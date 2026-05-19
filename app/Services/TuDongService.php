<?php
namespace App\Services;

use App\Repositories\TuDongRepository;
use App\Helpers\MqttHelper;
use App\Helpers\MqttNotificationQueue;

class TuDongService {
    private $tuDongRepo;
    private const DAY_LABELS = [
        'mon' => 'T2',
        'tue' => 'T3',
        'wed' => 'T4',
        'thu' => 'T5',
        'fri' => 'T6',
        'sat' => 'T7',
        'sun' => 'CN',
    ];
    private const SENSOR_LABELS = [
        'temp' => 'Nhiệt độ',
        'hum' => 'Độ ẩm',
        'gas' => 'Khí gas',
        'pir' => 'Cảm biến PIR',
    ];
    private const SENSOR_UNITS = [
        'temp' => '°C',
        'hum' => '%',
        'gas' => 'ppm',
        'pir' => '',
    ];

    public function __construct() {
        $this->tuDongRepo = new TuDongRepository();
    }

    public function layTatCaKichBan() {
        $kichBans = $this->tuDongRepo->layTatCaKichBan();

        foreach ($kichBans as &$kb) {
            $parsed = $this->tachDieuKien($kb['dieuKien'] ?? '');
            $action = $this->tachHanhDong($kb['hanhDong'] ?? '');
            $kb['trangThaiText'] = !empty($kb['kichHoat']) ? 'Đang bật' : 'Đã tắt';
            $kb['dieuKienHienThi'] = $this->taoMoTaDieuKien($parsed, $kb);
            $kb['hanhDongHienThi'] = $this->taoMoTaHanhDong($action);
            $kb['ngayApDungLabels'] = $this->dinhDangNgayApDung($kb['ngayApDung'] ?? '');
            $kb['loaiHienThi'] = ($kb['loaiKichBan'] ?? 'event') === 'schedule' ? 'Theo lịch' : 'Theo cảm biến';
            $kb['phongIds'] = $this->parseCsvIds($kb['danhSachPhong'] ?? '');
        }

        return $kichBans;
    }

    public function layDuLieuDongBo($filters = []) {
        $kichBans = $this->tuDongRepo->layKichBanDangKichHoat($filters);

        $normalized = array_map(function ($kb) {
            return [
                'idKichBan' => intval($kb['idKichBan']),
                'tenKichBan' => (string) ($kb['tenKichBan'] ?? ''),
                'moTa' => (string) ($kb['moTa'] ?? ''),
                'dieuKien' => (string) ($kb['dieuKien'] ?? ''),
                'hanhDong' => (string) ($kb['hanhDong'] ?? ''),
                'loaiKichBan' => (string) ($kb['loaiKichBan'] ?? 'event'),
                'kichHoat' => intval($kb['kichHoat'] ?? 0),
                'gioBatDau' => $kb['gioBatDau'] ?: null,
                'gioKetThuc' => $kb['gioKetThuc'] ?: null,
                'ngayApDung' => $kb['ngayApDung'] ?: null,
                'thoiGianChoChay' => intval($kb['thoiGianChoChay'] ?? 0),
                'phongIds' => $kb['phongIds'] ?? [],
                'actions' => $kb['actions'] ?? [],
            ];
        }, $kichBans);

        $version = md5(json_encode($normalized, JSON_UNESCAPED_UNICODE));

        return [
            'version' => $version,
            'count' => count($normalized),
            'scripts' => $normalized,
        ];
    }

    public function layKichBanTheoId($id) {
        $kichBan = $this->tuDongRepo->layKichBanTheoId($id);
        if (!$kichBan) {
            return null;
        }

        $parsedCondition = $this->tachDieuKien($kichBan['dieuKien'] ?? '');
        $parsedAction = $this->tachHanhDong($kichBan['hanhDong'] ?? '');

        $kichBan['phongIds'] = $kichBan['phongIds'] ?? [];
        $kichBan['sensorType'] = $parsedCondition['type'] ?? 'temp';
        $kichBan['operator'] = $parsedCondition['op'] ?? '>';
        $kichBan['triggerValue'] = $parsedCondition['value'] ?? '';
        $kichBan['scheduleStart'] = $parsedCondition['start'] ?? ($kichBan['gioBatDau'] ?? '18:00');
        $kichBan['scheduleEnd'] = $parsedCondition['end'] ?? ($kichBan['gioKetThuc'] ?? '06:00');
        $kichBan['days'] = $parsedCondition['days'] ?? $this->parseDays($kichBan['ngayApDung'] ?? '');
        $kichBan['actionTarget'] = $parsedAction['device'] ?? (($kichBan['actions'][0]['giaTriLenh'] ?? '') ?: 'relay');
        $kichBan['actionState'] = $parsedAction['state'] ?? (($kichBan['actions'][0]['lenh'] ?? '') ?: 'ON');
        $kichBan['loaiKichBan'] = $kichBan['loaiKichBan'] ?? 'event';

        return $kichBan;
    }

    public function layDanhSachPhongApDung() {
        return $this->tuDongRepo->layDanhSachPhongApDung();
    }

    public function layTatCaCauHinh() {
        return $this->tuDongRepo->layTatCaCauHinh();
    }

    public function themKichBan($data) {
        $payload = $this->normalizeScriptPayload($data);
        if (!$payload['success']) {
            return $payload;
        }

        $id = $this->tuDongRepo->themKichBan($payload['data']);
        if ($id) {
            // Send MQTT notification to trigger immediate sync on ESP32
            $this->notifyDeviceSync();
            
            return [
                'success' => true,
                'message' => 'Thêm kịch bản thành công',
                'id' => $id,
            ];
        }

        return [
            'success' => false,
            'message' => 'Không thể lưu kịch bản vào cơ sở dữ liệu.',
        ];
    }

    public function suaKichBan($id, $data) {
        $payload = $this->normalizeScriptPayload($data);
        if (!$payload['success']) {
            return $payload;
        }

        $result = $this->tuDongRepo->updateKichBan($id, $payload['data']);
        if ($result) {
            // Send MQTT notification to trigger immediate sync on ESP32
            $this->notifyDeviceSync();
            
            return [
                'success' => true,
                'message' => 'Cập nhật kịch bản thành công',
            ];
        }

        return [
            'success' => false,
            'message' => 'Không thể cập nhật kịch bản.',
        ];
    }

    public function xoaKichBan($id) {
        $result = $this->tuDongRepo->xoaKichBan($id);
        if ($result) {
            $this->notifyDeviceSync();
        }
        return [
            'success' => (bool) $result,
            'message' => $result ? 'Xóa kịch bản thành công' : 'Không thể xóa kịch bản.',
        ];
    }

    public function toggleKichHoat($id) {
        $result = $this->tuDongRepo->toggleKichHoat($id);
        if ($result) {
            $this->notifyDeviceSync();
            $kb = $this->tuDongRepo->layKichBanTheoId($id);
            return [
                'success' => true,
                'message' => 'Cập nhật trạng thái thành công',
                'kichHoat' => intval($kb['kichHoat'] ?? 0),
            ];
        }

        return [
            'success' => false,
            'message' => 'Không thể đổi trạng thái kích hoạt.',
        ];
    }

    public function luuCauHinhTuDong($configs) {
        if (!is_array($configs) || empty($configs)) {
            return [
                'success' => false,
                'message' => 'Không có cấu hình nào để lưu.',
            ];
        }

        foreach ($configs as $idPhong => $config) {
            $idPhong = intval($idPhong);
            if ($idPhong <= 0) {
                continue;
            }

            $data = [
                'nhietDoMin' => $config['nhietDoMin'] ?? null,
                'nhietDoMax' => $config['nhietDoMax'] ?? null,
                'doAmMin' => $config['doAmMin'] ?? null,
                'doAmMax' => $config['doAmMax'] ?? null,
                'gasMax' => $config['gasMax'] ?? null,
                'khoiBaoChay' => $config['khoiBaoChay'] ?? null,
                'moTa' => $config['moTa'] ?? null,
            ];

            if (!$this->tuDongRepo->luuCauHinhPhong($idPhong, $data)) {
                return [
                    'success' => false,
                    'message' => 'Có lỗi khi lưu ngưỡng cho phòng #' . $idPhong,
                ];
            }
        }

        return [
            'success' => true,
            'message' => 'Đã lưu cấu hình cảnh báo cho các phòng.',
        ];
    }

    private function normalizeScriptPayload($data) {
        $tenKichBan = trim((string) ($data['tenKichBan'] ?? ''));
        $loaiKichBan = trim((string) ($data['loaiKichBan'] ?? 'event'));
        $moTa = trim((string) ($data['moTa'] ?? ''));
        $phongIds = $this->normalizeIdList($data['phongIds'] ?? ($data['idPhong'] ?? []));
        $thoiGianChoChay = intval($data['thoiGianChoChay'] ?? 0);
        $kichHoat = !empty($data['kichHoat']) ? 1 : 0;

        if ($tenKichBan === '') {
            return ['success' => false, 'message' => 'Vui lòng nhập tên kịch bản.'];
        }

        if (empty($phongIds)) {
            return ['success' => false, 'message' => 'Hãy chọn ít nhất một phòng áp dụng.'];
        }

        $dieuKien = trim((string) ($data['dieuKien'] ?? ''));
        $gioBatDau = null;
        $gioKetThuc = null;
        $ngayApDung = null;

        if ($dieuKien === '') {
            if ($loaiKichBan === 'schedule') {
                $gioBatDau = trim((string) ($data['gioBatDau'] ?? ''));
                $gioKetThuc = trim((string) ($data['gioKetThuc'] ?? ''));
                $days = $this->normalizeDays($data['ngayApDung'] ?? ($data['ngayLap'] ?? []));

                if ($gioBatDau === '' || $gioKetThuc === '') {
                    return ['success' => false, 'message' => 'Vui lòng nhập đủ giờ bắt đầu và giờ kết thúc.'];
                }

                if (empty($days)) {
                    return ['success' => false, 'message' => 'Hãy chọn ít nhất một ngày áp dụng cho lịch.'];
                }

                $ngayApDung = implode(',', $days);
                $dieuKien = sprintf('timer|start=%s|end=%s|days=%s', $gioBatDau, $gioKetThuc, $ngayApDung);
            } else {
                $sensorType = trim((string) ($data['camBien'] ?? $data['sensorType'] ?? 'temp'));
                $operator = trim((string) ($data['toanTu'] ?? $data['operator'] ?? '>'));
                $triggerValue = trim((string) ($data['giaTriKichHoat'] ?? $data['triggerValue'] ?? ''));

                if ($triggerValue === '' || !is_numeric($triggerValue)) {
                    return ['success' => false, 'message' => 'Giá trị kích hoạt phải là số hợp lệ.'];
                }

                $dieuKien = sprintf('sensor|type=%s|op=%s|value=%s', $sensorType, $operator, rtrim(rtrim((string) (float) $triggerValue, '0'), '.'));
                $loaiKichBan = 'event';
            }
        } else {
            $parsed = $this->tachDieuKien($dieuKien);
            if (($parsed['kind'] ?? '') === 'timer') {
                $loaiKichBan = 'schedule';
                $gioBatDau = $parsed['start'] ?? null;
                $gioKetThuc = $parsed['end'] ?? null;
                $ngayApDung = !empty($parsed['days']) ? implode(',', $parsed['days']) : null;
            }
        }

        $actions = [];
        if (!empty($data['actions']) && is_array($data['actions'])) {
            foreach ($data['actions'] as $index => $action) {
                $normalized = $this->normalizeActionPayload($action, $index + 1);
                if (!$normalized) {
                    continue;
                }
                $actions[] = $normalized;
            }
        }

        if (empty($actions)) {
            $target = trim((string) ($data['thietBi'] ?? $data['actionTarget'] ?? 'relay'));
            $state = strtoupper(trim((string) ($data['lenhThucThi'] ?? $data['actionState'] ?? 'ON')));
            $actions[] = $this->normalizeActionPayload([
                'giaTriLenh' => $target,
                'lenh' => $state,
            ], 1);
        }

        $actions = array_values(array_filter($actions));
        if (empty($actions)) {
            return ['success' => false, 'message' => 'Vui lòng cấu hình ít nhất một hành động cho kịch bản.'];
        }

        $hanhDong = trim((string) ($data['hanhDong'] ?? ''));
        if ($hanhDong === '') {
            $hanhDong = $this->taoChuoiHanhDongWokwi($actions[0]);
        }

        return [
            'success' => true,
            'data' => [
                'tenKichBan' => $tenKichBan,
                'moTa' => $moTa,
                'dieuKien' => $dieuKien,
                'hanhDong' => $hanhDong,
                'loaiKichBan' => $loaiKichBan,
                'kichHoat' => $kichHoat,
                'gioBatDau' => $gioBatDau,
                'gioKetThuc' => $gioKetThuc,
                'ngayApDung' => $ngayApDung,
                'thoiGianChoChay' => $thoiGianChoChay,
                'phongIds' => $phongIds,
                'actions' => $actions,
            ],
        ];
    }

    private function normalizeActionPayload($action, $order) {
        $target = trim((string) ($action['giaTriLenh'] ?? $action['device'] ?? 'relay'));
        $state = strtoupper(trim((string) ($action['lenh'] ?? $action['state'] ?? 'ON')));

        if ($target === '') {
            return null;
        }

        if (!in_array($state, ['ON', 'OFF'], true)) {
            $state = 'ON';
        }

        return [
            'thuTuChay' => max(1, intval($action['thuTuChay'] ?? $order)),
            'loaiHanhDong' => trim((string) ($action['loaiHanhDong'] ?? 'device_control')),
            'idThietBi' => !empty($action['idThietBi']) ? intval($action['idThietBi']) : null,
            'lenh' => $state,
            'giaTriLenh' => $target,
            'moTa' => trim((string) ($action['moTa'] ?? '')),
        ];
    }

    private function tachDieuKien($condition) {
        $condition = trim((string) $condition);
        if ($condition === '') {
            return [];
        }

        if (strpos($condition, 'sensor|') === 0) {
            return [
                'kind' => 'sensor',
                'type' => $this->extractToken($condition, 'type'),
                'op' => $this->extractToken($condition, 'op'),
                'value' => $this->extractToken($condition, 'value'),
            ];
        }

        if (strpos($condition, 'timer|') === 0) {
            return [
                'kind' => 'timer',
                'start' => $this->extractToken($condition, 'start'),
                'end' => $this->extractToken($condition, 'end'),
                'days' => $this->parseDays($this->extractToken($condition, 'days')),
            ];
        }

        return [];
    }

    private function tachHanhDong($action) {
        $action = trim((string) $action);
        if ($action === '' || strpos($action, 'action|') !== 0) {
            return [];
        }

        return [
            'device' => $this->extractToken($action, 'device'),
            'state' => strtoupper($this->extractToken($action, 'state')),
        ];
    }

    private function taoMoTaDieuKien($parsed, $row) {
        if (($parsed['kind'] ?? '') === 'sensor') {
            $type = $parsed['type'] ?? 'temp';
            $unit = self::SENSOR_UNITS[$type] ?? '';
            $value = (string) ($parsed['value'] ?? '');
            return sprintf(
                '%s %s %s%s',
                self::SENSOR_LABELS[$type] ?? $type,
                $parsed['op'] ?? '>',
                $value,
                $unit
            );
        }

        if (($parsed['kind'] ?? '') === 'timer') {
            $days = !empty($parsed['days']) ? $this->dinhDangNgayApDung(implode(',', $parsed['days'])) : 'Mỗi ngày';
            return sprintf(
                'Từ %s đến %s (%s)',
                $parsed['start'] ?? ($row['gioBatDau'] ?? '--:--'),
                $parsed['end'] ?? ($row['gioKetThuc'] ?? '--:--'),
                $days
            );
        }

        return (string) ($row['dieuKien'] ?? '');
    }

    private function taoMoTaHanhDong($parsedAction) {
        if (empty($parsedAction)) {
            return 'Không có hành động';
        }

        $deviceLabel = $this->labelForActionTarget($parsedAction['device'] ?? '');
        $stateLabel = strtoupper($parsedAction['state'] ?? 'ON') === 'OFF' ? 'Tắt' : 'Bật';

        return $stateLabel . ' ' . $deviceLabel;
    }

    private function taoChuoiHanhDongWokwi($action) {
        $device = trim((string) ($action['giaTriLenh'] ?? 'relay'));
        $state = strtoupper(trim((string) ($action['lenh'] ?? 'ON')));
        return sprintf('action|device=%s|state=%s', $device, $state);
    }

    private function dinhDangNgayApDung($days) {
        $labels = [];
        foreach ($this->parseDays($days) as $day) {
            $labels[] = self::DAY_LABELS[$day] ?? strtoupper($day);
        }

        return empty($labels) ? 'Mỗi ngày' : implode(', ', $labels);
    }

    private function parseDays($days) {
        if (is_array($days)) {
            $rawDays = $days;
        } else {
            $rawDays = explode(',', (string) $days);
        }

        $normalized = [];
        foreach ($rawDays as $day) {
            $day = strtolower(trim((string) $day));
            if ($day !== '' && isset(self::DAY_LABELS[$day]) && !in_array($day, $normalized, true)) {
                $normalized[] = $day;
            }
        }

        return $normalized;
    }

    private function normalizeDays($days) {
        return $this->parseDays($days);
    }

    private function normalizeIdList($values) {
        if (!is_array($values)) {
            $values = [$values];
        }

        $ids = [];
        foreach ($values as $value) {
            $id = intval($value);
            if ($id > 0 && !in_array($id, $ids, true)) {
                $ids[] = $id;
            }
        }

        return $ids;
    }

    private function parseCsvIds($value) {
        return $this->normalizeIdList(explode(',', (string) $value));
    }

    private function extractToken($source, $key) {
        $token = $key . '=';
        $start = strpos((string) $source, $token);
        if ($start === false) {
            return '';
        }

        $start += strlen($token);
        $end = strpos((string) $source, '|', $start);
        if ($end === false) {
            $end = strlen((string) $source);
        }

        return trim(substr((string) $source, $start, $end - $start));
    }

    private function labelForActionTarget($target) {
        $target = strtolower(trim((string) $target));
        $map = [
            'relay' => 'relay',
            'buzzer' => 'còi',
            'fan' => 'quạt',
            'all' => 'toàn bộ thiết bị',
        ];

        return $map[$target] ?? ($target !== '' ? $target : 'thiết bị');
    }

    private function notifyDeviceSync() {
        try {
            // Enqueue notification async to avoid blocking the request
            $topic = 'nhatro123/room2/notification';
            $message = json_encode(['action' => 'sync_now'], JSON_UNESCAPED_UNICODE);
            
            // Try immediate publish first (fast path)
            try {
                $mqtt = MqttHelper::getInstance();
                if ($mqtt->publish($topic, $message)) {
                    error_log('[TuDongService] Instant notification sent');
                    return;
                }
            } catch (\Throwable $e) {
                error_log('[TuDongService] Instant publish failed: ' . $e->getMessage());
            }
            
            // Fallback to queue for retry
            MqttNotificationQueue::enqueue($topic, $message);
        } catch (\Throwable $e) {
            error_log('[TuDongService] Failed to notify device: ' . $e->getMessage());
            // Don't throw - notification is optional, main operation should succeed
        }
    }
}

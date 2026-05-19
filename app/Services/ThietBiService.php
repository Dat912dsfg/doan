<?php
namespace App\Services;

use App\Repositories\ThietBiRepository;

class ThietBiService {
    private $thietBiRepo;
    private const ONLINE_TIMEOUT_SECONDS = 6;

    public function __construct() {
        $this->thietBiRepo = new ThietBiRepository();
    }

    public function layDanhSachThietBi() {
        $devices = $this->thietBiRepo->layTatCaThietBi();

        foreach ($devices as &$device) {
            $rawTopic = trim((string) ($device['topicMqtt'] ?? ''));
            $heartbeatAt = !empty($device['lastHeartbeat']) ? strtotime((string) $device['lastHeartbeat']) : false;

            if ($heartbeatAt !== false) {
                $isOnline = $heartbeatAt >= (time() - self::ONLINE_TIMEOUT_SECONDS);
            } else {
                $isOnline = intval($device['trangThaiKetNoi'] ?? 0) === 1;
            }

            $device['isOnline'] = $isOnline;
            $device['ketNoiLabel'] = $isOnline ? 'Online' : 'Offline';
            $device['tenPhong'] = !empty($device['tenPhong']) ? $device['tenPhong'] : 'Chưa gán phòng';
            $device['loaiDisplay'] = !empty($device['loaiThietBi']) ? $device['loaiThietBi'] : 'Chưa phân loại';
            $device['sensorTopic'] = $this->buildSensorTopic($rawTopic);
            $device['topicDisplay'] = $device['sensorTopic'] !== '' ? $device['sensorTopic'] : 'Chưa cấu hình';
            $device['controlTopic'] = $this->buildControlTopic($rawTopic);
            $device['controlTopicDisplay'] = $device['controlTopic'] !== '' ? $device['controlTopic'] : 'Chưa cấu hình';
            $device['ipDisplay'] = !empty($device['diaChiIp']) ? $device['diaChiIp'] : 'Chưa cấu hình';
            $device['heartbeatDisplay'] = $heartbeatAt !== false
                ? date('d/m/Y H:i:s', $heartbeatAt)
                : 'Chưa ghi nhận';
            $device['firmwareDisplay'] = !empty($device['firmwareVersion']) ? $device['firmwareVersion'] : 'N/A';
        }

        return $devices;
    }

    public function layDanhSachPhong() {
        $rooms = $this->thietBiRepo->layTatCaPhong();

        foreach ($rooms as &$room) {
            $room['trangThaiLabel'] = intval($room['trangThai'] ?? 0) === 1 ? 'Đang sử dụng' : 'Tạm khóa';
            $room['cheDoLabel'] = intval($room['cheDoTuDong'] ?? 0) === 1 ? 'Tự động' : 'Thủ công';
            $room['moTaDisplay'] = !empty($room['moTa']) ? $room['moTa'] : 'Chưa có mô tả cho phòng này.';
            $room['viTriDisplay'] = !empty($room['viTri']) ? $room['viTri'] : 'Chưa cập nhật vị trí';
            $room['dienTichDisplay'] = !empty($room['dienTich'])
                ? rtrim(rtrim((string) $room['dienTich'], '0'), '.') . ' m²'
                : 'Chưa cập nhật';
        }

        return $rooms;
    }

    public function layDanhSachPhongChoForm() {
        return $this->thietBiRepo->layDanhSachPhongChoForm();
    }

    public function layThietBiTheoId($id) {
        return $this->thietBiRepo->layThietBiTheoId($id);
    }

    public function layPhongTheoId($id) {
        return $this->thietBiRepo->layPhongTheoId($id);
    }

    public function toggleTrangThaiPhong($id) {
        if (!$this->thietBiRepo->layPhongTheoId($id)) {
            return ['success' => false, 'message' => 'Phòng không tồn tại.'];
        }

        return $this->thietBiRepo->toggleTrangThaiPhong($id)
            ? ['success' => true]
            : ['success' => false, 'message' => 'Không thể đổi trạng thái phòng.'];
    }

    public function toggleCheDoPhong($id) {
        if (!$this->thietBiRepo->layPhongTheoId($id)) {
            return ['success' => false, 'message' => 'Phòng không tồn tại.'];
        }

        return $this->thietBiRepo->toggleCheDoPhong($id)
            ? ['success' => true]
            : ['success' => false, 'message' => 'Không thể đổi chế độ phòng.'];
    }

    public function themThietBi($data) {
        $validation = $this->validateThietBi($data);
        if ($validation !== true) {
            return ['success' => false, 'message' => $validation];
        }

        $id = $this->thietBiRepo->themThietBi($data);
        return $id
            ? ['success' => true, 'id' => $id]
            : ['success' => false, 'message' => 'Không thể thêm node thiết bị.'];
    }

    public function suaThietBi($id, $data) {
        if (!$this->thietBiRepo->layThietBiTheoId($id)) {
            return ['success' => false, 'message' => 'Thiết bị không tồn tại.'];
        }

        $validation = $this->validateThietBi($data);
        if ($validation !== true) {
            return ['success' => false, 'message' => $validation];
        }

        $ok = $this->thietBiRepo->capNhatThietBi($id, $data);
        return $ok
            ? ['success' => true]
            : ['success' => false, 'message' => 'Không thể cập nhật node thiết bị.'];
    }

    public function xoaThietBi($id) {
        if (!$this->thietBiRepo->layThietBiTheoId($id)) {
            return ['success' => false, 'message' => 'Thiết bị không tồn tại.'];
        }

        $ok = $this->thietBiRepo->xoaThietBi($id);
        return $ok
            ? ['success' => true]
            : ['success' => false, 'message' => 'Không thể xóa node thiết bị.'];
    }

    public function themPhong($data) {
        $validation = $this->validatePhong($data);
        if ($validation !== true) {
            return ['success' => false, 'message' => $validation];
        }

        $id = $this->thietBiRepo->themPhong($data);
        return $id
            ? ['success' => true, 'id' => $id]
            : ['success' => false, 'message' => 'Không thể thêm phòng mới.'];
    }

    public function suaPhong($id, $data) {
        if (!$this->thietBiRepo->layPhongTheoId($id)) {
            return ['success' => false, 'message' => 'Phòng không tồn tại.'];
        }

        $validation = $this->validatePhong($data);
        if ($validation !== true) {
            return ['success' => false, 'message' => $validation];
        }

        $ok = $this->thietBiRepo->capNhatPhong($id, $data);
        return $ok
            ? ['success' => true]
            : ['success' => false, 'message' => 'Không thể cập nhật thông tin phòng.'];
    }

    public function xoaPhong($id) {
        if (!$this->thietBiRepo->layPhongTheoId($id)) {
            return ['success' => false, 'message' => 'Phòng không tồn tại.'];
        }

        $ok = $this->thietBiRepo->xoaPhong($id);
        return $ok
            ? ['success' => true]
            : ['success' => false, 'message' => 'Không thể xóa phòng.'];
    }

    private function validateThietBi($data) {
        if (empty(trim($data['tenThietBi'] ?? ''))) {
            return 'Vui lòng nhập tên node thiết bị.';
        }

        if (intval($data['idPhong'] ?? 0) <= 0) {
            return 'Vui lòng chọn phòng cho node.';
        }

        $ip = trim($data['diaChiIp'] ?? '');
        if ($ip !== '' && !filter_var($ip, FILTER_VALIDATE_IP)) {
            return 'Địa chỉ IP chưa đúng định dạng.';
        }

        return true;
    }

    private function validatePhong($data) {
        if (empty(trim($data['tenPhong'] ?? ''))) {
            return 'Vui lòng nhập tên phòng.';
        }

        $dienTich = trim((string) ($data['dienTich'] ?? ''));
        if ($dienTich !== '' && !is_numeric($dienTich)) {
            return 'Diện tích phải là số hợp lệ.';
        }

        return true;
    }

    private function buildControlTopic($topic) {
        if ($topic === '') {
            return '';
        }

        if ($this->endsWith($topic, '/control')) {
            return $topic;
        }

        if ($this->endsWith($topic, '/sensor')) {
            return substr($topic, 0, -strlen('/sensor')) . '/control';
        }

        return rtrim($topic, '/') . '/control';
    }

    private function buildSensorTopic($topic) {
        if ($topic === '') {
            return '';
        }

        if ($this->endsWith($topic, '/sensor')) {
            return $topic;
        }

        if ($this->endsWith($topic, '/control')) {
            return substr($topic, 0, -strlen('/control')) . '/sensor';
        }

        return rtrim($topic, '/') . '/sensor';
    }

    private function endsWith($value, $suffix) {
        if ($suffix === '') {
            return true;
        }

        return substr($value, -strlen($suffix)) === $suffix;
    }
}

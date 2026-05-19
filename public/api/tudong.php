<?php
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204);
    exit;
}

session_start();
error_reporting(E_ALL);
ini_set('display_errors', 0);

require_once __DIR__ . '/../../vendor/autoload.php';

use App\Controllers\TuDongController;
use App\Repositories\CanhBaoRepository;
use App\Repositories\ThietBiRepository;
use App\Repositories\TuDongRepository;
use Config\KetNoi;

try {
    $method = $_SERVER['REQUEST_METHOD'];
    $action = $_GET['action'] ?? null;
    $id = $_GET['id'] ?? null;

    $controller = new TuDongController();

    switch ($action) {
        case 'list':
            if ($method === 'GET') {
                $controller->getDanhSachKichBan();
                exit;
            }
            break;

        case 'sync':
            if ($method === 'GET') {
                $controller->getDuLieuDongBo();
                exit;
            }
            break;

        case 'get':
            if ($method === 'GET' && $id) {
                $controller->layChiTietKichBan($id);
                exit;
            }
            break;

        case 'add':
            if ($method === 'POST') {
                $controller->themKichBan();
                exit;
            }
            break;

        case 'edit':
            if ($method === 'POST' && $id) {
                $controller->suaKichBan($id);
                exit;
            }
            break;

        case 'delete':
            if ($method === 'POST' && $id) {
                $controller->xoaKichBan($id);
                exit;
            }
            break;

        case 'toggle':
            if ($method === 'POST' && $id) {
                $controller->toggleKichHoat($id);
                exit;
            }
            break;

        case 'sensor-data':
            if ($method === 'POST') {
                handleSensorData();
                exit;
            }
            break;

        case 'device-status':
            if ($method === 'POST') {
                handleDeviceStatus();
                exit;
            }
            break;

        case 'action-report':
            if ($method === 'POST') {
                handleActionReport();
                exit;
            }
            break;

        case 'wokwi-init':
            if ($method === 'GET') {
                handleWokwiInit();
                exit;
            }
            break;

        case 'alerts':
            if ($method === 'GET') {
                handleGetAlerts();
                exit;
            }
            break;

        case 'alert-acknowledge':
            if ($method === 'POST' && $id) {
                handleAcknowledgeAlert($id);
                exit;
            }
            break;

        case 'alert-resolve':
            if ($method === 'POST' && $id) {
                handleResolveAlert($id);
                exit;
            }
            break;
    }

    jsonResponse([
        'success' => false,
        'message' => 'Action không hợp lệ',
    ], 400);
} catch (Throwable $e) {
    jsonResponse([
        'success' => false,
        'message' => 'Server error: ' . $e->getMessage(),
    ], 500);
}

function handleSensorData() {
    $input = json_decode(file_get_contents('php://input'), true);
    if (empty($input['sensors']) || !is_array($input['sensors'])) {
        jsonResponse(['success' => false, 'message' => 'Thiếu danh sách cảm biến'], 400);
    }

    $conn = (new KetNoi())->getConn();
    $tuDongRepo = new TuDongRepository();
    $alertRepo = new CanhBaoRepository();
    $results = [];

    foreach ($input['sensors'] as $sensor) {
        $idCamBien = intval($sensor['idCamBien'] ?? 0);
        $giaTri = isset($sensor['giaTri']) && is_numeric($sensor['giaTri']) ? (float) $sensor['giaTri'] : null;

        if ($idCamBien <= 0 || $giaTri === null) {
            $results[] = ['idCamBien' => $idCamBien, 'status' => 'invalid'];
            continue;
        }

        $sql = "INSERT INTO du_lieu_cam_bien (idCamBien, giaTri, trangThai) VALUES ($idCamBien, $giaTri, 1)";
        if (!mysqli_query($conn, $sql)) {
            $results[] = [
                'idCamBien' => $idCamBien,
                'status' => 'error',
                'error' => mysqli_error($conn),
            ];
            continue;
        }

        $sensorInfo = $tuDongRepo->layThongTinCamBien($idCamBien);
        $alertStatus = 'normal';
        if ($sensorInfo && intval($sensorInfo['idPhong'] ?? 0) > 0 && intval($sensorInfo['cheDoTuDong'] ?? 0) === 1) {
            $evaluation = evaluateThresholdAlert($tuDongRepo, $sensorInfo, $giaTri);

            if (!empty($evaluation['triggered'])) {
                $alertRepo->taoHoacCapNhatCanhBaoNguong([
                    'idPhong' => intval($sensorInfo['idPhong']),
                    'idCamBien' => $idCamBien,
                    'tenCanhBao' => $evaluation['title'],
                    'noiDung' => $evaluation['message'],
                    'loaiCanhBao' => 'threshold',
                    'mucDo' => $evaluation['severity'],
                    'dieuKien' => $evaluation['condition'],
                    'giaTriDo' => $giaTri,
                    'nguongViPham' => $evaluation['threshold'],
                ]);
                $alertStatus = 'triggered';
            } elseif (!empty($evaluation['resolvedConditions'])) {
                foreach ($evaluation['resolvedConditions'] as $resolvedCondition) {
                    $alertRepo->dongCanhBaoTheoDieuKien(
                        intval($sensorInfo['idPhong']),
                        $idCamBien,
                        $resolvedCondition,
                        'Giá trị đã quay lại ngưỡng an toàn'
                    );
                }
                $alertStatus = 'resolved';
            }
        }

        $results[] = [
            'idCamBien' => $idCamBien,
            'status' => 'saved',
            'alert' => $alertStatus,
        ];
    }

    jsonResponse([
        'success' => true,
        'message' => 'Sensor data processed',
        'results' => $results,
    ]);
}

function handleDeviceStatus() {
    $input = json_decode(file_get_contents('php://input'), true);
    $thietBiRepo = new ThietBiRepository();

    if (!empty($input['topic'])) {
        $ok = $thietBiRepo->capNhatHeartbeatTheoTopic(
            (string) $input['topic'],
            $input['ip'] ?? null,
            $input['firmwareVersion'] ?? null
        );

        if ($ok) {
            jsonResponse([
                'success' => true,
                'message' => 'Device status updated by topic',
                'timestamp' => date('Y-m-d H:i:s'),
            ]);
        }

        jsonResponse(['success' => false, 'message' => 'Không tìm thấy thiết bị theo topic'], 404);
    }

    if (empty($input['idThietBi'])) {
        jsonResponse(['success' => false, 'message' => 'Missing idThietBi or topic'], 400);
    }

    $conn = (new KetNoi())->getConn();
    $idThietBi = intval($input['idThietBi']);
    $trangThaiKetNoi = intval($input['trangThaiKetNoi'] ?? 1);
    $firmwareVersion = mysqli_real_escape_string($conn, $input['firmwareVersion'] ?? '');

    $sql = "UPDATE thiet_bi
            SET trangThaiKetNoi = $trangThaiKetNoi,
                lastHeartbeat = NOW()" .
        ($firmwareVersion !== '' ? ", firmwareVersion = '$firmwareVersion'" : '') .
        " WHERE idThietBi = $idThietBi";

    if (!mysqli_query($conn, $sql)) {
        jsonResponse(['success' => false, 'message' => 'Failed to update device status'], 500);
    }

    jsonResponse([
        'success' => true,
        'message' => 'Device status updated',
        'timestamp' => date('Y-m-d H:i:s'),
    ]);
}

function handleActionReport() {
    $input = json_decode(file_get_contents('php://input'), true);
    $conn = (new KetNoi())->getConn();
    $repo = new TuDongRepository();

    $idKichBan = intval($input['idKichBan'] ?? 0);
    $status = mysqli_real_escape_string($conn, $input['status'] ?? 'unknown');
    $result = mysqli_real_escape_string($conn, $input['result'] ?? '');

    $sql = "INSERT INTO nhat_ky (idKichBan, loaiNhatKy, hanhDong, noiDung, ketQua)
            VALUES (" . ($idKichBan > 0 ? $idKichBan : 'NULL') . ", 'script_exec', 'Thực thi kịch bản', '$result', '$status')";
    mysqli_query($conn, $sql);

    if ($idKichBan > 0) {
        $repo->capNhatThoiGianChay($idKichBan);
    }

    jsonResponse(['success' => true, 'message' => 'Report recorded']);
}

function handleWokwiInit() {
    $conn = (new KetNoi())->getConn();
    $tuDongController = new TuDongController();

    $deviceSql = "SELECT idThietBi, idPhong, tenThietBi, loaiThietBi, topicMqtt FROM thiet_bi ORDER BY idThietBi ASC";
    $devices = fetchAllAssoc($conn, $deviceSql);

    $sensorSql = "SELECT cb.*, tb.idPhong, tb.topicMqtt
                  FROM cam_bien cb
                  INNER JOIN thiet_bi tb ON tb.idThietBi = cb.idThietBi
                  WHERE cb.trangThai = 1
                  ORDER BY cb.idCamBien ASC";
    $sensors = fetchAllAssoc($conn, $sensorSql);

    $configSql = "SELECT * FROM cau_hinh ORDER BY idPhong ASC";
    $config = fetchAllAssoc($conn, $configSql);

    ob_start();
    $tuDongController->getDuLieuDongBo();
    $syncPayload = json_decode((string) ob_get_clean(), true);

    jsonResponse([
        'success' => true,
        'timestamp' => date('Y-m-d H:i:s'),
        'devices' => $devices,
        'scripts' => $syncPayload['data'] ?? [],
        'sensors' => $sensors,
        'config' => $config,
        'apiVersion' => '2.0',
        'endpoints' => [
            'sync' => '/api/tudong.php?action=sync',
            'sensor-data' => '/api/tudong.php?action=sensor-data',
            'device-status' => '/api/tudong.php?action=device-status',
            'action-report' => '/api/tudong.php?action=action-report',
        ],
    ]);
}

function handleGetAlerts() {
    $idPhong = $_GET['idPhong'] ?? null;
    $type = $_GET['type'] ?? 'active';
    $alertRepo = new CanhBaoRepository();

    if ($type === 'history') {
        $alerts = $alertRepo->layLichSuCanhBao($idPhong, 100);
    } elseif ($type === 'critical') {
        $alerts = array_values(array_filter($alertRepo->layAlertHoatDong($idPhong), function ($alert) {
            return strtolower((string) ($alert['mucDo'] ?? '')) === 'critical';
        }));
    } else {
        $alerts = $alertRepo->layAlertHoatDong($idPhong);
    }

    jsonResponse([
        'success' => true,
        'alerts' => $alerts,
        'statistics' => $alertRepo->layThongKeAlerts($idPhong),
        'count' => count($alerts),
        'type' => $type,
    ]);
}

function handleAcknowledgeAlert($id) {
    $input = json_decode(file_get_contents('php://input'), true);
    $idNguoiDung = intval($input['idNguoiDung'] ?? ($_SESSION['user_id'] ?? 1));
    $ghiChu = (string) ($input['ghiChu'] ?? '');

    $alertRepo = new CanhBaoRepository();
    if (!$alertRepo->xacNhanCanhBao($id, $idNguoiDung, $ghiChu)) {
        jsonResponse(['success' => false, 'message' => 'Failed to acknowledge alert'], 500);
    }

    jsonResponse(['success' => true, 'message' => 'Alert acknowledged']);
}

function handleResolveAlert($id) {
    $input = json_decode(file_get_contents('php://input'), true);
    $ghiChu = (string) ($input['ghiChu'] ?? '');

    $alertRepo = new CanhBaoRepository();
    if (!$alertRepo->giaiQuyetCanhBao($id, $ghiChu)) {
        jsonResponse(['success' => false, 'message' => 'Failed to resolve alert'], 500);
    }

    jsonResponse(['success' => true, 'message' => 'Alert resolved']);
}

function evaluateThresholdAlert(TuDongRepository $tuDongRepo, $sensorInfo, $giaTri) {
    $idPhong = intval($sensorInfo['idPhong'] ?? 0);
    if ($idPhong <= 0) {
        return ['triggered' => false];
    }

    $config = $tuDongRepo->layCauHinhTheoPhong($idPhong);
    if (!$config) {
        return ['triggered' => false];
    }

    $type = strtolower(trim((string) ($sensorInfo['loaiCamBien'] ?? '')));
    $title = '';
    $message = '';
    $condition = '';
    $threshold = null;
    $severity = 'warning';

    if (in_array($type, ['temp', 'temperature'], true)) {
        if (is_numeric($config['nhietDoMax'] ?? null) && $giaTri > (float) $config['nhietDoMax']) {
            $threshold = (float) $config['nhietDoMax'];
            $condition = 'threshold|type=temp|max=' . $threshold;
            $title = 'Nhiệt độ vượt ngưỡng';
            $message = 'Nhiệt độ tại ' . ($sensorInfo['tenPhong'] ?? 'phòng') . ' đang cao hơn mức cho phép.';
            $severity = 'warning';
        } elseif (is_numeric($config['nhietDoMin'] ?? null) && $giaTri < (float) $config['nhietDoMin']) {
            $threshold = (float) $config['nhietDoMin'];
            $condition = 'threshold|type=temp|min=' . $threshold;
            $title = 'Nhiệt độ xuống thấp';
            $message = 'Nhiệt độ tại ' . ($sensorInfo['tenPhong'] ?? 'phòng') . ' đang thấp hơn mức tối thiểu.';
            $severity = 'warning';
        }
    } elseif (in_array($type, ['hum', 'humidity'], true)) {
        if (is_numeric($config['doAmMax'] ?? null) && $giaTri > (float) $config['doAmMax']) {
            $threshold = (float) $config['doAmMax'];
            $condition = 'threshold|type=hum|max=' . $threshold;
            $title = 'Độ ẩm vượt ngưỡng';
            $message = 'Độ ẩm tại ' . ($sensorInfo['tenPhong'] ?? 'phòng') . ' đang cao hơn mức cho phép.';
            $severity = 'warning';
        } elseif (is_numeric($config['doAmMin'] ?? null) && $giaTri < (float) $config['doAmMin']) {
            $threshold = (float) $config['doAmMin'];
            $condition = 'threshold|type=hum|min=' . $threshold;
            $title = 'Độ ẩm xuống thấp';
            $message = 'Độ ẩm tại ' . ($sensorInfo['tenPhong'] ?? 'phòng') . ' đang thấp hơn mức tối thiểu.';
            $severity = 'warning';
        }
    } elseif ($type === 'gas') {
        if (is_numeric($config['gasMax'] ?? null) && $giaTri > (float) $config['gasMax']) {
            $threshold = (float) $config['gasMax'];
            $condition = 'threshold|type=gas|max=' . $threshold;
            $title = 'Phát hiện khí gas bất thường';
            $message = 'Nồng độ gas tại ' . ($sensorInfo['tenPhong'] ?? 'phòng') . ' đang vượt mức an toàn.';
            $severity = 'critical';
        }
    } elseif (in_array($type, ['smoke', 'khoi'], true)) {
        if (is_numeric($config['khoiBaoChay'] ?? null) && $giaTri > (float) $config['khoiBaoChay']) {
            $threshold = (float) $config['khoiBaoChay'];
            $condition = 'threshold|type=smoke|max=' . $threshold;
            $title = 'Khói vượt ngưỡng';
            $message = 'Cảm biến khói tại ' . ($sensorInfo['tenPhong'] ?? 'phòng') . ' đang báo động.';
            $severity = 'critical';
        }
    } elseif (in_array($type, ['pir', 'motion'], true) && $giaTri >= 1) {
        $threshold = 1;
        $condition = 'threshold|type=pir|eq=1';
        $title = 'Phát hiện chuyển động';
        $message = 'Cảm biến PIR tại ' . ($sensorInfo['tenPhong'] ?? 'phòng') . ' vừa phát hiện chuyển động.';
        $severity = 'critical';
    }

    if ($condition !== '') {
        return [
            'triggered' => true,
            'title' => $title,
            'message' => $message,
            'condition' => $condition,
            'threshold' => $threshold,
            'severity' => $severity,
        ];
    }

    return [
        'triggered' => false,
        'resolvedConditions' => inferResolvedConditions($config, $type, $giaTri),
    ];
}

function inferResolvedConditions($config, $type, $giaTri) {
    $conditions = [];

    if (in_array($type, ['temp', 'temperature'], true)) {
        if (is_numeric($config['nhietDoMax'] ?? null) && $giaTri <= (float) $config['nhietDoMax']) {
            $conditions[] = 'threshold|type=temp|max=' . (float) $config['nhietDoMax'];
        }
        if (is_numeric($config['nhietDoMin'] ?? null) && $giaTri >= (float) $config['nhietDoMin']) {
            $conditions[] = 'threshold|type=temp|min=' . (float) $config['nhietDoMin'];
        }
    }

    if (in_array($type, ['hum', 'humidity'], true)) {
        if (is_numeric($config['doAmMax'] ?? null) && $giaTri <= (float) $config['doAmMax']) {
            $conditions[] = 'threshold|type=hum|max=' . (float) $config['doAmMax'];
        }
        if (is_numeric($config['doAmMin'] ?? null) && $giaTri >= (float) $config['doAmMin']) {
            $conditions[] = 'threshold|type=hum|min=' . (float) $config['doAmMin'];
        }
    }

    if ($type === 'gas' && is_numeric($config['gasMax'] ?? null) && $giaTri <= (float) $config['gasMax']) {
        $conditions[] = 'threshold|type=gas|max=' . (float) $config['gasMax'];
    }

    if (in_array($type, ['smoke', 'khoi'], true) && is_numeric($config['khoiBaoChay'] ?? null) && $giaTri <= (float) $config['khoiBaoChay']) {
        $conditions[] = 'threshold|type=smoke|max=' . (float) $config['khoiBaoChay'];
    }

    if (in_array($type, ['pir', 'motion'], true) && $giaTri < 1) {
        $conditions[] = 'threshold|type=pir|eq=1';
    }

    return array_values(array_unique($conditions));
}

function fetchAllAssoc($conn, $sql) {
    $result = mysqli_query($conn, $sql);
    $rows = [];
    if (!$result) {
        return $rows;
    }

    while ($row = mysqli_fetch_assoc($result)) {
        $rows[] = $row;
    }

    return $rows;
}

function jsonResponse($payload, $status = 200) {
    http_response_code($status);
    echo json_encode($payload, JSON_UNESCAPED_UNICODE);
    exit;
}

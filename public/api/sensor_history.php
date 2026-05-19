<?php
header('Content-Type: application/json; charset=utf-8');

session_start();

if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode([
        'success' => false,
        'message' => 'Unauthorized',
    ], JSON_UNESCAPED_UNICODE);
    exit;
}

require_once __DIR__ . '/../../vendor/autoload.php';

use App\Repositories\ThietBiRepository;

date_default_timezone_set('Asia/Ho_Chi_Minh');

try {
    $deviceId = intval($_GET['deviceId'] ?? 0);
    if ($deviceId <= 0) {
        throw new RuntimeException('Thieu deviceId de tai lich su.');
    }

    $repo = new ThietBiRepository();
    $rows = $repo->layLichSuCamBienTheoThietBi($deviceId, 24);

    $labels = [];
    $tempValues = [];
    $humValues = [];

    foreach ($rows as $row) {
        $labels[] = date('H:i', strtotime($row['sample_time']));
        $tempValues[] = $row['temp_value'] !== null ? (float) $row['temp_value'] : null;
        $humValues[] = $row['hum_value'] !== null ? (float) $row['hum_value'] : null;
    }

    echo json_encode([
        'success' => true,
        'labels' => $labels,
        'datasets' => [
            'temp' => $tempValues,
            'hum' => $humValues,
        ],
        'hasData' => count($labels) > 0,
    ], JSON_UNESCAPED_UNICODE);
} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage(),
    ], JSON_UNESCAPED_UNICODE);
}

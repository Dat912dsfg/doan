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

try {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        http_response_code(405);
        echo json_encode([
            'success' => false,
            'message' => 'Method not allowed',
        ], JSON_UNESCAPED_UNICODE);
        exit;
    }

    $payload = json_decode(file_get_contents('php://input'), true);
    if (!is_array($payload)) {
        throw new RuntimeException('Du lieu gui len khong hop le.');
    }

    $topic = trim((string) ($payload['topic'] ?? ''));
    $temp = $payload['nhiet_do'] ?? null;
    $hum = $payload['do_am'] ?? null;
    $gas = $payload['gas'] ?? null;
    $pir = $payload['pir'] ?? null;

    if ($topic === '') {
        throw new RuntimeException('Thieu topic cam bien cua node.');
    }

    foreach (['temp' => $temp, 'hum' => $hum, 'gas' => $gas, 'pir' => $pir] as $key => $value) {
        if (!is_numeric($value)) {
            throw new RuntimeException("Thieu hoac sai du lieu cam bien: {$key}");
        }
    }

    $repo = new ThietBiRepository();
    $device = $repo->luuDuLieuCamBienTheoTopic($topic, [
        'nhiet_do' => $temp,
        'do_am' => $hum,
        'gas' => $gas,
        'pir' => $pir,
    ]);

    if ($device === false) {
        throw new RuntimeException('Khong the luu du lieu cam bien cho node theo topic da chon.');
    }

    echo json_encode([
        'success' => true,
        'deviceId' => intval($device['idThietBi'] ?? 0),
    ], JSON_UNESCAPED_UNICODE);
} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage(),
    ], JSON_UNESCAPED_UNICODE);
}

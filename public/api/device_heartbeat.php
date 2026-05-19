<?php
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204);
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
        throw new RuntimeException('Payload không hợp lệ.');
    }

    $topic = trim((string) ($payload['topic'] ?? ''));
    $ip = trim((string) ($payload['ip'] ?? ''));
    $firmware = trim((string) ($payload['firmwareVersion'] ?? ''));

    if ($topic === '') {
        throw new RuntimeException('Thiếu topic thiết bị.');
    }

    $repo = new ThietBiRepository();
    $updated = $repo->capNhatHeartbeatTheoTopic($topic, $ip, $firmware);

    if (!$updated) {
        http_response_code(404);
        echo json_encode([
            'success' => false,
            'message' => 'Không tìm thấy thiết bị khớp với topicMqtt.',
        ], JSON_UNESCAPED_UNICODE);
        exit;
    }

    echo json_encode([
        'success' => true,
        'message' => 'Heartbeat đã được cập nhật.',
    ], JSON_UNESCAPED_UNICODE);
} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage(),
    ], JSON_UNESCAPED_UNICODE);
}

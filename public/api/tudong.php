<?php
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204);
    exit;
}

session_start();
error_reporting(E_ALL);
ini_set('display_errors', 0);

require_once __DIR__ . '/../../vendor/autoload.php';

use App\Controllers\TuDongController;

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
    }

    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => 'Action không hợp lệ',
    ], JSON_UNESCAPED_UNICODE);
} catch (\Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Server error: ' . $e->getMessage(),
    ], JSON_UNESCAPED_UNICODE);
}

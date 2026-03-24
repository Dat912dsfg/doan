<?php
namespace App\Controllers;

use App\Services\TuDongService;

class TuDongController {
    private $tuDongService;

    public function __construct() {
        $this->tuDongService = new TuDongService();
    }

    public function layDanhSachKichBan() {
        return $this->tuDongService->layTatCaKichBan();
    }

    public function layKichBanTheoId($id) {
        return $this->tuDongService->layKichBanTheoId($id);
    }

    /**
     * API: Lấy tất cả kịch bản (JSON).
     */
    public function getDanhSachKichBan() {
        header('Content-Type: application/json; charset=utf-8');
        try {
            $kichBans = $this->tuDongService->layTatCaKichBan();
            echo json_encode([
                'success' => true,
                'data' => $kichBans,
            ], JSON_UNESCAPED_UNICODE);
        } catch (\Exception $e) {
            echo json_encode([
                'success' => false,
                'message' => $e->getMessage(),
            ], JSON_UNESCAPED_UNICODE);
        }
    }

    /**
     * API: Dữ liệu đồng bộ cho Wokwi/thiết bị.
     */
    public function getDuLieuDongBo() {
        header('Content-Type: application/json; charset=utf-8');
        try {
            $sync = $this->tuDongService->layDuLieuDongBo();
            echo json_encode([
                'success' => true,
                'version' => $sync['version'],
                'count' => $sync['count'],
                'data' => $sync['scripts'],
            ], JSON_UNESCAPED_UNICODE);
        } catch (\Exception $e) {
            echo json_encode([
                'success' => false,
                'message' => $e->getMessage(),
            ], JSON_UNESCAPED_UNICODE);
        }
    }

    /**
     * API: Thêm kịch bản mới.
     */
    public function themKichBan() {
        header('Content-Type: application/json; charset=utf-8');
        try {
            $data = json_decode(file_get_contents('php://input'), true);
            $result = $this->tuDongService->themKichBan($data);
            echo json_encode($result, JSON_UNESCAPED_UNICODE);
        } catch (\Exception $e) {
            echo json_encode([
                'success' => false,
                'message' => $e->getMessage(),
            ], JSON_UNESCAPED_UNICODE);
        }
    }

    /**
     * API: Cập nhật kịch bản.
     */
    public function suaKichBan($id) {
        header('Content-Type: application/json; charset=utf-8');
        try {
            $data = json_decode(file_get_contents('php://input'), true);
            $result = $this->tuDongService->suaKichBan($id, $data);
            echo json_encode($result, JSON_UNESCAPED_UNICODE);
        } catch (\Exception $e) {
            echo json_encode([
                'success' => false,
                'message' => $e->getMessage(),
            ], JSON_UNESCAPED_UNICODE);
        }
    }

    /**
     * API: Xóa kịch bản.
     */
    public function xoaKichBan($id) {
        header('Content-Type: application/json; charset=utf-8');
        try {
            $result = $this->tuDongService->xoaKichBan($id);
            echo json_encode($result, JSON_UNESCAPED_UNICODE);
        } catch (\Exception $e) {
            echo json_encode([
                'success' => false,
                'message' => $e->getMessage(),
            ], JSON_UNESCAPED_UNICODE);
        }
    }

    /**
     * API: Bật/tắt kích hoạt kịch bản.
     */
    public function toggleKichHoat($id) {
        header('Content-Type: application/json; charset=utf-8');
        try {
            $result = $this->tuDongService->toggleKichHoat($id);
            echo json_encode($result, JSON_UNESCAPED_UNICODE);
        } catch (\Exception $e) {
            echo json_encode([
                'success' => false,
                'message' => $e->getMessage(),
            ], JSON_UNESCAPED_UNICODE);
        }
    }

    public function webThemKichBan() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?page=tudong');
            exit;
        }

        $data = [
            'tenKichBan' => trim($_POST['tenKichBan'] ?? ''),
            'dieuKien' => trim($_POST['dieuKien'] ?? ''),
            'hanhDong' => trim($_POST['hanhDong'] ?? ''),
            'moTa' => trim($_POST['moTa'] ?? ''),
            'gioBatDau' => !empty($_POST['gioBatDau']) ? $_POST['gioBatDau'] : null,
            'gioKetThuc' => !empty($_POST['gioKetThuc']) ? $_POST['gioKetThuc'] : null,
            'kichHoat' => isset($_POST['kichHoat']) && intval($_POST['kichHoat']) === 0 ? 0 : 1,
        ];

        $result = $this->tuDongService->themKichBan($data);
        $_SESSION['msg'] = !empty($result['success']) ? 'add_success' : 'add_error';
        header('Location: index.php?page=tudong');
        exit;
    }

    public function webSuaKichBan() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?page=tudong');
            exit;
        }

        $id = intval($_POST['idKichBan'] ?? 0);
        if ($id <= 0) {
            $_SESSION['msg'] = 'edit_error';
            header('Location: index.php?page=tudong');
            exit;
        }

        $data = [
            'tenKichBan' => trim($_POST['tenKichBan'] ?? ''),
            'dieuKien' => trim($_POST['dieuKien'] ?? ''),
            'hanhDong' => trim($_POST['hanhDong'] ?? ''),
            'moTa' => trim($_POST['moTa'] ?? ''),
            'gioBatDau' => !empty($_POST['gioBatDau']) ? $_POST['gioBatDau'] : null,
            'gioKetThuc' => !empty($_POST['gioKetThuc']) ? $_POST['gioKetThuc'] : null,
            'kichHoat' => isset($_POST['kichHoat']) && intval($_POST['kichHoat']) === 0 ? 0 : 1,
        ];

        $result = $this->tuDongService->suaKichBan($id, $data);
        $_SESSION['msg'] = !empty($result['success']) ? 'edit_success' : 'edit_error';
        header('Location: index.php?page=tudong');
        exit;
    }

    public function webXoaKichBan() {
        $id = intval($_GET['id'] ?? 0);
        if ($id <= 0) {
            $_SESSION['msg'] = 'del_error';
            header('Location: index.php?page=tudong');
            exit;
        }

        $result = $this->tuDongService->xoaKichBan($id);
        $_SESSION['msg'] = !empty($result['success']) ? 'del_success' : 'del_error';
        header('Location: index.php?page=tudong');
        exit;
    }

    public function webToggleKichHoat() {
        $id = intval($_GET['id'] ?? 0);
        if ($id <= 0) {
            $_SESSION['msg'] = 'toggle_error';
            header('Location: index.php?page=tudong');
            exit;
        }

        $result = $this->tuDongService->toggleKichHoat($id);
        $_SESSION['msg'] = !empty($result['success']) ? 'toggle_success' : 'toggle_error';
        header('Location: index.php?page=tudong');
        exit;
    }
}

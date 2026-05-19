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

    public function layDanhSachPhongApDung() {
        return $this->tuDongService->layDanhSachPhongApDung();
    }

    public function layTatCaCauHinh() {
        return $this->tuDongService->layTatCaCauHinh();
    }

    public function getDanhSachKichBan() {
        header('Content-Type: application/json; charset=utf-8');

        try {
            echo json_encode([
                'success' => true,
                'data' => $this->tuDongService->layTatCaKichBan(),
            ], JSON_UNESCAPED_UNICODE);
        } catch (\Throwable $e) {
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'message' => $e->getMessage(),
            ], JSON_UNESCAPED_UNICODE);
        }
    }

    public function layChiTietKichBan($id) {
        header('Content-Type: application/json; charset=utf-8');

        try {
            $kichBan = $this->tuDongService->layKichBanTheoId($id);
            if (!$kichBan) {
                http_response_code(404);
                echo json_encode([
                    'success' => false,
                    'message' => 'Không tìm thấy kịch bản.',
                ], JSON_UNESCAPED_UNICODE);
                return;
            }

            echo json_encode([
                'success' => true,
                'data' => $kichBan,
            ], JSON_UNESCAPED_UNICODE);
        } catch (\Throwable $e) {
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'message' => $e->getMessage(),
            ], JSON_UNESCAPED_UNICODE);
        }
    }

    public function getDuLieuDongBo() {
        header('Content-Type: application/json; charset=utf-8');

        try {
            $filters = [
                'idPhong' => $_GET['idPhong'] ?? null,
            ];
            $sync = $this->tuDongService->layDuLieuDongBo($filters);

            echo json_encode([
                'success' => true,
                'version' => $sync['version'],
                'count' => $sync['count'],
                'data' => $sync['scripts'],
            ], JSON_UNESCAPED_UNICODE);
        } catch (\Throwable $e) {
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'message' => $e->getMessage(),
            ], JSON_UNESCAPED_UNICODE);
        }
    }

    public function themKichBan() {
        header('Content-Type: application/json; charset=utf-8');

        try {
            $data = json_decode(file_get_contents('php://input'), true);
            $result = $this->tuDongService->themKichBan(is_array($data) ? $data : []);
            echo json_encode($result, JSON_UNESCAPED_UNICODE);
        } catch (\Throwable $e) {
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'message' => $e->getMessage(),
            ], JSON_UNESCAPED_UNICODE);
        }
    }

    public function suaKichBan($id) {
        header('Content-Type: application/json; charset=utf-8');

        try {
            $data = json_decode(file_get_contents('php://input'), true);
            $result = $this->tuDongService->suaKichBan($id, is_array($data) ? $data : []);
            echo json_encode($result, JSON_UNESCAPED_UNICODE);
        } catch (\Throwable $e) {
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'message' => $e->getMessage(),
            ], JSON_UNESCAPED_UNICODE);
        }
    }

    public function xoaKichBan($id) {
        header('Content-Type: application/json; charset=utf-8');

        try {
            echo json_encode($this->tuDongService->xoaKichBan($id), JSON_UNESCAPED_UNICODE);
        } catch (\Throwable $e) {
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'message' => $e->getMessage(),
            ], JSON_UNESCAPED_UNICODE);
        }
    }

    public function toggleKichHoat($id) {
        header('Content-Type: application/json; charset=utf-8');

        try {
            echo json_encode($this->tuDongService->toggleKichHoat($id), JSON_UNESCAPED_UNICODE);
        } catch (\Throwable $e) {
            http_response_code(500);
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

        $result = $this->tuDongService->themKichBan($this->layDuLieuKichBanTuForm());
        $_SESSION['error_detail'] = !empty($result['success']) ? null : ($result['message'] ?? null);
        $_SESSION['msg'] = !empty($result['success']) ? 'add_success' : 'add_error';
        header('Location: index.php?page=tudong');
        exit;
    }

    public function webSuaKichBan() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?page=tudong');
            exit;
        }

        $id = intval($_POST['idKichBan'] ?? $_POST['id'] ?? 0);
        if ($id <= 0) {
            $_SESSION['msg'] = 'edit_error';
            header('Location: index.php?page=tudong');
            exit;
        }

        $result = $this->tuDongService->suaKichBan($id, $this->layDuLieuKichBanTuForm());
        $_SESSION['error_detail'] = !empty($result['success']) ? null : ($result['message'] ?? null);
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
        $_SESSION['error_detail'] = !empty($result['success']) ? null : ($result['message'] ?? null);
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
        $_SESSION['error_detail'] = !empty($result['success']) ? null : ($result['message'] ?? null);
        $_SESSION['msg'] = !empty($result['success']) ? 'toggle_success' : 'toggle_error';
        header('Location: index.php?page=tudong');
        exit;
    }

    public function webLuuCauHinh() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?page=tudong');
            exit;
        }

        $result = $this->tuDongService->luuCauHinhTuDong($_POST['config'] ?? []);
        $_SESSION['error_detail'] = !empty($result['success']) ? null : ($result['message'] ?? null);
        $_SESSION['msg'] = !empty($result['success']) ? 'edit_success' : 'edit_error';
        header('Location: index.php?page=tudong');
        exit;
    }

    private function layDuLieuKichBanTuForm() {
        return [
            'tenKichBan' => trim($_POST['tenKichBan'] ?? ''),
            'moTa' => trim($_POST['moTa'] ?? ''),
            'loaiKichBan' => trim($_POST['loaiKichBan'] ?? 'event'),
            'camBien' => trim($_POST['camBien'] ?? ''),
            'toanTu' => trim($_POST['toanTu'] ?? ''),
            'giaTriKichHoat' => trim($_POST['giaTriKichHoat'] ?? ''),
            'gioBatDau' => trim($_POST['gioBatDau'] ?? ''),
            'gioKetThuc' => trim($_POST['gioKetThuc'] ?? ''),
            'ngayApDung' => $_POST['ngayApDung'] ?? ($_POST['ngayLap'] ?? []),
            'thietBi' => trim($_POST['thietBi'] ?? ''),
            'lenhThucThi' => trim($_POST['lenhThucThi'] ?? ''),
            'thoiGianChoChay' => trim($_POST['thoiGianChoChay'] ?? '0'),
            'kichHoat' => isset($_POST['kichHoat']) ? 1 : 0,
            'phongIds' => $_POST['phongIds'] ?? ($_POST['idPhong'] ?? []),
        ];
    }
}

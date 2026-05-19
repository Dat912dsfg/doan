<?php
namespace App\Controllers;

use App\Services\ThietBiService;

class ThietBiController {
    private $thietBiService;

    public function __construct() {
        $this->thietBiService = new ThietBiService();
    }

    public function layDanhSachThietBi() {
        return $this->thietBiService->layDanhSachThietBi();
    }

    public function layDanhSachPhong() {
        return $this->thietBiService->layDanhSachPhong();
    }

    public function layDanhSachPhongChoForm() {
        return $this->thietBiService->layDanhSachPhongChoForm();
    }

    public function layThietBiTheoId($id) {
        return $this->thietBiService->layThietBiTheoId($id);
    }

    public function layPhongTheoId($id) {
        return $this->thietBiService->layPhongTheoId($id);
    }

    public function webThemThietBi() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?page=thietbi');
            exit;
        }

        $result = $this->thietBiService->themThietBi($this->layDuLieuThietBiTuForm());
        $_SESSION['msg'] = !empty($result['success']) ? 'add_success' : 'add_error';
        header('Location: index.php?page=thietbi');
        exit;
    }

    public function webSuaThietBi() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?page=thietbi');
            exit;
        }

        $id = intval($_POST['idThietBi'] ?? 0);
        if ($id <= 0) {
            $_SESSION['msg'] = 'edit_error';
            header('Location: index.php?page=thietbi');
            exit;
        }

        $result = $this->thietBiService->suaThietBi($id, $this->layDuLieuThietBiTuForm());
        $_SESSION['msg'] = !empty($result['success']) ? 'edit_success' : 'edit_error';
        header('Location: index.php?page=thietbi');
        exit;
    }

    public function webXoaThietBi() {
        $id = intval($_GET['id'] ?? 0);
        if ($id <= 0) {
            $_SESSION['msg'] = 'del_error';
            header('Location: index.php?page=thietbi');
            exit;
        }

        $result = $this->thietBiService->xoaThietBi($id);
        $_SESSION['msg'] = !empty($result['success']) ? 'del_success' : 'del_error';
        header('Location: index.php?page=thietbi');
        exit;
    }

    public function webThemPhong() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?page=thietbi');
            exit;
        }

        $result = $this->thietBiService->themPhong($this->layDuLieuPhongTuForm());
        $_SESSION['msg'] = !empty($result['success']) ? 'add_success' : 'add_error';
        header('Location: index.php?page=thietbi');
        exit;
    }

    public function webSuaPhong() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?page=thietbi');
            exit;
        }

        $id = intval($_POST['idPhong'] ?? 0);
        if ($id <= 0) {
            $_SESSION['msg'] = 'edit_error';
            header('Location: index.php?page=thietbi');
            exit;
        }

        $result = $this->thietBiService->suaPhong($id, $this->layDuLieuPhongTuForm());
        $_SESSION['msg'] = !empty($result['success']) ? 'edit_success' : 'edit_error';
        header('Location: index.php?page=thietbi');
        exit;
    }

    public function webXoaPhong() {
        $id = intval($_GET['id'] ?? 0);
        if ($id <= 0) {
            $_SESSION['msg'] = 'del_error';
            header('Location: index.php?page=thietbi');
            exit;
        }

        $result = $this->thietBiService->xoaPhong($id);
        $_SESSION['msg'] = !empty($result['success']) ? 'del_success' : 'del_error';
        header('Location: index.php?page=thietbi');
        exit;
    }

    public function webToggleTrangThaiPhong() {
        $id = intval($_GET['id'] ?? $_POST['id'] ?? 0);
        if ($id <= 0) {
            $_SESSION['msg'] = 'toggle_error';
            header('Location: index.php?page=thietbi');
            exit;
        }

        $result = $this->thietBiService->toggleTrangThaiPhong($id);
        $_SESSION['msg'] = !empty($result['success']) ? 'toggle_success' : 'toggle_error';
        header('Location: index.php?page=thietbi');
        exit;
    }

    public function webToggleCheDoPhong() {
        $id = intval($_GET['id'] ?? $_POST['id'] ?? 0);
        if ($id <= 0) {
            $_SESSION['msg'] = 'toggle_error';
            header('Location: index.php?page=thietbi');
            exit;
        }

        $result = $this->thietBiService->toggleCheDoPhong($id);
        $_SESSION['msg'] = !empty($result['success']) ? 'toggle_success' : 'toggle_error';
        header('Location: index.php?page=thietbi');
        exit;
    }

    private function layDuLieuThietBiTuForm() {
        return [
            'idPhong' => intval($_POST['idPhong'] ?? 0),
            'tenThietBi' => trim($_POST['tenThietBi'] ?? ''),
            'loaiThietBi' => trim($_POST['loaiThietBi'] ?? ''),
            'diaChiIp' => trim($_POST['diaChiIp'] ?? ''),
            'macAddress' => trim($_POST['macAddress'] ?? ''),
            'topicMqtt' => trim($_POST['topicMqtt'] ?? ''),
            'trangThaiKetNoi' => intval($_POST['trangThaiKetNoi'] ?? 0),
            'lastHeartbeat' => trim($_POST['lastHeartbeat'] ?? ''),
            'firmwareVersion' => trim($_POST['firmwareVersion'] ?? ''),
        ];
    }

    private function layDuLieuPhongTuForm() {
        return [
            'tenPhong' => trim($_POST['tenPhong'] ?? ''),
            'loaiPhong' => trim($_POST['loaiPhong'] ?? ''),
            'viTri' => trim($_POST['viTri'] ?? ''),
            'moTa' => trim($_POST['moTa'] ?? ''),
            'dienTich' => trim($_POST['dienTich'] ?? ''),
            'trangThai' => intval($_POST['trangThai'] ?? 1),
            'cheDoTuDong' => intval($_POST['cheDoTuDong'] ?? 0),
        ];
    }
}

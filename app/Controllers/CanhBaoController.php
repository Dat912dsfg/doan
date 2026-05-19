<?php
namespace App\Controllers;

use App\Services\CanhBaoService;

class CanhBaoController {
    private $canhBaoService;

    public function __construct() {
        $this->canhBaoService = new CanhBaoService();
    }

    public function layDanhSachCanhBao($idPhong = null, $limit = 200) {
        return $this->canhBaoService->layDanhSachCanhBao($idPhong, $limit);
    }

    public function webXacNhanCanhBao() {
        $idCanhBao = intval($_GET['id'] ?? 0);
        if ($idCanhBao <= 0) {
            $_SESSION['msg'] = 'edit_error';
            header('Location: index.php?page=alert_log');
            exit;
        }

        $idNguoiDung = intval($_SESSION['user_id'] ?? 0);
        $result = $this->canhBaoService->xacNhanCanhBao($idCanhBao, $idNguoiDung);
        $_SESSION['msg'] = !empty($result['success']) ? 'edit_success' : 'edit_error';
        header('Location: index.php?page=alert_log');
        exit;
    }

    public function webGiaiQuyetCanhBao() {
        $idCanhBao = intval($_GET['id'] ?? 0);
        if ($idCanhBao <= 0) {
            $_SESSION['msg'] = 'edit_error';
            header('Location: index.php?page=alert_log');
            exit;
        }

        $result = $this->canhBaoService->giaiQuyetCanhBao($idCanhBao);
        $_SESSION['msg'] = !empty($result['success']) ? 'edit_success' : 'edit_error';
        header('Location: index.php?page=alert_log');
        exit;
    }

    public function webXoaCanhBao() {
        $idCanhBao = intval($_GET['id'] ?? 0);
        if ($idCanhBao <= 0) {
            $_SESSION['msg'] = 'del_error';
            header('Location: index.php?page=alert_log');
            exit;
        }

        $result = $this->canhBaoService->xoaCanhBao($idCanhBao);
        $_SESSION['msg'] = !empty($result['success']) ? 'del_success' : 'del_error';
        header('Location: index.php?page=alert_log');
        exit;
    }

    public function webXoaTatCaCanhBao() {
        $result = $this->canhBaoService->xoaTatCaCanhBao();
        $_SESSION['msg'] = !empty($result['success']) ? 'del_success' : 'del_error';
        header('Location: index.php?page=alert_log');
        exit;
    }
}

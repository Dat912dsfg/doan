<?php
namespace App\Services;

use App\Repositories\CanhBaoRepository;

class CanhBaoService {
    private $canhBaoRepo;

    public function __construct() {
        $this->canhBaoRepo = new CanhBaoRepository();
    }

    public function layDanhSachCanhBao($idPhong = null, $limit = 200) {
        return $this->canhBaoRepo->layLichSuCanhBao($idPhong, $limit);
    }

    public function layThongKe($idPhong = null) {
        return $this->canhBaoRepo->layThongKeAlerts($idPhong);
    }

    public function xacNhanCanhBao($idCanhBao, $idNguoiDung, $ghiChu = '') {
        if (!$this->canhBaoRepo->layCanhBaoTheoId($idCanhBao)) {
            return ['success' => false, 'message' => 'Cảnh báo không tồn tại.'];
        }

        return $this->canhBaoRepo->xacNhanCanhBao($idCanhBao, $idNguoiDung, $ghiChu)
            ? ['success' => true]
            : ['success' => false, 'message' => 'Không thể xác nhận cảnh báo.'];
    }

    public function giaiQuyetCanhBao($idCanhBao, $ghiChu = '') {
        if (!$this->canhBaoRepo->layCanhBaoTheoId($idCanhBao)) {
            return ['success' => false, 'message' => 'Cảnh báo không tồn tại.'];
        }

        return $this->canhBaoRepo->giaiQuyetCanhBao($idCanhBao, $ghiChu)
            ? ['success' => true]
            : ['success' => false, 'message' => 'Không thể xử lý cảnh báo.'];
    }

    public function xoaCanhBao($idCanhBao) {
        if (!$this->canhBaoRepo->layCanhBaoTheoId($idCanhBao)) {
            return ['success' => false, 'message' => 'Cảnh báo không tồn tại.'];
        }

        return $this->canhBaoRepo->xoaCanhBao($idCanhBao)
            ? ['success' => true]
            : ['success' => false, 'message' => 'Không thể xóa cảnh báo.'];
    }

    public function xoaTatCaCanhBao() {
        return $this->canhBaoRepo->xoaTatCaCanhBao()
            ? ['success' => true]
            : ['success' => false, 'message' => 'Không thể xóa toàn bộ cảnh báo.'];
    }
}

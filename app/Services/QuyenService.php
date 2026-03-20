<?php
namespace App\Services;
use App\Repositories\QuyenRepository;

class QuyenService{
    private $quyenRepo;

    public function __construct()
    {
        $this->quyenRepo = new QuyenRepository();
    }

    public function hienthiDSQuyen(){
        $quyen = $this->quyenRepo->layTatCaQuyen();
        return $quyen;
    }

    public function layQuyenDaGan($idNhom) {
        if (!$idNhom) return [];
        return $this->quyenRepo->layQuyenCuaNhom($idNhom);
    }

    public function getQuyenById($id){
        $quyen = $this->quyenRepo->layQuyenTheoId($id);
        return $quyen;
    }

    public function themQuyen($data) {        
        return $this->quyenRepo->insertQuyen($data);
    }

    public function suaQuyen($id, $data) {
        return $this->quyenRepo->updateQuyen($id, $data);
    }

    public function xoaQuyen($id) {
        return $this->quyenRepo->deleteQuyen($id);
    }
}
?>
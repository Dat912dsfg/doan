<?php
namespace App\Services;
use App\Repositories\NhomRepository;

class NhomService{
    private $nhomRepo;
    public function __construct()
    {
        $this->nhomRepo = new NhomRepository();
    } 
    public function hienThiDSNhom(){
        $nhom = $this->nhomRepo->layTatCaNhom();
        return $nhom;
    }

    public function getRoleById($id) {
        $nhom = $this->nhomRepo->timNhomTheoId($id);
        return $nhom;
    }

    public function capNhatNhomVaQuyen($id, $data, $permissionIds) {
        try {
            $this->nhomRepo->updateNhom($id, $data);
            
            $this->nhomRepo->clearQuyen($id);
            
            if (!empty($permissionIds)) {
                $this->nhomRepo->insertQuyen($id, $permissionIds);
            }
            
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    public function themNhom($data) {        
        return $this->nhomRepo->insertNhom($data);
    }

    public function xoaNhom($id) {
        return $this->nhomRepo->deleteNhom($id);
    }
}
?>
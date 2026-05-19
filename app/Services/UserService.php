<?php
namespace App\Services;
use App\Repositories\UserRepository;

class UserService{
    private $userRepo;
    public function __construct(){
        $this->userRepo = new UserRepository();
    }
    public function hienThiUser() {
        $users = $this->userRepo->layTatCaNguoiDung();

        foreach ($users as $user) {
            if (isset($user->idNhom)) {
                $user->permissions = $this->userRepo->getPermissions($user->idNhom);
            } else {
                $user->permissions = [];
            }
        }

        return $users;
    }

    public function getUserById($id) {
        $user = $this->userRepo->timUserTheoId($id);

        if ($user && isset($user->idNhom)) {
            $user->permissions = $this->userRepo->getPermissions($user->idNhom);
        }

        return $user;
    }

    public function getUserByIdRole($id) {
        return $this->userRepo->timUserTheoNhom($id);
    }

    public function getUserCoSan($idNhom) {
        return $this->userRepo->layNguoiDungNgoaiNhom($idNhom);
    }

    public function themUser($data) {
        if (empty($data['matKhau'])) {
            $data['matKhau'] = '12345678';
        }

        $data['matKhau'] = password_hash($data['matKhau'], PASSWORD_DEFAULT);
        
        return $this->userRepo->insertNguoiDung($data);
    }

    public function suaUser($id, $data) {
        return $this->userRepo->updateNguoiDung($id, $data);
    }

    public function xoaUser($id) {
        return $this->userRepo->deleteNguoiDung($id);
    }

    public function thucHienResetPass($id){
        $matKhau = password_hash('12345678', PASSWORD_DEFAULT);
        return $this->userRepo->resetMatKhau($id, $matKhau);
    }

    public function chuyenNhomMoi($idUser, $idNhomMoi) {
        if (!$idUser || !$idNhomMoi) return false;
        return $this->userRepo->updateIdNhom($idUser, $idNhomMoi);
    }
}
?>
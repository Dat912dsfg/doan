<?php
namespace  App\Controllers;
use App\Services\UserService;
use App\Services\NhomService;
use App\Services\QuyenService;

class NguoiDungController{
    private $userService;
    private $nhomService;
    private $quyenService;
    public function __construct()
    {
        $this-> userService = new UserService();
        $this-> nhomService = new NhomService();
        $this-> quyenService = new QuyenService();
    }

    public function layDuLieuNguoiDung() {
        return $this->userService->hienThiUser();
    }
    public function layDuLieuNguoiDungBangId($id) {
        return $this->userService->getUserById($id);
    }

    public function layDuLieuNhom(){
        return $this->nhomService->hienThiDSNhom();
    }

    public function layDuLieuQuyen() {
        return $this->quyenService->hienthiDSQuyen();
    }

    public function layThongTinSua($id) {
        $user = $this->userService->getUserById($id);

        $danhSachNhom = $this->nhomService->hienThiDSNhom();

        return [
            'user' => $user,
            'danhSachNhom' => $danhSachNhom
        ];
    }


    public function layThongTinSuaNhom($id) {
        return $this->nhomService->getRoleById($id);
    }

    public function layThongTinSuaQuyen($id) {
        return $this->quyenService->getQuyenById($id);
    }

    public function layDSThanhVienNhom($id) {
        return $this->userService->getUserByIdRole($id);
    }

    public function layNguoiDungKhaDung($idNhom) {
        return $this->userService->getUserCoSan($idNhom);
    }

    public function htQuyenCuaNhom($id){
        return $quyenNhom = $this->quyenService->layQuyenDaGan($id);
    }

    public function webThemNguoiDung() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'maNguoiDung' => $_POST['maNguoiDung'] ?? null,
                'tenDangNhap' => $_POST['tenDangNhap'] ?? null,
                'hoTen'       => $_POST['hoTen'] ?? null,
                'idNhom'      => $_POST['idNhom'] ?? null,
                'matKhau'     => $_POST['matKhau'] ?? '12345678'
            ];

            $kq = $this->userService->themUser($data);

            $_SESSION['msg'] = $kq ? 'add_success' : 'add_error';
            header('Location: index.php?page=users');
            exit;
        }
    }

    public function webSuaNguoiDung() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['idNguoiDung'] ?? null;
            $data = [
                'tenDangNhap' => $_POST['tenDangNhap'] ?? null,
                'hoTen'       => $_POST['hoTen'] ?? null,
                'idNhom'      => $_POST['idNhom'] ?? null
            ];

            $kq = $this->userService->suaUser($id, $data);

            $_SESSION['msg'] = ($kq !== false) ? 'edit_success' : 'edit_error';
            
            if ($kq !== false) {
                header('Location: index.php?page=users');
            } else {
                header('Location: index.php?page=nguoidung_sua&id=' . $id);
            }
            exit;
        }
    }

    public function webXoaNguoiDung() {
        $id = $_GET['id'] ?? null;
        if ($id) {
            $kq = $this->userService->xoaUser($id);
            $_SESSION['msg'] = $kq ? 'del_success' : 'del_error';
            header('Location: index.php?page=users');
            exit;
        }
    }

    public function webResetPass(){
        $id = $_GET['id'] ?? null;
        if($id){
            $kq = $this->userService->thucHienResetPass($id);
            $_SESSION['msg'] = $kq ? 'res_thanhcong' : 'res_thatbai';
            header('Location: index.php?page=users');
            exit;
        }
    }

    public function webChuyenNhom() {
        $idNguoiDung = $_GET['idNguoiDung'] ?? null;
        $idNhomMoi = $_GET['idNhom'] ?? null;
    
        if ($idNguoiDung && $idNhomMoi) {
            $kq = $this->userService->chuyenNhomMoi($idNguoiDung, $idNhomMoi);
            
            $_SESSION['msg'] = $kq ? 'edit_success' : 'edit_error';
            
            $idNhomHienTai = $_GET['idNhomCu'] ?? $idNhomMoi; 
            header("Location: index.php?page=nhom_sua&id=" . $idNhomHienTai);
        } else {
            $_SESSION['msg'] = 'dulieu_khonghople';
            header('Location: index.php?page=users');
        }
        exit;
    }

    public function webSuaNhom() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['idNhom'] ?? null;
            $data = [
                'tenNhom' => $_POST['tenNhom'] ?? null,
                'moTa' => $_POST['moTa'] ?? null,
            ];
            
            $permissionIds = $_POST['permissions'] ?? [];
            
            $newMemberIds = $_POST['new_members'] ?? [];

            $kqGroup = $this->nhomService->capNhatNhomVaQuyen($id, $data, $permissionIds);

            $kqMembers = true;
            if (!empty($newMemberIds)) {
                foreach ($newMemberIds as $userId) {
                    if (!$this->userService->chuyenNhomMoi($userId, $id)) {
                        $kqMembers = false;
                    }
                }
            }

            $_SESSION['msg'] = ($kqGroup && $kqMembers) ? 'edit_success' : 'edit_error';
            
            header("Location: index.php?page=nhom_sua&id=" . $id);
            exit;
        }
    }

    public function webXoaNhom() {
        $id = $_GET['id'] ?? null;
        if ($id) {
            $kq = $this->nhomService->xoaNhom($id);
            $_SESSION['msg'] = $kq ? 'del_success' : 'del_error';
            header('Location: index.php?page=users&tab=groups');
            exit;
        }
    }

    public function webThemNhom() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'maNhom' => $_POST['maNhom'] ?? null,
                'tenNhom' => $_POST['tenNhom'] ?? null,
                'moTa'       => $_POST['moTa'] ?? null,
            ];

            $kq = $this->nhomService->themNhom($data);

            $_SESSION['msg'] = $kq ? 'add_success' : 'add_error';
            header('Location: index.php?page=users&tab=groups');
            exit;
        }
    }

    public function webThemQuyen() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'maQuyen' => $_POST['maQuyen'] ?? null,
                'tenQuyen' => $_POST['tenQuyen'] ?? null
            ];

            $kq = $this->quyenService->themQuyen($data);

            $_SESSION['msg'] = $kq ? 'add_success' : 'add_error';
            header('Location: index.php?page=users&tab=permissions');
            exit;
        }
    }

    public function webSuaQuyen() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['idQuyen'] ?? null;
            $data = [
                'tenQuyen' => $_POST['tenQuyen'] ?? null
            ];

            $kq = $this->quyenService->suaQuyen($id, $data);

            $_SESSION['msg'] = ($kq !== false) ? 'edit_success' : 'edit_error';
            
            if ($kq !== false) {
                header('Location: index.php?page=users');
            } else {
                header('Location: index.php?page=quyen_sua&id=' . $id);
            }
            exit;
        }
    }

    public function webXoaQuyen() {
        $id = $_GET['id'] ?? null;
        if ($id) {
            $kq = $this->quyenService->xoaQuyen($id);
            $_SESSION['msg'] = $kq ? 'del_success' : 'del_error';
            header('Location: index.php?page=users&tab=permissions');
            exit;
        }
    }
}
?>

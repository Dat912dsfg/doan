<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
header('Content-Type: text/html; charset=UTF-8');

// thong bao ket qua
session_start();

$msg = $_SESSION['msg'] ?? $_GET['msg'] ?? null;
$errorDetail = $_SESSION['error_detail'] ?? null;
$alert = null;

if ($msg) {
    unset($_SESSION['msg']); 
    unset($_SESSION['error_detail']);
    
    switch ($msg) {
        case 'add_success':
            $alert = ['type' => 'success', 'title' => 'Thành công!', 'text' => 'Thêm dữ liệu thành công.'];
            break;
        case 'add_error':
            $alert = ['type' => 'error', 'title' => 'Thất bại', 'text' => $errorDetail ?: 'Không thể thêm. Vui lòng kiểm tra lại dữ liệu.'];
            break;
        case 'edit_success':
            $alert = ['type' => 'success', 'title' => 'Đã cập nhật', 'text' => 'Thông tin đã được thay đổi thành công.'];
            break;
        case 'edit_error':
            $alert = ['type' => 'error', 'title' => 'Lỗi cập nhật', 'text' => $errorDetail ?: 'Có lỗi xảy ra trong quá trình lưu dữ liệu.'];
            break;
        case 'del_success':
            $alert = ['type' => 'success', 'title' => 'Đã xóa', 'text' => 'Dữ liệu đã được xóa thành công.'];
            break;
        
        case 'del_error':
            $alert = ['type' => 'error', 'title' => 'Không thể xóa', 'text' => $errorDetail ?: 'Có thể đang liên kết với các dữ liệu khác.'];
            break;
        
        case 'toggle_success':
            $alert = ['type' => 'success', 'title' => 'Đã cập nhật', 'text' => 'Trạng thái kích hoạt đã được thay đổi.'];
            break;
        case 'toggle_error':
            $alert = ['type' => 'error', 'title' => 'Lỗi', 'text' => $errorDetail ?: 'Không thể đổi trạng thái kích hoạt.'];
            break;
        case 'res_thanhcong':
            $alert = ['type' => 'success', 'title' => 'Khôi phục thành công', 'text' => 'Mật khẩu đã được đưa về mặc định (12345678).'];
            break;
        case 'res_thatbai':
            $alert = ['type' => 'error', 'title' => 'Khôi phục thất bại', 'text' => 'Lỗi khi thực hiện khôi phục mật khẩu.'];
            break;
    }
}

require_once __DIR__ . '/../vendor/autoload.php';

$page = $_GET['page'] ?? 'dashboard';

// 1. DANH SACH TRANG CONG KHAI
$publicPages = ['auth', 'auth_xuly_dangnhap', 'google_login', '404', '403'];

if (!isset($_SESSION['user_id']) && !in_array($page, $publicPages)) {
    header('Location: index.php?page=auth');
    exit;
}

// 2. CHUAN HOA DUONG DAN
$viewDir = realpath(__DIR__ . '/../views');
$layout = isset($_SESSION['user_id']) ? 'main' : 'auth';
$title = "Hệ thống quản lý nhà trọ IoT";

function hasPermission($permissionCode) {
    if (!isset($_SESSION['permissions']) || !is_array($_SESSION['permissions'])) {
        return false;
    }
    return in_array($permissionCode, $_SESSION['permissions']);
}

// 3. DIEU HUONG VA KIEM TRA QUYEN
switch ($page) {
    case 'auth':
        $layout = 'auth';
        $viewFile = $viewDir . '/auth/login.php';
        break;

    case 'auth_xuly_dangnhap':
        $controller = new \App\Controllers\AuthController();
        $controller->webLogin();
        exit;
    
    case 'google_login':
        $app = new \App\Controllers\AuthController();
        $app->googleLogin();
        break;

    case 'logout':
        $controller = new \App\Controllers\AuthController();
        $controller->logout();
        exit;

    case 'profile':
        $id = $_SESSION['user_id'];
        $controller = new \App\Controllers\NguoiDungController();
        $user = $controller->layDuLieuNguoiDungBangId($id);
        $viewFile = $viewDir . '/profile/index.php';
        break;

    case 'dashboard':
        if (hasPermission('trangchu.view')) {
            $thietBiController = new \App\Controllers\ThietBiController();
            $danhSachThietBi = $thietBiController->layDanhSachThietBi();
            $danhSachKhuVuc = $thietBiController->layDanhSachPhong();
            $viewFile = $viewDir . '/trangchu/index.php';
        } else {
            $page = '403';
        }
        break;

    // Cac tab thiet bi
    case 'thietbi':
        if (hasPermission('thietbi.view')) {
            $thietBiController = new \App\Controllers\ThietBiController();
            $danhSachThietBi = $thietBiController->layDanhSachThietBi();
            $danhSachKhuVuc = $thietBiController->layDanhSachPhong();
            $viewFile = $viewDir . '/thietbi/index.php';
        } else {
            $page = '403';
        }
        break;
    case 'thietbi_them':
        if (hasPermission('thietbi.view')) {
            $thietBiController = new \App\Controllers\ThietBiController();
            $danhSachPhong = $thietBiController->layDanhSachPhongChoForm();
            $viewFile = $viewDir . '/thietbi/them.php';
        } else {
            $page = '403';
        }
        break;
    case 'thietbi_sua':
        if (hasPermission('thietbi.view')) {
            $id = $_GET['id'] ?? null;
            if ($id) {
                $thietBiController = new \App\Controllers\ThietBiController();
                $thietBi = $thietBiController->layThietBiTheoId($id);
                $danhSachPhong = $thietBiController->layDanhSachPhongChoForm();
                if ($thietBi) {
                    $viewFile = $viewDir . '/thietbi/sua.php';
                } else {
                    $page = '404';
                }
            } else {
                $page = '404';
            }
        } else {
            $page = '403';
        }
        break;
    case 'thietbi_store':
        if (hasPermission('thietbi.view')) {
            $thietBiController = new \App\Controllers\ThietBiController();
            $thietBiController->webThemThietBi();
            exit;
        } else {
            $page = '403';
        }
        break;
    case 'thietbi_update':
        if (hasPermission('thietbi.view')) {
            $thietBiController = new \App\Controllers\ThietBiController();
            $thietBiController->webSuaThietBi();
            exit;
        } else {
            $page = '403';
        }
        break;
    case 'thietbi_xuly_xoa':
        if (hasPermission('thietbi.view')) {
            $thietBiController = new \App\Controllers\ThietBiController();
            $thietBiController->webXoaThietBi();
            exit;
        } else {
            $page = '403';
        }
        break;
    case 'khuvuc_them':
        if (hasPermission('thietbi.view')) {
            $viewFile = $viewDir . '/thietbi/them_phong.php';
        } else {
            $page = '403';
        }
        break;
    case 'khuvuc_sua':
        if (hasPermission('thietbi.view')) {
            $id = $_GET['id'] ?? null;
            if ($id) {
                $thietBiController = new \App\Controllers\ThietBiController();
                $phong = $thietBiController->layPhongTheoId($id);
                if ($phong) {
                    $viewFile = $viewDir . '/thietbi/sua_phong.php';
                } else {
                    $page = '404';
                }
            } else {
                $page = '404';
            }
        } else {
            $page = '403';
        }
        break;
    case 'khuvuc_store':
        if (hasPermission('thietbi.view')) {
            $thietBiController = new \App\Controllers\ThietBiController();
            $thietBiController->webThemPhong();
            exit;
        } else {
            $page = '403';
        }
        break;
    case 'khuvuc_update':
        if (hasPermission('thietbi.view')) {
            $thietBiController = new \App\Controllers\ThietBiController();
            $thietBiController->webSuaPhong();
            exit;
        } else {
            $page = '403';
        }
        break;
    case 'khuvuc_xuly_xoa':
        if (hasPermission('thietbi.view')) {
            $thietBiController = new \App\Controllers\ThietBiController();
            $thietBiController->webXoaPhong();
            exit;
        } else {
            $page = '403';
        }
        break;
    case 'khuvuc_toggle_trangthai':
        if (hasPermission('thietbi.view')) {
            $thietBiController = new \App\Controllers\ThietBiController();
            $thietBiController->webToggleTrangThaiPhong();
            exit;
        } else {
            $page = '403';
        }
        break;
    case 'khuvuc_toggle_chedo':
        if (hasPermission('thietbi.view')) {
            $thietBiController = new \App\Controllers\ThietBiController();
            $thietBiController->webToggleCheDoPhong();
            exit;
        } else {
            $page = '403';
        }
        break;

    // Phan tich va tu dong hoa
    case 'phantich':
        if (hasPermission('phantich.view')) {
            $viewFile = $viewDir . '/phantich/index.php';
        } else {
            $page = '403';
        }
        break;

    case 'tudong':
        if (hasPermission('tudong.view')) {
            $tuDongController = new \App\Controllers\TuDongController();
            $danhSachKichBan = $tuDongController->layDanhSachKichBan();
            $danhSachPhong = $tuDongController->layDanhSachPhongApDung();
            $danhSachCauHinh = $tuDongController->layTatCaCauHinh();
            $viewFile = $viewDir . '/tudong/index.php';
        } else {
            $page = '403';
        }
        break;
    case 'tudong_them':
        if (hasPermission('tudong.view')) {
            $tuDongController = new \App\Controllers\TuDongController();
            $danhSachPhong = $tuDongController->layDanhSachPhongApDung();
            $viewFile = $viewDir . '/tudong/them.php';
        } else {
            $page = '403';
        }
        break;
    case 'tudong_sua':
        if (hasPermission('tudong.view')) {
            $id = $_GET['id'] ?? null;
            if ($id) {
                $tuDongController = new \App\Controllers\TuDongController();
                $kichBan = $tuDongController->layKichBanTheoId($id);
                $danhSachPhong = $tuDongController->layDanhSachPhongApDung();
                if ($kichBan) {
                    $viewFile = $viewDir . '/tudong/sua.php';
                } else {
                    $page = '404';
                }
            } else {
                $page = '404';
            }
        } else {
            $page = '403';
        }
        break;
    case 'tudong_store':
    case 'tudong-store':
    case 'tudong_xuly_them':
        if (hasPermission('tudong.view')) {
            $tuDongController = new \App\Controllers\TuDongController();
            $tuDongController->webThemKichBan();
            exit;
        } else {
            $page = '403';
        }
        break;
    case 'tudong_update':
    case 'tudong-update':
    case 'tudong_xuly_sua':
        if (hasPermission('tudong.view')) {
            $tuDongController = new \App\Controllers\TuDongController();
            $tuDongController->webSuaKichBan();
            exit;
        } else {
            $page = '403';
        }
        break;
    case 'tudong_delete':
    case 'tudong-delete':
    case 'tudong_xuly_xoa':
        if (hasPermission('tudong.view')) {
            $tuDongController = new \App\Controllers\TuDongController();
            $tuDongController->webXoaKichBan();
            exit;
        } else {
            $page = '403';
        }
        break;
    case 'tudong_toggle':
    case 'tudong-toggle':
    case 'tudong_xuly_trangthai':
        if (hasPermission('tudong.view')) {
            $tuDongController = new \App\Controllers\TuDongController();
            $tuDongController->webToggleKichHoat();
            exit;
        } else {
            $page = '403';
        }
        break;
    case 'tudong_config_save':
    case 'tudong_xuly_caidat':
        if (hasPermission('tudong.view')) {
            $tuDongController = new \App\Controllers\TuDongController();
            $tuDongController->webLuuCauHinh();
            exit;
        } else {
            $page = '403';
        }
        break;


    case 'alert_log':
        if (hasPermission('canhbao.view')) {
            $canhBaoController = new \App\Controllers\CanhBaoController();
            $danhSachCanhBao = $canhBaoController->layDanhSachCanhBao();
            $viewFile = $viewDir . '/alert_log/index.php';
        } else {
            $page = '403';
        }
        break;
    case 'canhbao_xacnhan':
        if (hasPermission('canhbao.view')) {
            $canhBaoController = new \App\Controllers\CanhBaoController();
            $canhBaoController->webXacNhanCanhBao();
            exit;
        } else {
            $page = '403';
        }
        break;
    case 'canhbao_giaiquyet':
        if (hasPermission('canhbao.view')) {
            $canhBaoController = new \App\Controllers\CanhBaoController();
            $canhBaoController->webGiaiQuyetCanhBao();
            exit;
        } else {
            $page = '403';
        }
        break;
    case 'canhbao_xoa':
        if (hasPermission('canhbao.view')) {
            $canhBaoController = new \App\Controllers\CanhBaoController();
            $canhBaoController->webXoaCanhBao();
            exit;
        } else {
            $page = '403';
        }
        break;
    case 'canhbao_xoa_tatca':
        if (hasPermission('canhbao.view')) {
            $canhBaoController = new \App\Controllers\CanhBaoController();
            $canhBaoController->webXoaTatCaCanhBao();
            exit;
        } else {
            $page = '403';
        }
        break;

    // Quan ly nguoi dung
    case 'users':
        if (hasPermission('nguoidung.view')) {
            $userController = new \App\Controllers\NguoiDungController();
            
            $danhSachNguoiDung = $userController->layDuLieuNguoiDung();
            $danhSachNhom = $userController->layDuLieuNhom();
            $danhSachQuyen = $userController->layDuLieuQuyen();
            
            $viewFile = $viewDir . '/users/index.php';
        } else {
            $page = '403';
        }
        break;
    case 'nguoidung_them':
        if (hasPermission('nguoidung.view')) {
            $userController = new \App\Controllers\NguoiDungController();
            $danhSachNhom = $userController->layDuLieuNhom();
            $viewFile = $viewDir . '/users/them_user.php';
        } else {
            $page = '403';
        }
        break;
    case 'nguoidung_sua':
        $id = $_GET['id'] ?? null;
        if ($id) {
            $userController = new \App\Controllers\NguoiDungController();
            $data = $userController->layThongTinSua($id);
            
            $user = $data['user'];
            $danhSachNhom = $data['danhSachNhom'];

            if ($user) {
                $viewFile = $viewDir . '/users/sua_user.php';
            } else {
                $page = '404';
            }
        }
        break;
        
    case 'users_xuly_them':
        $userController = new \App\Controllers\NguoiDungController();
        $userController->webThemNguoiDung();
        break;

    case 'users_xuly_sua':
        $userController = new \App\Controllers\NguoiDungController();
        $userController->webSuaNguoiDung();
        break;

    case 'users_xuly_xoa':
        $userController = new \App\Controllers\NguoiDungController();
        $userController->webXoaNguoiDung();
        break;
    case 'users_xuly_reset':
        $userController = new \App\Controllers\NguoiDungController();
        $userController->webResetPass();
        break;
    case 'nhom_them':
        if (hasPermission('nguoidung.view')) {
            $controller = new \App\Controllers\NguoiDungController();
            $quyen = $controller->layDuLieuQuyen();
            $viewFile = $viewDir . '/users/them_nhom.php';
        } else {
            $page = '403';
        }
        break;
    case 'nhom_sua':
        if (hasPermission('nguoidung.view')) {
            $id = $_GET['id'] ?? null;
            $controller = new \App\Controllers\NguoiDungController();
            $user = $controller->layNguoiDungKhaDung($id);
            $tv = $controller->layDSThanhVienNhom($id);
            $nhom = $controller->layThongTinSuaNhom($id);
            $nhomKhac = $controller->layDuLieuNhom();
            $quyen = $controller->layDuLieuQuyen();
            $quyenNhom = $controller->htQuyenCuaNhom($id);
            $viewFile = $viewDir . '/users/sua_nhom.php';
        } else {
            $page = '403';
        }
        break;
    case 'nhom_xuly_sua':
        if (hasPermission('nguoidung.view')) {
            $controller = new \App\Controllers\NguoiDungController();
            $controller->webSuaNhom();
        } else {
            header('Location: index.php?page=403');
            exit;
        }
        break;

    case 'nhom_xuly_them':
        if (hasPermission('nguoidung.view')) {
            $controller = new \App\Controllers\NguoiDungController();
            $controller->webThemNhom();
        } else {
            header('Location: index.php?page=403');
            exit;
        }
        break;
    
    case 'nhom_xuly_xoa':
        if (hasPermission('nguoidung.view')) {
            $controller = new \App\Controllers\NguoiDungController();
            $controller->webXoaNhom();
        } else {
            header('Location: index.php?page=403');
            exit;
        }
        break;

    case 'nhom_chuyen_thanhvien':
        if (hasPermission('nguoidung.view')) {
            $controller = new \App\Controllers\NguoiDungController();
            $controller->webChuyenNhom();
        } else {
            header('Location: index.php?page=403');
            exit;
        }
        break;

    case 'quyen_them':
        if (hasPermission('nguoidung.view')) {
            $viewFile = $viewDir . '/users/them_quyen.php';
        } else {
            $page = '403';
        }
        break;
    case 'xuly_quyen_them':
        if (hasPermission('nguoidung.view')) {
            $controller = new \App\Controllers\NguoiDungController();
            $quyen = $controller->webThemQuyen();
        } else {
            $page = '403';
        }
        break;

    case 'quyen_sua':
        if (hasPermission('nguoidung.view')) {
            $id = $_GET['id'] ?? null;
            $controller = new \App\Controllers\NguoiDungController();
            $quyen = $controller->layThongTinSuaQuyen($id);
            $viewFile = $viewDir . '/users/sua_quyen.php';
        } else {
            $page = '403';
        }
        break;
    case 'xuly_quyen_sua':
        if (hasPermission('nguoidung.view')) {
            $controller = new \App\Controllers\NguoiDungController();
            $quyen = $controller->webSuaQuyen();
        } else {
            $page = '403';
        }
        break;

    case 'xuly_quyen_xoa':
        if (hasPermission('nguoidung.view')) {
            $controller = new \App\Controllers\NguoiDungController();
            $controller->webXoaQuyen();
        } else {
            header('Location: index.php?page=403');
            exit;
        }
        break;

    case '404':
        $viewFile = $viewDir . '/error/404.php';
        break;

    default:
        $viewFile = $viewDir . '/error/404.php';
        break;
}

// Xu ly loi 403 (neu bi gan lai tu cac case tren)
if ($page === '403') {
    $title = "403 - Truy cập bị từ chối";
    $viewFile = $viewDir . '/error/403.php';
}

// 4. KIEM TRA FILE VA NAP NOI DUNG
if ($viewFile && file_exists($viewFile)) {
    ob_start();
    include $viewFile;
    $content = ob_get_clean();
} else {
    $content = "<h2 class='text-red-500'>Lỗi: Nội dung không tồn tại tại $viewFile</h2>";
}

// 5. HIEN THI LAYOUT
$layoutPath = $viewDir . "/layouts/{$layout}.php";
if (file_exists($layoutPath)) {
    include $layoutPath;
} else {
    echo "Lỗi hệ thống: Layout không tồn tại.";
}

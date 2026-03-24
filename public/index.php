<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// thÃ´ng bÃ¡o káº¿t quáº£
session_start();

$msg = $_SESSION['msg'] ?? $_GET['msg'] ?? null;
$alert = null;

if ($msg) {
    unset($_SESSION['msg']); 
    
    switch ($msg) {
        case 'add_success':
            $alert = ['type' => 'success', 'title' => 'ThÃ nh cÃ´ng!', 'text' => 'ThÃªm dá»¯ liá»‡u thÃ nh cÃ´ng.'];
            break;
        case 'add_error':
            $alert = ['type' => 'error', 'title' => 'Tháº¥t báº¡i', 'text' => 'KhÃ´ng thá»ƒ thÃªm. Vui lÃ²ng kiá»ƒm tra láº¡i dá»¯ liá»‡u.'];
            break;
        case 'edit_success':
            $alert = ['type' => 'success', 'title' => 'ÄÃ£ cáº­p nháº­t', 'text' => 'ThÃ´ng tin Ä‘Ã£ Ä‘Æ°á»£c thay Ä‘á»•i thÃ nh cÃ´ng.'];
            break;
        case 'edit_error':
            $alert = ['type' => 'error', 'title' => 'Lá»—i cáº­p nháº­t', 'text' => 'CÃ³ lá»—i xáº£y ra trong quÃ¡ trÃ¬nh lÆ°u dá»¯ liá»‡u.'];
            break;
        
        case 'del_error':
            $alert = ['type' => 'error', 'title' => 'KhÃ´ng thá»ƒ xÃ³a', 'text' => 'CÃ³ thá»ƒ Ä‘ang liÃªn káº¿t vá»›i cÃ¡c dá»¯ liá»‡u khÃ¡c.'];
            break;
        
        case 'toggle_success':
            $alert = ['type' => 'success', 'title' => 'Đã cập nhật', 'text' => 'Trạng thái kích hoạt đã được thay đổi.'];
            break;
        case 'toggle_error':
            $alert = ['type' => 'error', 'title' => 'Lỗi', 'text' => 'Không thể đổi trạng thái kích hoạt.'];
            break;
        case 'res_thanhcong':
            $alert = ['type' => 'success', 'title' => 'KhÃ´i phá»¥c thÃ nh cÃ´ng', 'text' => 'Máº­t kháº©u Ä‘Ã£ Ä‘Æ°á»£c Ä‘Æ°a vá» máº·c Ä‘á»‹nh (12345678).'];
            break;
        case 'res_thatbai':
            $alert = ['type' => 'error', 'title' => 'KhÃ´i phá»¥c tháº¥t báº¡i', 'text' => 'Lá»—i khi thá»±c hiá»‡n khÃ´i phá»¥c máº­t kháº©u.'];
            break;
    }
}

require_once __DIR__ . '/../vendor/autoload.php';

$page = $_GET['page'] ?? 'dashboard';

// 1. DANH SÃCH TRANG CÃ”NG KHAI
$publicPages = ['auth', 'auth_xuly_dangnhap', 'google_login', '404', '403'];

if (!isset($_SESSION['user_id']) && !in_array($page, $publicPages)) {
    header('Location: index.php?page=auth');
    exit;
}

// 2. CHUáº¨N HÃ“A ÄÆ¯á»œNG DáºªN
$viewDir = realpath(__DIR__ . '/../views');
$layout = isset($_SESSION['user_id']) ? 'main' : 'auth';
$title = "Há»‡ thá»‘ng quáº£n lÃ½ nhÃ  trá» IoT";

function hasPermission($permissionCode) {
    if (!isset($_SESSION['permissions']) || !is_array($_SESSION['permissions'])) {
        return false;
    }
    return in_array($permissionCode, $_SESSION['permissions']);
}

// 3. ÄIá»€U HÆ¯á»šNG VÃ€ KIá»‚M TRA QUYá»€N
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
            
            $viewFile = $viewDir . '/trangchu/index.php';
        } else {
            $page = '403';
        }
        break;

    // CÃ¡c tab thiáº¿t bá»‹
    case 'thietbi':
        if (hasPermission('thietbi.view')) {
            $viewFile = $viewDir . '/thietbi/index.php';
        } else {
            $page = '403';
        }
        break;

    // PhÃ¢n tÃ­ch vÃ  Tá»± Ä‘á»™ng hÃ³a
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
            $kichBans = $tuDongController->layDanhSachKichBan();
            $viewFile = $viewDir . '/tudong/index.php';
        } else {
            $page = '403';
        }
        break;
    case 'tudong_them':
        if (hasPermission('tudong.view')) {
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
    case 'tudong-store':
        if (hasPermission('tudong.view')) {
            $tuDongController = new \App\Controllers\TuDongController();
            $tuDongController->webThemKichBan();
            exit;
        } else {
            $page = '403';
        }
        break;
    case 'tudong-update':
        if (hasPermission('tudong.view')) {
            $tuDongController = new \App\Controllers\TuDongController();
            $tuDongController->webSuaKichBan();
            exit;
        } else {
            $page = '403';
        }
        break;
    case 'tudong-delete':
        if (hasPermission('tudong.view')) {
            $tuDongController = new \App\Controllers\TuDongController();
            $tuDongController->webXoaKichBan();
            exit;
        } else {
            $page = '403';
        }
        break;
    case 'tudong-toggle':
        if (hasPermission('tudong.view')) {
            $tuDongController = new \App\Controllers\TuDongController();
            $tuDongController->webToggleKichHoat();
            exit;
        } else {
            $page = '403';
        }
        break;


    case 'alert_log':
        if (hasPermission('canhbao.view')) {
            $viewFile = $viewDir . '/alert_log/index.php';
        } else {
            $page = '403';
        }
        break;

    // Quáº£n lÃ½ ngÆ°á»i dÃ¹ng
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

// Xá»¬ LÃ Lá»–I 403 (Náº¿u bá»‹ gÃ¡n láº¡i tá»« cÃ¡c case trÃªn)
if ($page === '403') {
    $title = "403 - Truy cáº­p bá»‹ tá»« chá»‘i";
    $viewFile = $viewDir . '/error/403.php';
}

// 4. KIá»‚M TRA FILE VÃ€ Náº P Ná»˜I DUNG
if ($viewFile && file_exists($viewFile)) {
    ob_start();
    include $viewFile;
    $content = ob_get_clean();
} else {
    $content = "<h2 class='text-red-500'>Lá»—i: Ná»™i dung khÃ´ng tá»“n táº¡i táº¡i $viewFile</h2>";
}

// 5. HIá»‚N THá»Š LAYOUT
$layoutPath = $viewDir . "/layouts/{$layout}.php";
if (file_exists($layoutPath)) {
    include $layoutPath;
} else {
    echo "Lá»—i há»‡ thá»‘ng: Layout khÃ´ng tá»“n táº¡i.";
}



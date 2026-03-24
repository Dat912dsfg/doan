<?php
namespace App\Controllers;

use App\Services\AuthService;
use App\Services\GoogleAuthService;

class AuthController {
    private $authService;

    public function __construct() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $this->authService = new AuthService();
    }

    public function webLogin() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = $_POST['username'] ?? '';
            $password = $_POST['password'] ?? '';

            $user = $this->authService->authenticate($username, $password);

            if ($user) {
                $_SESSION['user_id'] = $user->idNguoiDung;
                $_SESSION['user_email'] = $user->tenDangNhap;
                $_SESSION['user_name'] = $user->hoTen;
                $_SESSION['user_role'] = $user->tenNhom;
                $_SESSION['user_avatar'] = $user->anhDaiDien;
                $_SESSION['permissions'] = $user->permissions;
                
                header("Location: index.php?page=dashboard");
                exit();
            } else {
                $_SESSION['error'] = "Sai tài khoản hoặc mật khẩu!";
                header("Location: index.php?page=auth");
                exit();
            }
        }
    }

    public function googleLogin() {
        $input = json_decode(file_get_contents('php://input'), true);
        $idToken = $input['idToken'] ?? '';

        $googleService = new GoogleAuthService();
        $result = $this->authService->dnBangGoogle($idToken, $googleService);

        if (ob_get_length()) ob_clean();
        header('Content-Type: application/json');
        echo json_encode($result);
        exit();
    }

    public function logout() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $_SESSION = array();

        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(
                session_name(), 
                '', 
                time() - 42000,
                $params["path"], 
                $params["domain"],
                $params["secure"], 
                $params["httponly"]
            );
        }

        session_destroy();

        header("Location: index.php?page=auth");
        exit();
    }

    public function showLoginForm() {
        $error = $_SESSION['error'] ?? null;
        unset($_SESSION['error']);
        include __DIR__ . '/../../views/auth/login.php';
    }
}
<?php
namespace App\Services;

use App\Repositories\UserRepository;

class AuthService {
    private $userRepo;

    public function __construct() {
        $this->userRepo = new UserRepository();
    }

    public function authenticate($username, $password) {
        $user = $this->userRepo->findByUsername($username);

        if ($user && password_verify($password, $user->matKhau)) {
            $user->permissions = $this->userRepo->getPermissions($user->idNhom);
            return $user;
        }
        return null;
    }

    public function dnBangGoogle($idToken, GoogleAuthService $googleService) {
        $payload = $googleService->verifyIdToken($idToken);
        
        if (!$payload) {
            return ['success' => false, 'message' => 'Token Google bị từ chối'];
        }

        $googleId = $payload['sub'];
        $email = $payload['email'];
        $avatar = $payload['picture'] ?? '';

        $user = $this->userRepo->findByGoogleIdOrEmail($googleId, $email);

        if (!$user) {
            return ['success' => false, 'message' => 'Tài khoản lạ. Không có quyền truy cập hệ thống.'];
        }

        if (empty($user->googleId)) {
            $this->userRepo->updateGoogleInfo($user->idNguoiDung, $googleId, $avatar);
            $user->googleId = $googleId;
            $user->anhDaiDien = $avatar;
        }

        $permissions = $this->userRepo->getPermissions($user->idNhom);

        $_SESSION['user_id'] = $user->idNguoiDung;
        $_SESSION['user_name'] = $user->hoTen;
        $_SESSION['user_role'] = $user->tenNhom;
        $_SESSION['permissions'] = $permissions;
        $_SESSION['user_avatar'] = $user->anhDaiDien;

        return ['success' => true];
    }
}
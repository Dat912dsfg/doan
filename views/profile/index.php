<?php
    $hoTen = $user->hoTen ?? 'User';
    $tenMahoa = urlencode($hoTen);
    $anhDaiDien = $_SESSION['user_avatar'] ?? '';
    $nguonAnh = !empty($anhDaiDien) ? $anhDaiDien : "https://ui-avatars.com/api/?name={$tenMahoa}&background=dc2626&color=fff";
    $danhSachQuyen = $_SESSION['permissions'] ?? [];
?>

<div class="flex items-center justify-between my-6">
    <h2 class="text-2xl font-semibold text-gray-700 dark:text-gray-200">
        Hồ sơ cá nhân
    </h2>
</div>

<div class="grid gap-6 mb-8 md:grid-cols-12">
    
    <div class="md:col-span-7 space-y-6">
        
        <div class="min-w-0 p-4 bg-white rounded-lg shadow-xs dark:bg-gray-800 border border-gray-200 dark:border-gray-700">
            <h4 class="mb-4 font-semibold text-gray-800 dark:text-gray-300 border-b pb-2 dark:border-gray-700 flex items-center">
                <i class="fas fa-user-edit mr-2 text-blue-600"></i> Thông tin cơ bản
            </h4>
            <form action="index.php?page=profile_update_info" method="POST" class="space-y-4">
                <label class="block text-sm">
                    <span class="text-gray-700 dark:text-gray-400">Họ và tên</span>
                    <input class="block w-full mt-1 text-sm dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 form-input focus:border-blue-400 focus:shadow-outline-blue" 
                           name="hoTen" value="<?= htmlspecialchars($user->hoTen ?? '') ?>" requiblue>
                </label>

                <label class="block text-sm">
                    <span class="text-gray-700 dark:text-gray-400">Tên đăng nhập (Email/Tài khoản)</span>
                    <input class="block w-full mt-1 text-sm dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 form-input focus:border-blue-400 focus:shadow-outline-blue" 
                           name="tenDangNhap" value="<?= htmlspecialchars($user->tenDangNhap ?? '') ?>" readonly>
                </label>
                
                <div class="flex justify-end mt-4">
                    <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 focus:outline-none focus:shadow-outline-blue">
                        Lưu thông tin
                    </button>
                </div>
            </form>
        </div>

        <div class="min-w-0 p-4 bg-white rounded-lg shadow-xs dark:bg-gray-800 border border-gray-200 dark:border-gray-700">
            <h4 class="mb-4 font-semibold text-gray-800 dark:text-gray-300 border-b pb-2 dark:border-gray-700 flex items-center">
                <i class="fas fa-lock mr-2 text-blue-600"></i> Đổi mật khẩu
            </h4>
            <form action="index.php?page=profile_change_password" method="POST" class="space-y-4">
                <label class="block text-sm">
                    <span class="text-gray-700 dark:text-gray-400">Mật khẩu hiện tại</span>
                    <input type="password" name="old_password" class="block w-full mt-1 text-sm dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 form-input focus:border-blue-400 focus:shadow-outline-blue" requiblue>
                </label>
                <div class="grid grid-cols-2 gap-4">
                    <label class="block text-sm">
                        <span class="text-gray-700 dark:text-gray-400">Mật khẩu mới</span>
                        <input type="password" name="new_password" class="block w-full mt-1 text-sm dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 form-input focus:border-blue-400 focus:shadow-outline-blue" requiblue>
                    </label>
                    <label class="block text-sm">
                        <span class="text-gray-700 dark:text-gray-400">Xác nhận mật khẩu mới</span>
                        <input type="password" name="confirm_password" class="block w-full mt-1 text-sm dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 form-input focus:border-blue-400 focus:shadow-outline-blue" requiblue>
                    </label>
                </div>
                <div class="flex justify-end mt-4">
                    <button type="submit" class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-200 rounded-lg hover:bg-gray-300 dark:bg-gray-700 dark:text-gray-200 dark:hover:bg-gray-600">
                        Cập nhật mật khẩu
                    </button>
                </div>
            </form>
        </div>

    </div>

    <div class="md:col-span-5 space-y-6">
        
        <div class="min-w-0 p-6 bg-gradient-to-br from-blue-600 to-blue-800 rounded-lg shadow-xs text-white">
            <div class="flex items-center justify-center w-16 h-16 mb-4 bg-white rounded-full text-blue-600 text-2xl font-bold mx-auto shadow-inner overflow-hidden">
                <img src="<?= htmlspecialchars($nguonAnh) ?>" alt="Avatar" class="w-full h-full object-cover">
            </div>
            <div class="text-center">
                <h3 class="text-xl font-bold mb-1"><?= htmlspecialchars($user->hoTen ?? '') ?></h3>
                <p class="text-blue-200 font-mono text-sm mb-3">Mã: <?= htmlspecialchars($user->maNguoiDung ?? '') ?></p>
                <span class="px-3 py-1 bg-white bg-opacity-20 rounded-full text-sm font-semibold border border-blue-400 backdrop-blur-sm">
                    <i class="fas fa-shield-alt mr-1"></i> <?= htmlspecialchars($_SESSION['user_role']?? 'Chưa phân nhóm') ?>
                </span>
            </div>
        </div>

        <div class="min-w-0 p-4 bg-white rounded-lg shadow-xs dark:bg-gray-800 border border-gray-200 dark:border-gray-700">
            <h4 class="mb-4 font-semibold text-gray-800 dark:text-gray-300 border-b pb-2 dark:border-gray-700 flex items-center">
                <i class="fas fa-key mr-2 text-blue-600"></i> Phân quyền chi tiết
            </h4>
            <div class="h-48 overflow-y-auto pr-2 custom-scrollbar">
                <ul class="space-y-2">
                    <?php if (!empty($danhSachQuyen)): ?>
                        <?php foreach ($danhSachQuyen as $quyen): ?>
                            <li class="flex items-center text-sm text-gray-600 dark:text-gray-400 font-mono">
                                <i class="fas fa-check-circle text-green-500 mr-2"></i> <?= htmlspecialchars($quyen) ?>
                            </li>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <li class="text-sm text-gray-500 italic">Tài khoản này chưa được cấp quyền chi tiết.</li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>

    </div>
</div>
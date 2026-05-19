<div class="grid gap-6 mb-8 md:grid-cols-2">
    <div class="md:col-span-1">
        <div class="min-w-0 p-4 bg-white rounded-lg shadow-xs dark:bg-gray-800 border border-gray-200 dark:border-gray-700 h-full">
            <div class="flex items-center mb-4 pb-3 border-b border-gray-200 dark:border-gray-700">
                
                <h4 class="font-semibold text-gray-700 dark:text-gray-300">Thông tin cá nhân</h4>
            </div>
            
            <div class="space-y-4">
                <div class="flex flex-col gap-4 xl:flex-row xl:items-start">
                    <div class="text-center mx-auto xl:mx-0">
                        <div class="relative inline-block">
                            <div class="flex items-center justify-center w-28 h-28 bg-gray-100 rounded-full border-2 border-dashed border-gray-300 dark:bg-gray-700 dark:border-gray-600">
                                <i class="fas fa-camera text-2xl text-gray-400 dark:text-gray-500"></i>
                            </div>
                            <button type="button" class="absolute bottom-0 right-0 flex items-center justify-center w-8 h-8 text-white bg-blue-600 rounded-full border-2 border-white shadow-sm hover:bg-blue-700">
                                <i class="fas fa-pen text-xs"></i>
                            </button>
                        </div>
                        <div class="mt-2 text-xs font-medium text-gray-500 dark:text-gray-400">Ảnh đại diện</div>
                    </div>

                    <div class="flex-1 w-full space-y-3">
                        <label class="block text-sm">
                            <span class="text-gray-700 dark:text-gray-400 font-medium flex items-center">
                                Mã người dùng
                                <?php if (isset($user)): ?>
                                    <i class="fas fa-lock ml-2 text-gray-400 text-xs" title="Mã số cố định"></i>
                                <?php endif; ?>
                            </span>
                            <input type="text" name="maNguoiDung" id="maNguoiDung"
                                class="block w-full mt-1 text-sm <?= isset($user) ? 'bg-gray-100 cursor-not-allowed' : 'bg-white' ?> dark:bg-gray-700 dark:text-gray-300 form-input focus:border-blue-400" 
                                value="<?= $user->maNguoiDung ?? '' ?>" 
                                <?= isset($user) ? 'readonly' : 'placeholder="VD: NV001" requiblue' ?>>
                            <span class="text-xs text-blue-600 dark:text-blue-400 hidden mt-1" id="maNguoiDung_error">Vui lòng nhập mã nhân sự</span>
                        </label>

                        <label class="block text-sm">
                            <span class="text-gray-700 dark:text-gray-400 font-medium">Họ và tên <span class="text-blue-600">*</span></span>
                            <input type="text" id="fullname" name="hoTen"
                                class="block w-full mt-1 text-sm dark:bg-gray-700 dark:text-gray-300 form-input focus:border-blue-400" 
                                value="<?= $user->hoTen ?? '' ?>" requiblue>
                            <span class="text-xs text-blue-600 dark:text-blue-400 hidden mt-1" id="fullname_error">Họ tên không được để trống</span>
                        </label>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="md:col-span-1">
        <div class="min-w-0 p-4 bg-white rounded-lg shadow-xs dark:bg-gray-800 border border-gray-200 dark:border-gray-700 h-full">
            <div class="flex items-center mb-4 pb-3 border-b border-gray-200 dark:border-gray-700">
                
                <h4 class="font-semibold text-gray-700 dark:text-gray-300">Tài khoản và Phân quyền</h4>
            </div>
            
            <div class="space-y-4">
                <div class="grid gap-4 md:grid-cols-2">
                    <label class="block text-sm">
                        <span class="text-gray-700 dark:text-gray-400 font-medium">Tên đăng nhập (Email) <span class="text-blue-600">*</span></span>
                        <input type="email" id="email" name="tenDangNhap" 
                            class="block w-full mt-1 text-sm dark:bg-gray-700 dark:text-gray-300 form-input focus:border-blue-400" 
                            value="<?= $user->tenDangNhap ?? '' ?>" requiblue>
                        <span class="text-xs text-blue-600 dark:text-blue-400 hidden mt-1" id="email_error">Email không hợp lệ</span>
                    </label>

                    <div class="block text-sm">
                        <span class="text-gray-700 dark:text-gray-400 font-medium flex items-center">
                            Bảo mật
                        </span>
                        <?php if (isset($user)): ?>
                            <button type="button" 
                                    onclick="if(confirm('Reset mật khẩu về mặc định?')) window.location.href='index.php?page=users_reset_pass&id=<?= $user->idNguoiDung ?>'"
                                    class="flex items-center justify-center w-full px-4 mt-1 font-medium text-blue-600 bg-blue-50 border border-blue-200 rounded-lg h-[38px] text-xs hover:bg-blue-100 transition-colors">
                                <i class="fas fa-sync-alt mr-2 text-xs"></i> Reset mật khẩu
                            </button>
                        <?php else: ?>
                            <div class="relative mt-1">
                                <input type="password" id="password" name="matKhau" 
                                    class="block w-full pr-10 text-sm dark:bg-gray-700 dark:text-gray-300 form-input focus:border-blue-400 font-mono" 
                                    value="12345678" requiblue>
                                <button type="button" id="togglePassword"
                                        class="absolute inset-y-0 right-0 flex items-center px-3 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="grid gap-4 md:grid-cols-2">
                    <label class="block text-sm">
                        <span class="text-gray-700 dark:text-gray-400 font-medium uppercase text-xs tracking-wider">Nhóm quyền</span>
                        <select id="role" name="idNhom" class="block w-full mt-1 text-sm dark:bg-gray-700 form-select focus:border-blue-400" requiblue>
                            <option value="" disabled <?= !isset($user) ? 'selected' : '' ?>>-- Chọn nhóm --</option>
                            <?php if (!empty($danhSachNhom)): ?>
                                <?php foreach ($danhSachNhom as $nhom): ?>
                                    <option value="<?= $nhom->idNhom ?>" <?= (isset($user) && $user->idNhom == $nhom->idNhom) ? 'selected' : '' ?>>
                                        <?= $nhom->tenNhom ?>
                                    </option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </label>
                </div>
            </div>
        </div>
    </div>
</div>
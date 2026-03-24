<div class="flex flex-col items-start justify-between w-full gap-4 my-6 sm:flex-row sm:items-center">
    <div>
        <h2 class="text-2xl font-semibold text-gray-700 dark:text-gray-200">
            Chỉnh sửa nhân sự
        </h2>
        <p class="text-sm text-gray-500 dark:text-gray-400">Đang chỉnh sửa thông tin của: <span class="font-bold text-blue-600"><?= $user->hoTen ?></span></p>
    </div>
    
    <a href="index.php?page=users" 
       class="inline-flex items-center px-4 py-2 text-sm font-medium leading-5 text-gray-700 transition-colors duration-150 bg-white border border-gray-300 rounded-lg hover:text-gray-800 hover:border-gray-400 focus:outline-none focus:shadow-outline-blue dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-gray-300 dark:hover:border-gray-500">
        <i class="fas fa-chevron-left mr-2"></i> Quay lại
    </a>
</div>

<form id="userForm" action="index.php?page=users_xuly_sua" method="POST">
    <input type="hidden" name="idNguoiDung" value="<?= $user->idNguoiDung ?>">

    <?php include 'user_chung/user_info.php'; ?>

    <div class="flex justify-end gap-3 mt-8 pt-6 border-t border-gray-200 dark:border-gray-700">
        <button type="button" onclick="window.location.reload()"
                class="px-4 py-2 text-sm font-medium leading-5 text-gray-700 transition-colors duration-150 bg-white border border-gray-300 rounded-lg hover:text-gray-800 hover:border-gray-400 focus:outline-none focus:shadow-outline-blue dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-gray-300 dark:hover:border-gray-500">
            <i class="fas fa-sync-alt mr-2"></i> Khôi phục dữ liệu
        </button>
        <button type="submit" 
                class="px-5 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-blue-600 border border-transparent rounded-lg hover:bg-blue-700 focus:outline-none focus:shadow-outline-blue shadow-md">
            <i class="fas fa-check-circle mr-2"></i> Cập nhật thông tin
        </button>
    </div>
</form>

<script>
    <?php include 'user_chung/user_validate.js'; ?>
</script>

<style>
    .input-invalid { border-color: #dc2626 !important; }
    .input-valid { border-color: #16a34a !important; }
    .dark .input-invalid { border-color: #ef4444 !important; }
    .dark .input-valid { border-color: #4ade80 !important; }
</style>
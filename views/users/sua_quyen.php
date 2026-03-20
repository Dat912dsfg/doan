<?php 
// Thiết lập chế độ Chỉnh sửa
$isEdit = true; 

// Giả định biến $permission đã được lấy từ database ở controller/file cha
?>

<div class="flex flex-col items-start justify-between w-full gap-4 my-6 sm:flex-row sm:items-center">
    <div>
        <h2 class="text-2xl font-semibold text-gray-700 dark:text-gray-200">
            Sửa quyền hạn: <span class="text-blue-600 font-mono"><?= $quyen->maQuyen ?></span>
        </h2>
        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
            Cập nhật tên hiển thị và mô tả cho quyền hạn
        </p>
    </div>
    
    <a href="index.php?page=users&tab=permissions" 
       class="inline-flex items-center px-4 py-2 text-sm font-medium leading-5 text-gray-700 transition-colors duration-150 bg-white border border-gray-300 rounded-lg hover:text-gray-800 hover:border-gray-400 focus:outline-none focus:shadow-outline-blue dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-gray-300 dark:hover:border-gray-500">
        <i class="fas fa-chevron-left mr-2"></i> Quay lại
    </a>
</div>

<form action="index.php?page=xuly_quyen_sua" method="POST" id="mainForm">
    
    <?php include 'quyen_chung/quyen_info.php'; ?>

    <div class="flex justify-end gap-3 mt-8 pt-6 border-t border-gray-200 dark:border-gray-700">
        <a href="index.php?page=users&tab=permissions" 
           class="px-4 py-2 text-sm font-medium leading-5 text-gray-700 transition-colors duration-150 bg-white border border-gray-300 rounded-lg hover:text-gray-800 hover:border-gray-400 focus:outline-none focus:shadow-outline-blue dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-gray-300 dark:hover:border-gray-500">
            <i class="fas fa-times mr-2"></i> Hủy
        </a>
        <button type="submit" 
                class="px-5 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-blue-600 border border-transparent rounded-lg hover:bg-blue-700 focus:outline-none focus:shadow-outline-blue shadow-md">
            <i class="fas fa-save mr-2"></i> Cập nhật quyền
        </button>
    </div>
</form>

<script>
    <?php include 'quyen_chung/quyen_validate.js'; ?>
</script>
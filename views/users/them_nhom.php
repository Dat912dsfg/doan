<?php
$isEdit = false;
$allPermissions = [];
?>

<div class="flex flex-col items-start justify-between w-full gap-4 my-6 sm:flex-row sm:items-center">
    <div>
        <h2 class="text-2xl font-semibold text-gray-700 dark:text-gray-200">
            Tạo nhóm người dùng
        </h2>
        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
            Thiết lập nhóm mới và phân quyền
        </p>
    </div>
    
    <a href="index.php?page=users&tab=groups" 
       class="inline-flex items-center px-4 py-2 text-sm font-medium leading-5 text-gray-700 transition-colors duration-150 bg-white border border-gray-300 rounded-lg hover:text-gray-800 hover:border-gray-400 focus:outline-none focus:shadow-outline-blue dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-gray-300 dark:hover:border-gray-500">
        <i class="fas fa-chevron-left mr-2"></i> Quay lại
    </a>
</div>

<div class="mb-6 border-b border-gray-200 dark:border-gray-700">
    <ul class="flex flex-wrap -mb-px text-sm font-medium text-center" id="groupTabs" role="tablist">
        <li class="mr-2">
            <button class="inline-block p-4 border-b-2 rounded-t-lg transition-colors duration-150 active-tab text-blue-600 border-blue-600" 
                    id="info-tab" 
                    onclick="switchTab('info')">
                <i class="fas fa-info-circle mr-2"></i> Thông tin chung
            </button>
        </li>
        <li class="mr-2">
            <button class="inline-block p-4 border-b-2 border-transparent rounded-t-lg hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300 transition-colors duration-150" 
                    id="permissions-tab" 
                    onclick="switchTab('permissions')">
                <i class="fas fa-key mr-2"></i> Phân quyền
            </button>
        </li>
    </ul>
</div>

<form action="index.php?page=nhom_xuly_them" method="POST" id="mainForm">
    <div class="grid gap-6 mb-8 md:grid-cols-12">
        <div id="info-tab-content" class="tab-content col-span-12">
            <?php include 'nhom_chung/nhom_info.php'; ?>
        </div>

        <div id="permissions-tab-content" class="tab-content hidden col-span-12">
            <?php include 'nhom_chung/nhom_quyen.php'; ?>
        </div>
    </div>

    <div class="flex justify-end gap-3 mt-8 pt-6 border-t border-gray-200 dark:border-gray-700">
        <button type="reset" 
                onclick="setTimeout(updatePreview, 10)"
                class="px-4 py-2 text-sm font-medium leading-5 text-gray-700 transition-colors duration-150 bg-white border border-gray-300 rounded-lg hover:text-gray-800 hover:border-gray-400 focus:outline-none focus:shadow-outline-blue dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-gray-300 dark:hover:border-gray-500">
            <i class="fas fa-undo mr-2"></i> Làm mới
        </button>
        <button type="submit" 
                class="px-5 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-blue-600 border border-transparent rounded-lg hover:bg-blue-700 focus:outline-none focus:shadow-outline-blue shadow-md">
            <i class="fas fa-save mr-2"></i> Tạo nhóm
        </button>
    </div>
</form>

<script>
    <?php include 'nhom_chung/nhom_validate.js'; ?>
</script>
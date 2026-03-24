<?php

$group = $nhom; 
$isEdit = true;
$groupId = $group->idNhom; 
?>

<div class="flex flex-col items-start justify-between w-full gap-4 my-6 sm:flex-row sm:items-center">
    <div>
        <h2 class="text-2xl font-semibold text-gray-700 dark:text-gray-200">
            Sửa nhóm: <span class="text-blue-600"><?= htmlspecialchars($group->tenNhom) ?></span>
        </h2>
        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
            Mã định danh: <span class="font-mono font-bold"><?= $group->maNhom ?></span>
        </p>
    </div>
    
    <a href="index.php?page=users&tab=groups" 
       class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:border-gray-400 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600">
        <i class="fas fa-chevron-left mr-2"></i> Quay lại danh sách
    </a>
</div>

<div class="mb-6 border-b border-gray-200 dark:border-gray-700">
    <ul class="flex flex-wrap -mb-px text-sm font-medium text-center" id="groupTabs">
        <li class="mr-2">
            <button class="inline-block p-4 border-b-2 rounded-t-lg transition-colors duration-150 text-blue-600 border-blue-600" 
                    id="info-tab" type="button" onclick="switchTab('info')">
                <i class="fas fa-info-circle mr-2"></i> 1. Thông tin chung
            </button>
        </li>
        <li class="mr-2">
            <button class="inline-block p-4 border-b-2 border-transparent rounded-t-lg hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300 transition-colors duration-150" 
                    id="permissions-tab" type="button" onclick="switchTab('permissions')">
                <i class="fas fa-key mr-2"></i> 2. Phân quyền
            </button>
        </li>
        <li class="mr-2">
            <button class="inline-block p-4 border-b-2 border-transparent rounded-t-lg hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300 transition-colors duration-150" 
                    id="members-tab" type="button" onclick="switchTab('members')">
                <i class="fas fa-users mr-2"></i> 3. Thành viên
            </button>
        </li>
    </ul>
</div>

<form action="index.php?page=nhom_xuly_sua" method="POST" id="mainForm">
    <input type="hidden" name="idNhom" value="<?= $group->idNhom ?>">
    
    <div id="info-tab-content" class="tab-content">
        <?php include 'nhom_chung/nhom_info.php'; ?>
    </div>

    <div id="permissions-tab-content" class="tab-content hidden">
        <?php include 'nhom_chung/nhom_quyen.php'; ?>
    </div>

    <div id="members-tab-content" class="tab-content hidden">
        <div class="grid gap-6 md:grid-cols-2">
            <div class="min-w-0 p-4 bg-white rounded-lg shadow-xs dark:bg-gray-800 border border-gray-200 dark:border-gray-700">
                <h4 class="font-semibold text-gray-700 dark:text-gray-300 mb-4 flex items-center">
                    Thành viên hiện tại
                </h4>
                <div class="space-y-2 max-h-80 overflow-y-auto pr-2">
                    <?php if (empty($tv)): ?>
                        <p class="text-sm text-gray-500 italic">Nhóm chưa có thành viên.</p>
                    <?php else: ?>
                        <?php foreach ($tv as $member): ?>
                        <div class="flex items-center justify-between p-2 bg-gray-50 rounded-lg dark:bg-gray-700">
                            <div class="flex items-center">
                                <div class="w-8 h-8 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center font-bold mr-3">
                                    <?= strtoupper(substr($member->hoTen, 0, 1)) ?>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-800 dark:text-gray-200"><?= $member->hoTen ?></p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400"><?= $member->tenDangNhap ?></p>
                                </div>
                            </div>
                            <button type="button" 
                                    
                                    onclick="openMoveUserModal('<?= $member->idNguoiDung ?>', '<?= htmlspecialchars($member->hoTen) ?>', '<?= htmlspecialchars($groupId) ?>')"
                                    class="text-gray-400 hover:text-blue-600 transition-colors duration-150" 
                                    title="Điều chuyển nhóm">
                                <i class="fas fa-exchange-alt"></i>
                            </button>
                        </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
            
            <div class="min-w-0 p-4 bg-white rounded-lg shadow-xs dark:bg-gray-800 border border-gray-200 dark:border-gray-700">
                <h4 class="font-semibold text-gray-700 dark:text-gray-300 mb-4 flex items-center">
                    Thêm thành viên mới
                </h4>
                <div class="relative mb-3">
                    <input type="text" id="searchUser" class="block w-full pl-10 pr-3 py-2 text-sm border border-gray-300 rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300" placeholder="Tìm tên hoặc email...">
                    <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
                </div>
                <div class="space-y-2 max-h-80 overflow-y-auto pr-2" id="availableUsersList">
                    <?php foreach ($user as $user): ?>
                    <label class="flex items-center p-2 bg-gray-50 rounded-lg cursor-pointer hover:bg-gray-100 dark:bg-gray-700 dark:hover:bg-gray-600 transition-colors">
                        <input type="checkbox" name="new_members[]" value="<?= $user->idNguoiDung ?>" 
                            class="w-5 h-5 text-blue-600 border-gray-300 rounded focus:ring-blue-500 mr-3">
                        <div>
                            <p class="text-sm font-medium text-gray-800 dark:text-gray-200"><?= $user->hoTen ?></p>
                            <p class="text-xs text-gray-500 dark:text-gray-400"><?= $user->tenNhom ?></p>
                        </div>
                    </label>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>

    <div class="flex justify-end gap-3 mt-8 pt-6 border-t border-gray-200 dark:border-gray-700">
        <button type="submit" 
                class="px-5 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-blue-600 border border-transparent rounded-lg hover:bg-blue-700 focus:outline-none focus:shadow-outline-blue shadow-md">
            <i class="fas fa-save mr-2"></i> Lưu tất cả thay đổi
        </button>
    </div>
</form>

<div
    x-show="isTransferModalOpen"
    x-cloak
    x-data="{ isTransferModalOpen: false }"
    @open-transfer-modal.window="isTransferModalOpen = true"
    x-transition:enter="transition ease-out duration-150"
    x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100"
    x-transition:leave="transition ease-in duration-150"
    x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0"
    class="fixed inset-0 z-30 flex items-end bg-black bg-opacity-50 sm:items-center sm:justify-center"
>
    <div
        x-show="isTransferModalOpen"
        @click.away="isTransferModalOpen = false"
        @keydown.escape="isTransferModalOpen = false"
        class="w-full px-6 py-4 overflow-hidden bg-white rounded-t-lg dark:bg-gray-800 sm:rounded-lg sm:m-4 sm:max-w-xl"
        role="dialog"
    >
        <header class="flex justify-end">
            <button @click="isTransferModalOpen = false" class="inline-flex items-center justify-center w-6 h-6 text-gray-400">
                <i class="fas fa-times"></i>
            </button>
        </header>
        
        <div class="mt-4 mb-6">
            <p class="mb-2 text-lg font-semibold text-gray-700 dark:text-gray-300">
                Chuyển nhóm mới
            </p>
            <div class="text-sm text-gray-700 dark:text-gray-400">
                <p class="mb-3">
                    Chọn nhóm mới cho thành viên <b id="display-transfer-name" class="text-blue-600"></b>:
                </p>
                
                <div class="relative text-gray-500 focus-within:text-purple-600 dark:focus-within:text-purple-400">
                    <select id="transfer-group-select" 
                            class="block w-full pl-3 pr-10 py-2.5 text-sm border border-gray-300 rounded-lg focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300 form-select">
                        <?php if (!empty($nhomKhac)): ?>
                            <?php foreach ($nhomKhac as $g): ?>
                                <?php if ($g->idNhom != $groupId): ?>
                                    <option value="<?= $g->idNhom ?>"><?= htmlspecialchars($g->tenNhom) ?></option>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>
                <input type="hidden" id="transfer-user-id">
                <input type="hidden" id="transfer-old-group-id">
            </div>
        </div>

        <footer class="flex flex-col items-center justify-end px-6 py-3 -mx-6 -mb-4 space-y-4 sm:space-y-0 sm:space-x-6 sm:flex-row bg-gray-50 dark:bg-gray-800">
            <button @click="isTransferModalOpen = false" 
                    class="w-full px-5 py-3 text-sm text-gray-700 border border-gray-300 rounded-lg sm:w-auto hover:bg-gray-100 dark:text-gray-400">
                Hủy bỏ
            </button>
            <button onclick="executeTransfer()" 
                    class="w-full px-5 py-3 text-sm text-center text-white bg-purple-600 rounded-lg sm:w-auto">
                Xác nhận chuyển
            </button>
        </footer>
    </div>
</div>

<script>
    function switchTab(tab) {
        document.querySelectorAll('.tab-content').forEach(c => c.classList.add('hidden'));
        document.getElementById(tab + '-tab-content').classList.remove('hidden');
        
        document.querySelectorAll('#groupTabs button').forEach(b => {
            b.classList.remove('text-blue-600', 'border-blue-600');
            b.classList.add('border-transparent');
        });
        document.getElementById(tab + '-tab').classList.add('text-blue-600', 'border-blue-600');
    }

    <?php include 'nhom_chung/nhom_validate.js'; ?>

    document.getElementById('searchUser')?.addEventListener('input', function(e) {
        const term = e.target.value.toLowerCase();
        document.querySelectorAll('#availableUsersList label').forEach(label => {
            const text = label.textContent.toLowerCase();
            label.style.display = text.includes(term) ? 'flex' : 'none';
        });
    });
</script>

<script>
    function openMoveUserModal(userId, userName, idNhomCu) {
        const nameDisplay = document.getElementById('display-transfer-name');
        if (nameDisplay) nameDisplay.textContent = userName;

        const idInput = document.getElementById('transfer-user-id');
        const oldGroupInput = document.getElementById('transfer-old-group-id');
        
        if (idInput) idInput.value = userId;
        if (oldGroupInput) oldGroupInput.value = idNhomCu;

        window.dispatchEvent(new CustomEvent('open-transfer-modal'));
    }

    function executeTransfer() {
        const userId = document.getElementById('transfer-user-id').value;
        const oldGroupId = document.getElementById('transfer-old-group-id').value;
        const newGroupId = document.getElementById('transfer-group-select').value;

        if (userId && newGroupId && oldGroupId) {
            const url = `index.php?page=nhom_chuyen_thanhvien&idNguoiDung=${userId}&idNhom=${newGroupId}&idNhomCu=${oldGroupId}`;
            window.location.href = url;
        } else {
            alert('Thiếu thông tin điều chuyển, vui lòng thử lại!');
        }
    }
</script>
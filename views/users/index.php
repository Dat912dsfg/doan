    <!-- Header với tiêu đề và nút action động -->
    <div class="flex flex-col items-start justify-between w-full gap-4 my-6 sm:flex-row sm:items-center">
        <div>
            <h2 class="text-2xl font-semibold text-gray-700 dark:text-gray-200">
                Người dùng và Phân quyền
            </h2>
        </div>
        
        <!-- Dynamic Action Button -->
        <div id="dynamicActionButton">
            <a href="index.php?page=nguoidung_them" 
               class="inline-flex items-center px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-blue-600 border border-transparent rounded-lg hover:bg-blue-700 focus:outline-none focus:shadow-outline-blue shadow-md">
                <i class="fas fa-user-plus mr-2"></i>
                Thêm người dùng mới
            </a>
        </div>
    </div>

    <!-- Tabs Navigation -->
    <div class="mb-6 border-b border-gray-200 dark:border-gray-700">
        <ul class="flex flex-wrap -mb-px text-sm font-medium text-center" id="userTabs" role="tablist">
            <li class="mr-2">
                <button class="inline-block p-4 border-b-2 rounded-t-lg transition-colors duration-150 focus:outline-none active-tab" 
                        id="users-tab" 
                        data-tab="users"
                        data-bs-toggle="tab" 
                        data-bs-target="#tab-users">
                    <i class="fas fa-users mr-2"></i> Danh sách người dùng
                </button>
            </li>
            <li class="mr-2">
                <button class="inline-block p-4 border-b-2 border-transparent rounded-t-lg hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300 transition-colors duration-150 focus:outline-none" 
                        id="groups-tab" 
                        data-tab="groups"
                        data-bs-toggle="tab" 
                        data-bs-target="#tab-groups">
                    <i class="fas fa-layer-group mr-2 "></i> Nhóm vai trò
                </button>
            </li>
            <li class="mr-2">
                <button class="inline-block p-4 border-b-2 border-transparent rounded-t-lg hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300 transition-colors duration-150 focus:outline-none" 
                        id="permissions-tab" 
                        data-tab="permissions"
                        data-bs-toggle="tab" 
                        data-bs-target="#tab-permissions">
                    <i class="fas fa-key mr-2 "></i> Định nghĩa Quyền
                </button>
            </li>
        </ul>
    </div>

    <!-- Tab Content -->
    <div class="tab-content" id="userTabsContent">
        
        <!-- Tab 1: Danh sách nhân sự -->
        <div class="tab-pane fade show active" id="tab-users" role="tabpanel">
            <div class="w-full overflow-hidden rounded-lg shadow-xs border border-gray-200 dark:border-gray-700">
                <!-- Card Header với filter -->
                <div class="px-4 py-3 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">
                    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                        <!-- Search box đẹp hơn -->
                        <div class="relative flex-1 max-w-md">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                <i class="fas fa-search text-gray-400 dark:text-gray-500"></i>
                            </div>
                            <input type="text" 
                                class="block w-full pl-10 pr-4 py-2.5 text-sm bg-gray-100 border-0 rounded-lg dark:bg-gray-700 dark:text-gray-300 placeholder-gray-500 dark:placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-300 dark:focus:ring-blue-800 transition-all duration-150" 
                                placeholder="Tìm kiếm theo tên, email..."
                                style="min-width: 280px;">
                        </div>
                        
                        <div class="flex gap-2">
                            <!-- Select đẹp hơn -->
                            <div class="relative">
                                <select class="block w-full py-2.5 pl-4 pr-10 text-sm bg-gray-100 border-0 rounded-lg appearance-none dark:bg-gray-700 dark:text-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-300 dark:focus:ring-blue-800 transition-all duration-150 cursor-pointer">
                                    <option value="">Tất cả vai trò</option>
                                    <option value="admin">Admin</option>
                                    <option value="staff">Staff</option>
                                    <option value="viewer">Viewer</option>
                                </select>
                                <div class="absolute inset-y-0 right-0 flex items-center px-3 pointer-events-none">
                                    <i class="fas fa-chevron-down text-xs text-gray-400 dark:text-gray-500"></i>
                                </div>
                            </div>
                            
                            <!-- Nút lọc đẹp hơn -->
                            <button class="inline-flex items-center px-4 py-2.5 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-blue-300 dark:bg-gray-700 dark:text-gray-300 dark:hover:bg-gray-600 dark:focus:ring-blue-800 transition-all duration-150">
                                <i class="fas fa-filter mr-2 text-blue-500"></i>
                                <span>Lọc</span>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Table -->
                <div class="w-full overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase border-b dark:border-gray-700 bg-gray-50 dark:text-gray-400 dark:bg-gray-800">
                                <th class="px-4 py-3">Người dùng</th>
                                <th class="px-4 py-3">Tên đăng nhập</th>
                                <th class="px-4 py-3">Vai trò</th>
                                <th class="px-4 py-3">Quyền hạn</th>
                                <th class="px-4 py-3 text-right">Thao tác</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800">
                            <?php if (!empty($danhSachNguoiDung)): ?>
                                <?php foreach ($danhSachNguoiDung as $user): ?>
                                    <tr class="text-gray-700 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-150">
                                        <td class="px-4 py-3">
                                            <div class="flex items-center text-sm">
                                                <div class="relative hidden w-8 h-8 mr-3 rounded-full md:block flex-shrink-0">
                                                    <div class="flex items-center justify-center w-full h-full rounded-full bg-blue-100 text-blue-600 dark:bg-blue-900 dark:text-blue-200 font-bold">
                                                        <?= strtoupper(substr($user->hoTen ?? 'U', 0, 1)) ?>
                                                    </div>
                                                </div>
                                                <div class="min-w-0">
                                                    <p class="font-semibold break-words"><?= $user->maNguoiDung ?> - <?= $user->hoTen ?></p>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-4 py-3 text-sm">
                                            <?= $user->tenDangNhap ?>
                                        </td>
                                        <td class="px-4 py-3 text-center">
                                            <span class="px-2 py-1 text-xs font-semibold leading-tight text-blue-700 dark:text-blue-100">
                                                <?= $user->tenNhom ?>
                                            </span>
                                        </td>
                                        <td class="px-4 py-3 text-xs">
                                            <span class="px-2 py-1 font-medium leading-tight text-blue-700 bg-blue-100 rounded-full dark:bg-blue-700 dark:text-blue-100 inline-block">
                                                <?= count($user->permissions) ?> quyền
                                            </span>
                                        </td>
                                        <td class="px-4 py-3 text-sm text-right">
                                            <div class="flex items-center justify-end space-x-2">
                                                <button @click="openModal" 
                                                        onclick="triggerModal({
                                                            title: 'Khôi phục mật khẩu',
                                                            description: 'Bạn đang khôi phục mật khẩu của <?= $user->hoTen ?>.',
                                                            confirmUrl: 'index.php?page=users_xuly_reset&id=<?= $user->idNguoiDung ?>',
                                                            btnClass: 'bg-blue-600 hover:bg-blue-700'
                                                        })"
                                                        class="text-gray-400 hover:text-blue-600 p-1">
                                                    <i class="fas fa-key"></i>
                                                </button>
                                                <a href="index.php?page=nguoidung_sua&id=<?= $user->idNguoiDung ?>" 
                                                class="text-gray-400 hover:text-blue-600 p-1">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <button @click="openModal" 
                                                        onclick="triggerModal({
                                                            title: 'Xóa người dùng',
                                                            description: 'Hành động này không thể hoàn tác!',
                                                            confirmUrl: 'index.php?page=users_xuly_xoa&id=<?= $user->idNguoiDung ?>',
                                                            btnClass: 'bg-blue-600 hover:bg-blue-700'
                                                        })"
                                                        class="text-gray-400 hover:text-red-600 p-1">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr><td colspan="5" class="px-4 py-3 text-center">Không có dữ liệu.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Tab 2: Nhóm vai trò -->
        <div class="tab-pane fade" id="tab-groups" role="tabpanel" style="display: none;">
            <div class="grid gap-6">
                <div class="w-full overflow-hidden rounded-lg shadow-xs border border-gray-200 dark:border-gray-700">
                    <div class="px-4 py-3 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">
                        <h4 class="font-semibold text-gray-700 dark:text-gray-300">
                            Các nhóm quyền hiện có
                        </h4>
                    </div>
                    <div class="w-full overflow-x-auto">
                        <table class="w-full whitespace-no-wrap">
                            <thead>
                                <tr class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase border-b dark:border-gray-700 bg-gray-50 dark:text-gray-400 dark:bg-gray-800">
                                    <th class="px-4 py-3">Mã nhóm</th>
                                    <th class="px-4 py-3">Tên nhóm</th>
                                    <th class="px-4 py-3">Mô tả</th>
                                    <th class="px-4 py-3">Thành viên</th>
                                    <th class="px-4 py-3 text-right">Thao tác</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800">
                                <?php if (!empty($danhSachNhom)): ?>
                                    <?php foreach ($danhSachNhom as $nhom): ?>
                                        <tr class="text-gray-700 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-150">
                                            <td class="px-4 py-3 text-sm">
                                                <code class="px-2 py-1 font-mono text-xs bg-gray-100 rounded dark:bg-gray-700 text-blue-600 dark:text-blue-400">
                                                    <?= $nhom->maNhom ?>
                                                </code>
                                            </td>
                                            <td class="px-4 py-3 font-medium">
                                                <?= $nhom->tenNhom ?>
                                            </td>
                                            <td class="px-4 py-3 text-sm text-gray-600 dark:text-gray-400">
                                                <?= $nhom->moTa ?? 'Khong co mo ta' ?>
                                            </td>
                                            <td class="px-4 py-3">
                                                <span class="px-2 py-1 text-xs font-medium text-gray-600 bg-gray-100 rounded dark:bg-gray-700 dark:text-gray-400">
                                                    <?= $nhom->soThanhVien ?? 0 ?> Users
                                                </span>
                                            </td>
                                            <td class="px-4 py-3 text-sm text-right">
                                                <a href="index.php?page=nhom_sua&id=<?= $nhom->idNhom ?>" 
                                                class="text-gray-400 hover:text-blue-600 dark:hover:text-blue-400 transition-colors duration-150 mx-1">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <button type="button" 
                                                        @click="isDeleteGroupModalOpen = true" 
                                                        onclick="openDeleteGroupModal('<?= $nhom->idNhom ?>', '<?= htmlspecialchars($nhom->maNhom) ?>')"
                                                        class="text-gray-400 hover:text-blue-600 transition-colors duration-150"
                                                        title="Xóa nhóm">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="5" class="px-4 py-3 text-center text-gray-500">Khong co du lieu nhom.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tab 3: Định nghĩa Quyền -->
        <div class="tab-pane fade" id="tab-permissions" role="tabpanel" style="display: none;">
            <div class="w-full overflow-hidden rounded-lg shadow-xs border border-gray-200 dark:border-gray-700">
                <div class="px-4 py-3 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center">
                    <h4 class="font-semibold text-gray-700 dark:text-gray-300">
                        Danh sách quyền
                    </h4>
                    
                </div>
                <div class="w-full overflow-x-auto">
                    <table class="w-full whitespace-no-wrap">
                        <thead>
                            <tr class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase border-b dark:border-gray-700 bg-gray-50 dark:text-gray-400 dark:bg-gray-800">
                                <th class="px-4 py-3">Mã quyền (Key)</th>
                                <th class="px-4 py-3">Tên hiển thị</th>
                                <th class="px-4 py-3">Nhóm sử dụng</th>
                                <th class="px-4 py-3 text-right">Thao tác</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800">
                            <?php if (!empty($danhSachQuyen)): ?>
                                <?php foreach ($danhSachQuyen as $quyen): ?>
                                    <tr class="text-gray-700 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-150">
                                        <td class="px-4 py-3 text-sm">
                                            <code class="px-2 py-1 font-mono text-xs bg-gray-100 rounded dark:bg-gray-700 text-blue-600 dark:text-blue-400">
                                                <?= $quyen->maQuyen ?>
                                            </code>
                                        </td>
                                        <td class="px-4 py-3 text-sm">
                                            <?= $quyen->tenQuyen ?>
                                        </td>
                                        <td class="px-4 py-3">
                                            <span class="px-2 py-1 text-xs font-semibold leading-tight text-blue-700 bg-blue-100 rounded-full dark:bg-blue-700 dark:text-blue-100">
                                                <?= $quyen->soNhom ?? 0 ?> Nhóm
                                            </span>
                                        </td>
                                        <td class="px-4 py-3 text-right">
                                            <a href="index.php?page=quyen_sua&id=<?= $quyen->idQuyen ?>" 
                                            class="text-gray-400 hover:text-blue-600 dark:hover:text-blue-400 transition-colors duration-150 mx-1">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <button @click="openModal" 
                                                    onclick="triggerModal({
                                                        title: 'Xóa quyền',
                                                        description: 'Bạn đang xóa quyền <?= $quyen->tenQuyen ?>. Hành động này không thể hoàn tác!',
                                                        confirmUrl: 'index.php?page=xuly_quyen_xoa&id=<?= $quyen->idQuyen ?>',
                                                        btnClass: 'bg-blue-600 hover:bg-blue-700'
                                                    })"
                                                    class="text-gray-400 hover:text-blue-600 transition-colors duration-150">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="4" class="px-4 py-3 text-center text-gray-500">Chưa có định nghĩa quyền nào.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

<div
    x-show="isDeleteGroupModalOpen"
    x-cloak
    x-data="{ isDeleteGroupModalOpen: false }"
    @open-delete-modal.window="isDeleteGroupModalOpen = true"
    x-transition:enter="transition ease-out duration-150"
    x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100"
    x-transition:leave="transition ease-in duration-150"
    x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0"
    class="fixed inset-0 z-40 flex items-end bg-black bg-opacity-50 sm:items-center sm:justify-center"
>
    <div
        x-show="isDeleteGroupModalOpen"
        @click.away="isDeleteGroupModalOpen = false"
        @keydown.escape="isDeleteGroupModalOpen = false"
        class="w-full px-6 py-4 overflow-hidden bg-white rounded-t-lg dark:bg-gray-800 sm:rounded-lg sm:m-4 sm:max-w-xl "
        role="dialog"
    >
        <div class="mt-4 mb-6">
            <p class="mb-2 text-lg font-semibold text-gray-700 dark:text-gray-300">
                Xác nhận xóa nhóm: <span id="delete-group-display-name" class="text-blue-600"></span>
            </p>
            <div class="text-sm text-gray-700 dark:text-gray-400 space-y-3">
                <div class="p-3 bg-blue-50 dark:bg-blue-900/20 text-blue-700 dark:text-blue-400 rounded-lg border border-blue-100 dark:border-blue-800">
                    <i class="fas fa-exclamation-triangle mr-2"></i>
                    <b>Cảnh báo:</b> Hành động này sẽ xóa toàn bộ thành viên trong nhóm. Di chuyển người dùng sang nhóm mới (nếu có) để xóa an toàn.
                </div>
                
                <p>Để xác nhận, vui lòng nhập chính xác mã nhóm vào ô dưới đây:</p>
                
                <input type="text" 
                       id="delete-confirm-input" 
                       placeholder="Nhập mã nhóm..."
                       autocomplete="off"
                       class="block w-full px-4 py-2 text-sm border border-gray-300 rounded-lg focus:border-blue-400 focus:outline-none focus:shadow-outline-blue dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300">
                
                <input type="hidden" id="delete-group-id">
                <input type="hidden" id="delete-group-expected-name">
            </div>
        </div>

        <footer class="flex flex-col items-center justify-end px-6 py-3 -mx-6 -mb-4 space-y-4 sm:space-y-0 sm:space-x-6 sm:flex-row bg-gray-50 dark:bg-gray-800">
            <button @click="isDeleteGroupModalOpen = false" 
                    class="w-full px-5 py-3 text-sm text-gray-700 border border-gray-300 rounded-lg sm:w-auto hover:bg-gray-100 dark:text-gray-400">
                Hủy bỏ
            </button>
            <button onclick="executeDeleteGroup()" 
                    class="w-full px-5 py-3 text-sm text-center text-white bg-blue-600 rounded-lg sm:w-auto hover:bg-blue-700 shadow-lg">
                Xác nhận xóa vĩnh viễn
            </button>
        </footer>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const tabs = document.querySelectorAll('[data-bs-toggle="tab"]');
        const tabPanes = {
            users: document.getElementById('tab-users'),
            groups: document.getElementById('tab-groups'),
            permissions: document.getElementById('tab-permissions')
        };
        const actionBtnContainer = document.getElementById('dynamicActionButton');

        function setActiveTab(activeId) {
            tabs.forEach(tab => {
                const tabId = tab.getAttribute('data-tab');
                tab.classList.remove('active', 'text-blue-600', 'border-blue-600', 'dark:text-blue-500', 'dark:border-blue-500');
                
                if (tabId === activeId) {
                    tab.classList.add('active', 'text-blue-600', 'border-blue-600', 'dark:text-blue-500', 'dark:border-blue-500');
                } else {
                    tab.classList.add('border-transparent', 'hover:text-gray-600', 'hover:border-gray-300', 'dark:hover:text-gray-300');
                }
            });
        }

        tabs.forEach(tab => {
            tab.addEventListener('click', function(e) {
                e.preventDefault();
                const targetId = this.getAttribute('data-bs-target').replace('#tab-', '');
                
                Object.values(tabPanes).forEach(pane => {
                    pane.style.display = 'none';
                    pane.classList.remove('show', 'active');
                });
                
                tabPanes[targetId].style.display = 'block';
                tabPanes[targetId].classList.add('show', 'active');
                
                setActiveTab(targetId);
                
                const actions = {
                    users: { icon: 'user-plus', text: 'Thêm người dùng mới', page: 'nguoidung_them', color: 'blue' },
                    groups: { icon: 'folder-plus', text: 'Thêm nhóm mới', page: 'nhom_them', color: 'blue' },
                    permissions: { icon: 'key', text: 'Thêm quyền mới', page: 'quyen_them', color: 'blue' }
                };
                
                const action = actions[targetId];
                actionBtnContainer.innerHTML = `
                    <a href="index.php?page=${action.page}" 
                    class="inline-flex items-center px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-${action.color}-600 border border-transparent rounded-lg hover:bg-${action.color}-700 focus:outline-none focus:shadow-outline-${action.color} shadow-md">
                        <i class="fas fa-${action.icon} mr-2"></i>
                        ${action.text}
                    </a>
                `;
            });
        });

        const urlParams = new URLSearchParams(window.location.search);
        const activeTabParam = urlParams.get('tab'); 

        if (activeTabParam) {
            const targetTab = document.querySelector(`[data-bs-target="#tab-${activeTabParam}"]`);
            if (targetTab) {
                targetTab.click();
            } else if (tabs.length > 0) {
                tabs[0].click();
            }
        } else if (tabs.length > 0) {
            tabs[0].click();
        }
    });
</script>

<script>
    function openDeleteGroupModal(groupId, groupName) {
        document.getElementById('delete-group-display-name').textContent = groupName;

        document.getElementById('delete-group-id').value = groupId;
        document.getElementById('delete-group-expected-name').value = groupName;

        const input = document.getElementById('delete-confirm-input');
        input.value = '';
        input.classList.remove('border-blue-600', 'bg-blue-50');

        window.dispatchEvent(new CustomEvent('open-delete-modal'));
    }

    function executeDeleteGroup() {
        const groupId = document.getElementById('delete-group-id').value;
        const expectedName = document.getElementById('delete-group-expected-name').value;
        const userInput = document.getElementById('delete-confirm-input').value.trim();

        if (userInput === expectedName) {
            window.location.href = `index.php?page=nhom_xuly_xoa&id=${groupId}`;
        } else {
            const inputEl = document.getElementById('delete-confirm-input');
            inputEl.classList.add('border-blue-600', 'bg-blue-50');
            inputEl.focus();
            alert('Mã nhóm nhập vào không chính xác. Vui lòng kiểm tra lại!');
        }
    }
</script>

<style>
    [data-bs-toggle="tab"] {
        color: #6b7280;
        border-bottom: 2px solid transparent;
    }
    [data-bs-toggle="tab"].active {
        color: #2563eb;
        border-bottom-color: #2563eb;
    }
    .dark [data-bs-toggle="tab"].active {
        color: #60a5fa;
        border-bottom-color: #60a5fa;
    }
    [data-bs-toggle="tab"]:hover {
        color: #4b5563;
        border-bottom-color: #d1d5db;
    }
    .dark [data-bs-toggle="tab"]:hover {
        color: #e5e7eb;
        border-bottom-color: #4b5563;
    }
    
    .peer:checked ~ div:last-child {
        transform: translateX(100%);
    }
</style>
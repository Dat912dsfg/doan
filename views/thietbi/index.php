<div class="flex flex-col items-start justify-between w-full gap-4 my-6 sm:flex-row sm:items-center min-w-0">
    <div class="min-w-0 flex-1">
        <h2 class="text-2xl font-semibold text-gray-700 dark:text-gray-200 truncate">
            Quản lý Thiết bị & Phòng ở
        </h2>
    </div>
    
    <div id="dynamicActionButton" class="flex-shrink-0"></div>
</div>

<div class="mb-6 border-b border-gray-200 dark:border-gray-700">
    <ul class="flex flex-wrap -mb-px text-sm font-medium text-center text-gray-500 dark:text-gray-400">
        <li class="mr-2">
            <a href="#" data-bs-toggle="tab" data-bs-target="#tab-thietbi" data-tab="thietbi"
               class="inline-flex items-center justify-center p-4 border-b-2 rounded-t-lg group transition-colors duration-200">
                <i class="fas fa-microchip mr-2"></i>
                Danh sách thiết bị
            </a>
        </li>
        <li class="mr-2">
            <a href="#" data-bs-toggle="tab" data-bs-target="#tab-khuvuc" data-tab="khuvuc"
               class="inline-flex items-center justify-center p-4 border-b-2 rounded-t-lg group transition-colors duration-200">
                <i class="fas fa-door-open mr-2"></i>
                Danh sách Phòng
            </a>
        </li>
    </ul>
</div>

<div class="tab-content">
    
    <div id="tab-thietbi" style="display: none;">
        <div class="w-full mb-8 overflow-hidden rounded-xl shadow-xs border border-gray-200 dark:border-gray-700">
            <div class="w-full overflow-x-auto">
                <table class="w-full whitespace-no-wrap">
                    <thead>
                        <tr class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase border-b dark:border-gray-700 bg-gray-50 dark:text-gray-400 dark:bg-gray-800">
                            <th class="px-4 py-3">Thiết bị</th>
                            <th class="px-4 py-3">Vị trí (Phòng)</th>
                            <th class="px-4 py-3">Kết nối</th>
                            <th class="px-4 py-3">Topic MQTT</th>
                            <th class="px-4 py-3 text-right">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800">
                        <?php if (!empty($danhSachThietBi)): ?>
                            <?php foreach ($danhSachThietBi as $tb): ?>
                                <tr class="text-gray-700 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-150">
                                    <td class="px-4 py-3 text-sm">
                                        <div class="flex items-center">
                                            <div class="p-2 mr-3 rounded-full bg-blue-100 text-blue-600 dark:bg-blue-900 dark:text-blue-200">
                                                <i class="fas fa-microchip"></i>
                                            </div>
                                            <div class="min-w-0">
                                                <p class="font-semibold truncate"><?= htmlspecialchars($tb->tenThietBi) ?></p>
                                                <p class="text-[10px] text-gray-500 font-mono uppercase"><?= htmlspecialchars($tb->maThietBi) ?></p>
                                            </div>
                                        </div>
                                    </td>
                                    
                                    <td class="px-4 py-3 text-sm">
                                        <span class="font-medium text-gray-800 dark:text-gray-200">
                                            <i class="fas fa-home mr-1 text-gray-400"></i>
                                            <?= htmlspecialchars($tb->tenKhuVuc ?? 'Chưa gán phòng') ?>
                                        </span>
                                    </td>
                                    
                                    <td class="px-4 py-3 text-xs">
                                        <?php if (!$tb->isActive()): ?>
                                            <span class="px-2 py-1 font-bold text-gray-500 bg-gray-100 rounded-full dark:bg-gray-700">
                                                Tạm dừng
                                            </span>
                                        <?php elseif ($tb->isOnline()): ?>
                                            <span class="px-2 py-1 font-bold text-green-700 bg-green-100 rounded-full dark:bg-green-700 dark:text-green-100 flex items-center w-fit">
                                                <span class="w-2 h-2 bg-green-500 rounded-full mr-1 animate-pulse"></span> Online
                                            </span>
                                        <?php else: ?>
                                            <span class="px-2 py-1 font-bold text-red-700 bg-red-100 rounded-full dark:bg-red-900 dark:text-red-100">
                                                Ngoại tuyến
                                            </span>
                                        <?php endif; ?>
                                    </td>
                                    
                                    <td class="px-4 py-3 text-sm font-mono text-blue-600 dark:text-blue-400 italic">
                                        <?= htmlspecialchars($tb->topicMQTT) ?>
                                    </td>
                                    
                                    <td class="px-4 py-3 text-sm text-right">
                                        <a href="index.php?page=thietbi_sua&id=<?= $tb->idThietBi ?>" class="text-blue-600 hover:text-blue-800 dark:text-blue-400 p-2">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button onclick="triggerModal({ title: 'Gỡ thiết bị', description: 'Bạn muốn gỡ thiết bị <?= $tb->tenThietBi ?> khỏi phòng?', confirmUrl: 'index.php?page=thietbi_xuly_xoa&id=<?= $tb->idThietBi ?>' })" class="text-red-500 hover:text-red-700 p-2">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr><td colspan="5" class="px-4 py-10 text-center text-gray-400">Chưa có thiết bị nào trong các phòng.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div id="tab-khuvuc" style="display: none;">
        <div class="grid gap-6 mb-8 md:grid-cols-2 xl:grid-cols-3">
            <?php if (!empty($danhSachKhuVuc)): foreach ($danhSachKhuVuc as $kv): ?>
                <div class="p-5 bg-white rounded-xl shadow-sm dark:bg-gray-800 border border-gray-200 dark:border-gray-700 hover:border-blue-500 transition-all">
                    <div class="flex items-start justify-between mb-4">
                        <div class="flex items-center">
                            <div class="p-3 mr-4 rounded-lg bg-blue-50 dark:bg-blue-900/30 text-blue-600">
                                <i class="fas fa-door-closed text-xl"></i>
                            </div>
                            <div>
                                <h4 class="text-lg font-bold text-gray-800 dark:text-gray-100"><?= htmlspecialchars($kv->tenKhuVuc) ?></h4>
                                <p class="text-[10px] text-gray-400 uppercase tracking-widest">ID: <?= htmlspecialchars($kv->maKhuVuc) ?></p>
                            </div>
                        </div>
                        <span class="px-2 py-1 text-[10px] font-black rounded bg-gray-100 dark:bg-gray-700 dark:text-gray-300">
                            <?= ($kv->cheDo === 'AUTO') ? 'TỰ ĐỘNG' : 'THỦ CÔNG' ?>
                        </span>
                    </div>
                    
                    <div class="mb-4">
                        <p class="text-xs text-gray-500 dark:text-gray-400 italic mb-3">
                            <?= htmlspecialchars($kv->moTa ?? 'Phòng đang cho thuê...') ?>
                        </p>
                        <div class="flex items-center gap-4">
                            <div class="flex items-center text-xs text-gray-600 dark:text-gray-400">
                                <i class="fas fa-plug mr-2 text-green-500"></i>
                                <span><b><?= intval($kv->soThietBi ?? 0) ?></b> Thiết bị</span>
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center justify-end gap-3 pt-4 border-t dark:border-gray-700">
                        <a href="index.php?page=khuvuc_sua&id=<?= $kv->idKhuVuc ?>" class="text-xs font-bold text-blue-600 uppercase">Chỉnh sửa</a>
                        <button onclick="triggerModal({ title: 'Xóa phòng', description: 'Dữ liệu thiết bị trong phòng này sẽ bị ảnh hưởng!', confirmUrl: 'index.php?page=khuvuc_xuly_xoa&id=<?= $kv->idKhuVuc ?>' })" class="text-gray-400 hover:text-red-600 transition">
                            <i class="fas fa-trash-alt"></i>
                        </button>
                    </div>
                </div>
            <?php endforeach; else: ?>
                <div class="col-span-full p-10 text-center bg-white rounded-xl border-2 border-dashed border-gray-200 dark:border-gray-700">
                    <p class="text-gray-400">Nhà trọ hiện chưa được chia phòng quản lý.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>

</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const tabs = document.querySelectorAll('[data-bs-toggle="tab"]');
        const tabPanes = {
            thietbi: document.getElementById('tab-thietbi'),
            khuvuc: document.getElementById('tab-khuvuc')
        };
        const actionBtnContainer = document.getElementById('dynamicActionButton');

        function updateUI(activeId) {
            tabs.forEach(tab => {
                const isMatch = tab.getAttribute('data-tab') === activeId;
                tab.className = `inline-flex items-center justify-center p-4 border-b-2 rounded-t-lg transition-all duration-200 ${
                    isMatch ? 'text-blue-600 border-blue-600 dark:text-blue-400 dark:border-blue-400 font-bold' : 'border-transparent text-gray-400'
                }`;
            });

            const config = {
                thietbi: { text: 'Thêm Thiết bị', page: 'thietbi_them' },
                khuvuc: { text: 'Thêm Phòng mới', page: 'khuvuc_them' }
            }[activeId];

            actionBtnContainer.innerHTML = `
                <a href="index.php?page=${config.page}" class="flex items-center px-4 py-2 text-sm font-bold text-white bg-blue-600 rounded-lg hover:bg-blue-700 shadow-md">
                    <i class="fas fa-plus mr-2"></i> ${config.text}
                </a>
            `;
        }

        tabs.forEach(tab => {
            tab.addEventListener('click', function(e) {
                e.preventDefault();
                const targetId = this.getAttribute('data-tab');
                Object.values(tabPanes).forEach(p => p.style.display = 'none');
                tabPanes[targetId].style.display = 'block';
                updateUI(targetId);
            });
        });

        tabs[0].click();
    });
</script>
<?php
$danhSachKichBan = $danhSachKichBan ?? [];
$danhSachPhong = $danhSachPhong ?? [];
$danhSachCauHinh = $danhSachCauHinh ?? [];

if (!function_exists('tdText')) {
    function tdText($value) {
        return htmlspecialchars((string) ($value ?? ''), ENT_QUOTES, 'UTF-8');
    }
}

if (!function_exists('tdValue')) {
    function tdValue($item, $key, $default = '') {
        return is_array($item) ? ($item[$key] ?? $default) : $default;
    }
}

$tongKichBan = count($danhSachKichBan);
$dangBat = count(array_filter($danhSachKichBan, fn($item) => (int) tdValue($item, 'kichHoat', 0) === 1));
$tongPhong = count($danhSachPhong);
$phongTuDong = count(array_filter($danhSachPhong, fn($item) => (int) tdValue($item, 'cheDoTuDong', 0) === 1));
?>

<div class="space-y-6 py-6">
    <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
        <div>
            <p class="text-sm font-semibold text-blue-600">Tự động hóa & cảnh báo</p>
            <h1 class="mt-1 text-2xl font-bold text-gray-900 dark:text-gray-100">Kịch bản điều khiển và ngưỡng theo phòng</h1>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Cấu hình trên web sẽ được lưu vào database. Wokwi tải lại qua API `tudong.php?action=sync` để chạy theo đúng kịch bản đang bật.</p>
        </div>

        <a href="index.php?page=tudong_them" class="inline-flex items-center px-4 py-2.5 text-sm font-semibold text-white bg-blue-600 rounded-md hover:bg-blue-700">
            <i class="fas fa-plus mr-2"></i> Thêm kịch bản
        </a>
    </div>

    <div class="grid gap-4 md:grid-cols-4">
        <div class="p-5 bg-white border border-gray-100 rounded-lg shadow-sm dark:bg-gray-800 dark:border-gray-700">
            <p class="text-sm text-gray-500 dark:text-gray-400">Tổng kịch bản</p>
            <p class="mt-2 text-3xl font-bold text-gray-900 dark:text-gray-100"><?= $tongKichBan ?></p>
        </div>
        <div class="p-5 bg-white border border-gray-100 rounded-lg shadow-sm dark:bg-gray-800 dark:border-gray-700">
            <p class="text-sm text-gray-500 dark:text-gray-400">Đang kích hoạt</p>
            <p class="mt-2 text-3xl font-bold text-emerald-600"><?= $dangBat ?></p>
        </div>
        <div class="p-5 bg-white border border-gray-100 rounded-lg shadow-sm dark:bg-gray-800 dark:border-gray-700">
            <p class="text-sm text-gray-500 dark:text-gray-400">Phòng đã khai báo</p>
            <p class="mt-2 text-3xl font-bold text-gray-900 dark:text-gray-100"><?= $tongPhong ?></p>
        </div>
        <div class="p-5 bg-white border border-gray-100 rounded-lg shadow-sm dark:bg-gray-800 dark:border-gray-700">
            <p class="text-sm text-gray-500 dark:text-gray-400">Phòng ở chế độ tự động</p>
            <p class="mt-2 text-3xl font-bold text-blue-600"><?= $phongTuDong ?></p>
        </div>
    </div>

    <div class="p-4 text-sm text-slate-700 bg-slate-50 border border-slate-100 rounded-lg dark:bg-gray-800 dark:border-gray-700 dark:text-gray-300">
        <div class="flex items-start gap-3">
            <i class="fas fa-diagram-project mt-0.5 text-slate-500"></i>
            <div>
                <p class="font-semibold">Luồng dữ liệu đang dùng</p>
                <p class="mt-1 text-xs leading-5">
                    Web lưu cấu hình vào các bảng `kich_ban_tu_dong`, `kich_ban_hanh_dong`, `phong_kich_ban`, `cau_hinh`.
                    Khi ESP32 trên Wokwi chạy, thiết bị gọi `sync` để lấy kịch bản đang bật rồi tự thực thi theo điều kiện cảm biến hoặc lịch đã cấu hình.
                </p>
            </div>
        </div>
    </div>

    <form method="post" action="index.php?page=tudong_config_save" class="bg-white border border-gray-100 rounded-lg shadow-sm overflow-hidden dark:bg-gray-800 dark:border-gray-700">
        <div class="px-5 py-4 border-b border-gray-100 dark:border-gray-700 flex items-center justify-between">
            <div>
                <h2 class="text-sm font-bold uppercase tracking-wider text-gray-800 dark:text-gray-100">Ngưỡng cảnh báo theo phòng</h2>
                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Các ngưỡng này được dùng để tạo cảnh báo tự động khi dữ liệu cảm biến vượt mức.</p>
            </div>
            <button type="submit" class="inline-flex items-center px-4 py-2 text-sm font-semibold text-white bg-emerald-600 rounded-md hover:bg-emerald-700">
                <i class="fas fa-save mr-2"></i> Lưu ngưỡng
            </button>
        </div>

        <div class="grid gap-4 p-5 xl:grid-cols-2">
            <?php foreach ($danhSachCauHinh as $item): ?>
                <?php $idPhong = (int) tdValue($item, 'idPhong', 0); ?>
                <section class="p-4 border border-gray-200 rounded-lg bg-gray-50 dark:bg-gray-700/40 dark:border-gray-700">
                    <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
                        <div>
                            <h3 class="text-base font-semibold text-gray-900 dark:text-gray-100"><?= tdText(tdValue($item, 'tenPhong', 'Phòng')) ?></h3>
                            <p class="text-xs text-gray-500 dark:text-gray-400">
                                Chế độ phòng:
                                <span class="<?= (int) tdValue($item, 'cheDoTuDong', 0) === 1 ? 'text-blue-600' : 'text-gray-500' ?>">
                                    <?= (int) tdValue($item, 'cheDoTuDong', 0) === 1 ? 'Tự động' : 'Thủ công' ?>
                                </span>
                            </p>
                        </div>
                        <a href="index.php?page=khuvuc_toggle_chedo&id=<?= $idPhong ?>" class="inline-flex items-center px-3 py-2 text-xs font-semibold rounded-md <?= (int) tdValue($item, 'cheDoTuDong', 0) === 1 ? 'bg-amber-50 text-amber-700 hover:bg-amber-100' : 'bg-blue-50 text-blue-700 hover:bg-blue-100' ?>">
                            <?= (int) tdValue($item, 'cheDoTuDong', 0) === 1 ? 'Chuyển thủ công' : 'Bật tự động' ?>
                        </a>
                    </div>

                    <div class="grid gap-3 mt-4 md:grid-cols-2">
                        <label class="text-sm">
                            <span class="text-xs font-semibold text-gray-500">Nhiệt độ min (°C)</span>
                            <input type="number" step="0.1" name="config[<?= $idPhong ?>][nhietDoMin]" value="<?= tdText(tdValue($item, 'nhietDoMin')) ?>" class="block w-full mt-1 text-sm rounded-md border-gray-300 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-200">
                        </label>
                        <label class="text-sm">
                            <span class="text-xs font-semibold text-gray-500">Nhiệt độ max (°C)</span>
                            <input type="number" step="0.1" name="config[<?= $idPhong ?>][nhietDoMax]" value="<?= tdText(tdValue($item, 'nhietDoMax')) ?>" class="block w-full mt-1 text-sm rounded-md border-gray-300 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-200">
                        </label>
                        <label class="text-sm">
                            <span class="text-xs font-semibold text-gray-500">Độ ẩm min (%)</span>
                            <input type="number" step="0.1" name="config[<?= $idPhong ?>][doAmMin]" value="<?= tdText(tdValue($item, 'doAmMin')) ?>" class="block w-full mt-1 text-sm rounded-md border-gray-300 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-200">
                        </label>
                        <label class="text-sm">
                            <span class="text-xs font-semibold text-gray-500">Độ ẩm max (%)</span>
                            <input type="number" step="0.1" name="config[<?= $idPhong ?>][doAmMax]" value="<?= tdText(tdValue($item, 'doAmMax')) ?>" class="block w-full mt-1 text-sm rounded-md border-gray-300 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-200">
                        </label>
                        <label class="text-sm">
                            <span class="text-xs font-semibold text-gray-500">Gas max (ppm)</span>
                            <input type="number" step="0.1" name="config[<?= $idPhong ?>][gasMax]" value="<?= tdText(tdValue($item, 'gasMax')) ?>" class="block w-full mt-1 text-sm rounded-md border-gray-300 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-200">
                        </label>
                        <label class="text-sm">
                            <span class="text-xs font-semibold text-gray-500">Khói max</span>
                            <input type="number" step="0.1" name="config[<?= $idPhong ?>][khoiBaoChay]" value="<?= tdText(tdValue($item, 'khoiBaoChay')) ?>" class="block w-full mt-1 text-sm rounded-md border-gray-300 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-200">
                        </label>
                    </div>

                    <label class="block mt-3 text-sm">
                        <span class="text-xs font-semibold text-gray-500">Ghi chú</span>
                        <input type="text" name="config[<?= $idPhong ?>][moTa]" value="<?= tdText(tdValue($item, 'moTa')) ?>" class="block w-full mt-1 text-sm rounded-md border-gray-300 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-200" placeholder="Ví dụ: Phòng có bếp gas">
                    </label>
                </section>
            <?php endforeach; ?>
        </div>
    </form>

    <div class="bg-white border border-gray-100 rounded-lg shadow-sm overflow-hidden dark:bg-gray-800 dark:border-gray-700">
        <div class="px-5 py-4 border-b border-gray-100 dark:border-gray-700 flex items-center justify-between">
            <div>
                <h2 class="text-sm font-bold uppercase tracking-wider text-gray-800 dark:text-gray-100">Danh sách kịch bản</h2>
                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Các kịch bản bật sẽ được Wokwi đồng bộ xuống trong lần gọi `sync` kế tiếp.</p>
            </div>
        </div>

        <div class="divide-y divide-gray-100 dark:divide-gray-700">
            <?php if (!empty($danhSachKichBan)): ?>
                <?php foreach ($danhSachKichBan as $item): ?>
                    <?php $active = (int) tdValue($item, 'kichHoat', 0) === 1; ?>
                    <article class="p-5 hover:bg-gray-50/80 dark:hover:bg-gray-700/30">
                        <div class="flex flex-col gap-4 xl:flex-row xl:items-start xl:justify-between">
                            <div class="min-w-0">
                                <div class="flex flex-wrap items-center gap-2">
                                    <h3 class="text-base font-semibold text-gray-900 dark:text-gray-100"><?= tdText(tdValue($item, 'tenKichBan', 'Kịch bản')) ?></h3>
                                    <span class="px-2 py-1 text-[11px] font-semibold rounded-md <?= $active ? 'bg-emerald-100 text-emerald-700' : 'bg-gray-100 text-gray-600' ?>">
                                        <?= $active ? 'Đang bật' : 'Đang tắt' ?>
                                    </span>
                                    <span class="px-2 py-1 text-[11px] font-semibold rounded-md bg-blue-50 text-blue-700">
                                        <?= tdText(tdValue($item, 'loaiHienThi', '')) ?>
                                    </span>
                                </div>

                                <div class="grid gap-3 mt-3 lg:grid-cols-2">
                                    <div class="p-3 rounded-lg bg-blue-50 border border-blue-100 dark:bg-blue-900/20 dark:border-blue-900/30">
                                        <p class="text-[11px] font-bold uppercase tracking-wide text-blue-700 dark:text-blue-300">Nếu</p>
                                        <p class="mt-1 text-sm text-gray-700 dark:text-gray-200"><?= tdText(tdValue($item, 'dieuKienHienThi', tdValue($item, 'dieuKien'))) ?></p>
                                    </div>
                                    <div class="p-3 rounded-lg bg-emerald-50 border border-emerald-100 dark:bg-emerald-900/20 dark:border-emerald-900/30">
                                        <p class="text-[11px] font-bold uppercase tracking-wide text-emerald-700 dark:text-emerald-300">Thì</p>
                                        <p class="mt-1 text-sm text-gray-700 dark:text-gray-200"><?= tdText(tdValue($item, 'hanhDongHienThi', tdValue($item, 'hanhDong'))) ?></p>
                                    </div>
                                </div>

                                <div class="flex flex-wrap gap-2 mt-3 text-[12px] text-gray-600 dark:text-gray-300">
                                    <span class="inline-flex items-center gap-1.5 px-2 py-1 bg-gray-100 rounded-md dark:bg-gray-700">
                                        <i class="fas fa-door-open text-gray-400"></i>
                                        Phòng: <?= tdText(tdValue($item, 'tenPhongApDung', 'Chưa gán')) ?>
                                    </span>
                                    <span class="inline-flex items-center gap-1.5 px-2 py-1 bg-gray-100 rounded-md dark:bg-gray-700">
                                        <i class="fas fa-repeat text-gray-400"></i>
                                        Chạy: <?= (int) tdValue($item, 'soLanDaChay', 0) ?> lần
                                    </span>
                                    <span class="inline-flex items-center gap-1.5 px-2 py-1 bg-gray-100 rounded-md dark:bg-gray-700">
                                        <i class="far fa-calendar text-gray-400"></i>
                                        <?= tdText(tdValue($item, 'ngayApDungLabels', 'Mỗi ngày')) ?>
                                    </span>
                                </div>
                            </div>

                            <div class="flex items-center gap-2">
                                <a href="index.php?page=tudong_toggle&id=<?= (int) tdValue($item, 'idKichBan', 0) ?>" class="inline-flex items-center justify-center w-10 h-10 rounded-md <?= $active ? 'bg-gray-100 text-gray-600 hover:bg-gray-200' : 'bg-emerald-50 text-emerald-600 hover:bg-emerald-100' ?>" title="<?= $active ? 'Tắt kịch bản' : 'Bật kịch bản' ?>">
                                    <i class="fas <?= $active ? 'fa-toggle-on' : 'fa-toggle-off' ?>"></i>
                                </a>
                                <a href="index.php?page=tudong_sua&id=<?= (int) tdValue($item, 'idKichBan', 0) ?>" class="inline-flex items-center justify-center w-10 h-10 text-blue-600 bg-blue-50 rounded-md hover:bg-blue-100" title="Sửa">
                                    <i class="fas fa-pen"></i>
                                </a>
                                <button type="button" onclick="confirmDeleteTuDong('<?= (int) tdValue($item, 'idKichBan', 0) ?>')" class="inline-flex items-center justify-center w-10 h-10 text-red-600 bg-red-50 rounded-md hover:bg-red-100" title="Xóa">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </div>
                    </article>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="min-h-[260px] flex flex-col items-center justify-center text-center text-gray-400">
                    <i class="fas fa-inbox text-3xl mb-3"></i>
                    <p class="text-sm font-semibold">Chưa có kịch bản tự động nào.</p>
                    <a href="index.php?page=tudong_them" class="mt-3 text-sm font-semibold text-blue-600 hover:text-blue-700">Tạo kịch bản đầu tiên</a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
    function confirmDeleteTuDong(id) {
        if (confirm("Bạn có chắc chắn muốn xóa kịch bản này không?")) {
            window.location.href = "index.php?page=tudong_delete&id=" + encodeURIComponent(id);
        }
    }
</script>

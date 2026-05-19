<?php
$danhSachCanhBao = $danhSachCanhBao ?? [];

if (!function_exists('alertValue')) {
    function alertValue($item, $field, $default = '') {
        return is_array($item) ? ($item[$field] ?? $default) : $default;
    }
}

if (!function_exists('alertText')) {
    function alertText($value) {
        return htmlspecialchars((string) ($value ?? ''), ENT_QUOTES, 'UTF-8');
    }
}

if (!function_exists('alertDate')) {
    function alertDate($value, $format = 'H:i:s - d/m/Y') {
        if (empty($value)) {
            return 'N/A';
        }

        $timestamp = strtotime((string) $value);
        return $timestamp ? date($format, $timestamp) : 'N/A';
    }
}

if (!function_exists('alertSeverity')) {
    function alertSeverity($level) {
        $level = strtolower(trim((string) $level));

        if (in_array($level, ['critical', 'danger', 'nghiem trong', 'nguy hiem'], true)) {
            return ['label' => 'Nguy hiểm', 'badge' => 'bg-red-100 text-red-700', 'border' => 'border-red-500'];
        }

        if (in_array($level, ['warning', 'canh bao'], true)) {
            return ['label' => 'Cảnh báo', 'badge' => 'bg-amber-100 text-amber-700', 'border' => 'border-amber-500'];
        }

        return ['label' => 'Thông tin', 'badge' => 'bg-blue-100 text-blue-700', 'border' => 'border-blue-500'];
    }
}

if (!function_exists('alertStatusMeta')) {
    function alertStatusMeta($status) {
        $status = (int) $status;
        if ($status === 2) {
            return ['label' => 'Đã xử lý', 'badge' => 'bg-emerald-100 text-emerald-700'];
        }

        if ($status === 1) {
            return ['label' => 'Đã xác nhận', 'badge' => 'bg-blue-100 text-blue-700'];
        }

        return ['label' => 'Chưa xử lý', 'badge' => 'bg-amber-100 text-amber-700'];
    }
}

$keyword = trim($_GET['q'] ?? '');
$mucDoFilter = trim($_GET['muc_do'] ?? '');
$trangThaiFilter = trim($_GET['trang_thai'] ?? '');
$ngayFilter = trim($_GET['ngay'] ?? '');

$tongCanhBao = count($danhSachCanhBao);
$dangMo = count(array_filter($danhSachCanhBao, fn($item) => (int) alertValue($item, 'trangThaiHoatDong', 0) === 1));
$daXuLy = count(array_filter($danhSachCanhBao, fn($item) => (int) alertValue($item, 'trangThaiXuLy', 0) === 2));
$choXuLy = count(array_filter($danhSachCanhBao, fn($item) => (int) alertValue($item, 'trangThaiXuLy', 0) === 0));

$danhSachDaLoc = array_values(array_filter($danhSachCanhBao, function ($item) use ($keyword, $mucDoFilter, $trangThaiFilter, $ngayFilter) {
    $haystack = strtolower(
        implode(' ', [
            alertValue($item, 'tenPhong', ''),
            alertValue($item, 'tenCamBien', ''),
            alertValue($item, 'tenCanhBao', ''),
            alertValue($item, 'noiDung', ''),
            alertValue($item, 'mucDo', ''),
        ])
    );

    if ($keyword !== '' && strpos($haystack, strtolower($keyword)) === false) {
        return false;
    }

    if ($mucDoFilter !== '' && alertValue($item, 'mucDo', '') !== $mucDoFilter) {
        return false;
    }

    if ($trangThaiFilter !== '' && (string) alertValue($item, 'trangThaiXuLy', '') !== $trangThaiFilter) {
        return false;
    }

    if ($ngayFilter !== '') {
        $time = strtotime((string) alertValue($item, 'ngayTao', ''));
        if (!$time || date('Y-m-d', $time) !== $ngayFilter) {
            return false;
        }
    }

    return true;
}));
?>

<div class="py-6 space-y-6">
    <div class="flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
        <div>
            <p class="text-sm font-semibold text-red-600">Cảnh báo hệ thống</p>
            <h1 class="mt-1 text-2xl font-bold text-gray-900 dark:text-gray-100">Nhật ký cảnh báo từ ngưỡng và Wokwi</h1>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Các cảnh báo được tạo khi cảm biến gửi dữ liệu vượt ngưỡng đã lưu trong bảng `cau_hinh` của từng phòng.</p>
        </div>

        <?php if ($tongCanhBao > 0): ?>
            <button type="button" onclick="confirmClearLogs()" class="inline-flex items-center px-4 py-2 text-sm font-semibold text-red-700 bg-red-50 border border-red-100 rounded-md hover:bg-red-100">
                <i class="fas fa-trash-alt mr-2"></i> Xóa toàn bộ
            </button>
        <?php endif; ?>
    </div>

    <div class="grid gap-4 md:grid-cols-3">
        <div class="p-5 bg-white border border-gray-100 rounded-lg shadow-sm dark:bg-gray-800 dark:border-gray-700">
            <p class="text-sm text-gray-500 dark:text-gray-400">Tổng cảnh báo</p>
            <p class="mt-2 text-3xl font-bold text-gray-900 dark:text-gray-100"><?= $tongCanhBao ?></p>
        </div>
        <div class="p-5 bg-white border border-gray-100 rounded-lg shadow-sm dark:bg-gray-800 dark:border-gray-700">
            <p class="text-sm text-gray-500 dark:text-gray-400">Đang mở</p>
            <p class="mt-2 text-3xl font-bold text-red-600"><?= $dangMo ?></p>
        </div>
        <div class="p-5 bg-white border border-gray-100 rounded-lg shadow-sm dark:bg-gray-800 dark:border-gray-700">
            <p class="text-sm text-gray-500 dark:text-gray-400">Đã xử lý</p>
            <p class="mt-2 text-3xl font-bold text-emerald-600"><?= $daXuLy ?></p>
        </div>
    </div>

    <form method="get" action="index.php" class="p-4 bg-white border border-gray-100 rounded-lg shadow-sm dark:bg-gray-800 dark:border-gray-700">
        <input type="hidden" name="page" value="alert_log">
        <div class="grid gap-3 lg:grid-cols-[minmax(220px,1fr)_180px_170px_170px_auto]">
            <input name="q" value="<?= alertText($keyword) ?>" type="text" class="w-full px-3 py-2.5 text-sm bg-gray-50 border border-gray-200 rounded-md dark:bg-gray-700 dark:border-gray-600" placeholder="Tìm phòng, cảm biến, nội dung...">

            <select name="muc_do" class="px-3 py-2.5 text-sm bg-gray-50 border border-gray-200 rounded-md dark:bg-gray-700 dark:border-gray-600">
                <option value="">Tất cả mức độ</option>
                <option value="warning" <?= $mucDoFilter === 'warning' ? 'selected' : '' ?>>Cảnh báo</option>
                <option value="critical" <?= $mucDoFilter === 'critical' ? 'selected' : '' ?>>Nguy hiểm</option>
                <option value="info" <?= $mucDoFilter === 'info' ? 'selected' : '' ?>>Thông tin</option>
            </select>

            <select name="trang_thai" class="px-3 py-2.5 text-sm bg-gray-50 border border-gray-200 rounded-md dark:bg-gray-700 dark:border-gray-600">
                <option value="">Tất cả trạng thái</option>
                <option value="0" <?= $trangThaiFilter === '0' ? 'selected' : '' ?>>Chưa xử lý</option>
                <option value="1" <?= $trangThaiFilter === '1' ? 'selected' : '' ?>>Đã xác nhận</option>
                <option value="2" <?= $trangThaiFilter === '2' ? 'selected' : '' ?>>Đã xử lý</option>
            </select>

            <input name="ngay" value="<?= alertText($ngayFilter) ?>" type="date" class="px-3 py-2.5 text-sm bg-gray-50 border border-gray-200 rounded-md dark:bg-gray-700 dark:border-gray-600">

            <div class="flex gap-2">
                <button type="submit" class="inline-flex items-center justify-center px-4 py-2.5 text-sm font-semibold text-white bg-blue-600 rounded-md hover:bg-blue-700">
                    <i class="fas fa-filter mr-2"></i> Lọc
                </button>
                <a href="index.php?page=alert_log" class="inline-flex items-center justify-center px-4 py-2.5 text-sm font-semibold text-gray-600 bg-gray-100 rounded-md hover:bg-gray-200">
                    <i class="fas fa-rotate-right"></i>
                </a>
            </div>
        </div>
    </form>

    <div class="p-4 text-sm text-slate-700 bg-slate-50 border border-slate-100 rounded-lg dark:bg-gray-800 dark:border-gray-700 dark:text-gray-300">
        <div class="flex items-start gap-3">
            <i class="fas fa-circle-info mt-0.5 text-slate-500"></i>
            <div>
                <p class="font-semibold">Trạng thái xử lý</p>
                <p class="mt-1 text-xs leading-5">`Chưa xử lý` là cảnh báo mới. `Đã xác nhận` là đã có người kiểm tra. `Đã xử lý` là đã đóng cảnh báo hoặc giá trị đã về lại ngưỡng an toàn.</p>
            </div>
        </div>
    </div>

    <div class="bg-white border border-gray-100 rounded-lg shadow-sm overflow-hidden dark:bg-gray-800 dark:border-gray-700">
        <div class="px-5 py-4 border-b border-gray-100 dark:border-gray-700 flex items-center justify-between">
            <h2 class="text-sm font-bold uppercase tracking-wider text-gray-800 dark:text-gray-100">Danh sách cảnh báo</h2>
            <span class="text-xs font-medium text-gray-500 dark:text-gray-400">Hiển thị <?= count($danhSachDaLoc) ?> / <?= $tongCanhBao ?> bản ghi</span>
        </div>

        <div class="divide-y divide-gray-100 dark:divide-gray-700 max-h-[calc(100vh-320px)] overflow-y-auto">
            <?php if (!empty($danhSachDaLoc)): ?>
                <?php foreach ($danhSachDaLoc as $item): ?>
                    <?php
                    $id = (int) alertValue($item, 'idCanhBao', 0);
                    $severity = alertSeverity(alertValue($item, 'mucDo', 'warning'));
                    $status = alertStatusMeta(alertValue($item, 'trangThaiXuLy', 0));
                    $isActive = (int) alertValue($item, 'trangThaiHoatDong', 0) === 1;
                    ?>
                    <article class="p-5 border-l-4 <?= $severity['border'] ?> hover:bg-gray-50/80 dark:hover:bg-gray-700/30">
                        <div class="flex flex-col gap-4 xl:flex-row xl:items-start xl:justify-between">
                            <div class="min-w-0">
                                <div class="flex flex-wrap items-center gap-2">
                                    <span class="px-2 py-1 text-[11px] font-semibold rounded-md <?= $severity['badge'] ?>"><?= alertText($severity['label']) ?></span>
                                    <span class="px-2 py-1 text-[11px] font-semibold rounded-md <?= $status['badge'] ?>"><?= alertText($status['label']) ?></span>
                                    <?php if ($isActive): ?>
                                        <span class="px-2 py-1 text-[11px] font-semibold rounded-md bg-red-50 text-red-600">Đang hoạt động</span>
                                    <?php endif; ?>
                                    <span class="text-[11px] text-gray-400">ID #<?= $id ?></span>
                                </div>

                                <h3 class="mt-2 text-[15px] font-semibold leading-6 text-gray-900 dark:text-gray-100"><?= alertText(alertValue($item, 'tenCanhBao', 'Cảnh báo hệ thống')) ?></h3>
                                <p class="mt-1 text-sm text-gray-600 dark:text-gray-300"><?= alertText(alertValue($item, 'noiDung', 'Không có nội dung')) ?></p>

                                <div class="flex flex-wrap gap-2 mt-3 text-[12px] text-gray-600 dark:text-gray-300">
                                    <span class="inline-flex items-center gap-1.5 px-2 py-1 bg-gray-100 rounded-md dark:bg-gray-700">
                                        <i class="fas fa-door-open text-gray-400"></i> <?= alertText(alertValue($item, 'tenPhong', 'Chưa rõ phòng')) ?>
                                    </span>
                                    <span class="inline-flex items-center gap-1.5 px-2 py-1 bg-gray-100 rounded-md dark:bg-gray-700">
                                        <i class="fas fa-microchip text-gray-400"></i> <?= alertText(alertValue($item, 'tenCamBien', 'Cảm biến')) ?>
                                    </span>
                                    <span class="inline-flex items-center gap-1.5 px-2 py-1 bg-gray-100 rounded-md dark:bg-gray-700">
                                        <i class="fas fa-gauge-high text-gray-400"></i> Giá trị: <?= alertText(alertValue($item, 'giaTriDo', 'N/A')) ?>
                                    </span>
                                    <span class="inline-flex items-center gap-1.5 px-2 py-1 bg-gray-100 rounded-md dark:bg-gray-700">
                                        <i class="fas fa-sliders text-gray-400"></i> Ngưỡng: <?= alertText(alertValue($item, 'nguongViPham', 'N/A')) ?>
                                    </span>
                                    <span class="inline-flex items-center gap-1.5 px-2 py-1 bg-gray-100 rounded-md dark:bg-gray-700">
                                        <i class="far fa-clock text-gray-400"></i> <?= alertDate(alertValue($item, 'ngayTao')) ?>
                                    </span>
                                </div>
                            </div>

                            <div class="flex gap-2">
                                <?php if ((int) alertValue($item, 'trangThaiXuLy', 0) === 0): ?>
                                    <a href="index.php?page=canhbao_xacnhan&id=<?= $id ?>" class="inline-flex items-center justify-center w-10 h-10 text-blue-600 bg-blue-50 border border-blue-100 rounded-md hover:bg-blue-100" title="Xác nhận">
                                        <i class="fas fa-check"></i>
                                    </a>
                                <?php endif; ?>
                                <?php if ($isActive): ?>
                                    <a href="index.php?page=canhbao_giaiquyet&id=<?= $id ?>" class="inline-flex items-center justify-center w-10 h-10 text-emerald-600 bg-emerald-50 border border-emerald-100 rounded-md hover:bg-emerald-100" title="Đóng cảnh báo">
                                        <i class="fas fa-shield-check"></i>
                                    </a>
                                <?php endif; ?>
                                <button type="button" onclick="confirmDeleteLog('<?= $id ?>')" class="inline-flex items-center justify-center w-10 h-10 text-red-600 bg-red-50 border border-red-100 rounded-md hover:bg-red-100" title="Xóa">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </div>
                    </article>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="flex flex-col items-center justify-center min-h-[330px] text-center text-gray-400">
                    <div class="p-5 mb-4 rounded-full bg-gray-50 dark:bg-gray-700">
                        <i class="fas fa-inbox text-3xl text-gray-300"></i>
                    </div>
                    <p class="text-sm font-semibold text-gray-600 dark:text-gray-300">Không có cảnh báo phù hợp.</p>
                    <p class="mt-1 text-xs text-gray-400">Hãy đổi bộ lọc hoặc kiểm tra dữ liệu cảm biến từ Wokwi.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
    function confirmClearLogs() {
        if (confirm("Bạn có chắc chắn muốn xóa toàn bộ lịch sử cảnh báo không?")) {
            window.location.href = "index.php?page=canhbao_xoa_tatca";
        }
    }

    function confirmDeleteLog(id) {
        if (confirm("Bạn có chắc chắn muốn xóa cảnh báo #" + id + " không?")) {
            window.location.href = "index.php?page=canhbao_xoa&id=" + encodeURIComponent(id);
        }
    }
</script>

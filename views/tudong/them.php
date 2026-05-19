<?php
$danhSachPhong = $danhSachPhong ?? [];

if (!function_exists('tdFormText')) {
    function tdFormText($value) {
        return htmlspecialchars((string) ($value ?? ''), ENT_QUOTES, 'UTF-8');
    }
}

$days = [
    'mon' => 'T2',
    'tue' => 'T3',
    'wed' => 'T4',
    'thu' => 'T5',
    'fri' => 'T6',
    'sat' => 'T7',
    'sun' => 'CN',
];
?>

<div class="flex flex-col items-start justify-between w-full gap-4 my-6 sm:flex-row sm:items-center">
    <h2 class="text-2xl font-semibold text-gray-700 dark:text-gray-200">Thêm kịch bản tự động</h2>
    <a href="index.php?page=tudong" class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 dark:bg-gray-800 dark:text-gray-300 dark:border-gray-600">
        <i class="fas fa-arrow-left mr-2"></i> Quay lại
    </a>
</div>

<div class="w-full max-w-4xl mx-auto mb-8">
    <div class="p-6 bg-white rounded-lg shadow-sm dark:bg-gray-800 border border-gray-200 dark:border-gray-700">
        <form action="index.php?page=tudong_store" method="POST" class="space-y-6">
            <div class="grid gap-4 md:grid-cols-2">
                <label class="block text-sm md:col-span-2">
                    <span class="text-gray-700 dark:text-gray-400 font-bold uppercase text-[10px] tracking-widest">1. Tên kịch bản</span>
                    <input type="text" name="tenKichBan" required class="block w-full mt-1 text-sm dark:bg-gray-700 dark:text-gray-300 form-input p-2.5 border rounded-lg" placeholder="Ví dụ: Bật còi khi phát hiện gas">
                </label>

                <label class="block text-sm md:col-span-2">
                    <span class="text-gray-700 dark:text-gray-400 font-bold uppercase text-[10px] tracking-widest">2. Mô tả</span>
                    <textarea name="moTa" rows="2" class="block w-full mt-1 text-sm dark:bg-gray-700 dark:text-gray-300 form-textarea p-2.5 border rounded-lg" placeholder="Mô tả ngắn để dễ quản lý"></textarea>
                </label>
            </div>

            <div class="p-4 rounded-lg border border-gray-200 dark:border-gray-700">
                <div class="flex flex-col gap-4 lg:flex-row lg:items-start">
                    <div class="lg:w-48">
                        <span class="text-gray-700 dark:text-gray-400 font-bold uppercase text-[10px] tracking-widest">3. Phòng áp dụng</span>
                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Một kịch bản có thể gán cho nhiều phòng.</p>
                    </div>
                    <div class="grid flex-1 gap-3 sm:grid-cols-2 xl:grid-cols-3">
                        <?php foreach ($danhSachPhong as $phong): ?>
                            <?php $idPhong = (int) ($phong['idPhong'] ?? 0); ?>
                            <label class="flex items-start gap-3 p-3 border rounded-lg cursor-pointer hover:border-blue-400 dark:border-gray-600">
                                <input type="checkbox" name="phongIds[]" value="<?= $idPhong ?>" class="mt-1 rounded text-blue-600">
                                <span>
                                    <span class="block font-semibold text-gray-800 dark:text-gray-100"><?= tdFormText($phong['tenPhong'] ?? '') ?></span>
                                    <span class="block text-xs text-gray-500 dark:text-gray-400">
                                        <?= (int) ($phong['cheDoTuDong'] ?? 0) === 1 ? 'Đang ở chế độ tự động' : 'Đang ở chế độ thủ công' ?>
                                    </span>
                                </span>
                            </label>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>

            <div class="p-4 bg-gray-50 rounded-lg border border-gray-200 dark:bg-gray-700/30 dark:border-gray-600">
                <div class="flex flex-wrap items-center gap-3 mb-4">
                    <span class="text-gray-700 dark:text-gray-400 font-bold uppercase text-[10px] tracking-widest">4. Loại điều kiện</span>
                    <button type="button" id="event-tab" onclick="switchMode('event')" class="px-3 py-2 text-sm font-semibold text-blue-600 border border-blue-200 bg-blue-50 rounded-lg">Theo cảm biến</button>
                    <button type="button" id="schedule-tab" onclick="switchMode('schedule')" class="px-3 py-2 text-sm font-semibold text-gray-600 border border-gray-200 bg-white rounded-lg">Theo lịch</button>
                </div>

                <input type="hidden" name="loaiKichBan" id="loaiKichBan" value="event">

                <div id="event-panel" class="grid gap-4 md:grid-cols-3">
                    <label class="block text-sm">
                        <span class="text-xs font-semibold text-gray-500">Cảm biến</span>
                        <select name="camBien" class="block w-full mt-1 text-sm rounded-lg border-gray-300 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-200">
                            <option value="temp">Nhiệt độ</option>
                            <option value="hum">Độ ẩm</option>
                            <option value="gas">Khí gas</option>
                            <option value="pir">PIR</option>
                        </select>
                    </label>
                    <label class="block text-sm">
                        <span class="text-xs font-semibold text-gray-500">Toán tử</span>
                        <select name="toanTu" class="block w-full mt-1 text-sm rounded-lg border-gray-300 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-200">
                            <option value=">">Lớn hơn</option>
                            <option value="<">Nhỏ hơn</option>
                            <option value="=">Bằng</option>
                        </select>
                    </label>
                    <label class="block text-sm">
                        <span class="text-xs font-semibold text-gray-500">Giá trị kích hoạt</span>
                        <input type="number" step="0.1" name="giaTriKichHoat" class="block w-full mt-1 text-sm rounded-lg border-gray-300 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-200" placeholder="35">
                    </label>
                </div>

                <div id="schedule-panel" class="hidden space-y-4">
                    <div class="grid gap-4 md:grid-cols-2">
                        <label class="block text-sm">
                            <span class="text-xs font-semibold text-gray-500">Giờ bắt đầu</span>
                            <input type="time" name="gioBatDau" value="18:00" class="block w-full mt-1 text-sm rounded-lg border-gray-300 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-200">
                        </label>
                        <label class="block text-sm">
                            <span class="text-xs font-semibold text-gray-500">Giờ kết thúc</span>
                            <input type="time" name="gioKetThuc" value="06:00" class="block w-full mt-1 text-sm rounded-lg border-gray-300 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-200">
                        </label>
                    </div>

                    <div>
                        <span class="text-xs font-semibold text-gray-500">Ngày áp dụng</span>
                        <div class="flex flex-wrap gap-2 mt-2">
                            <?php foreach ($days as $value => $label): ?>
                                <label class="cursor-pointer">
                                    <input name="ngayApDung[]" value="<?= tdFormText($value) ?>" type="checkbox" class="sr-only peer" <?= in_array($value, ['mon', 'tue', 'wed', 'thu', 'fri'], true) ? 'checked' : '' ?>>
                                    <div class="w-10 h-10 flex items-center justify-center text-xs font-bold border-2 rounded-lg peer-checked:bg-blue-600 peer-checked:text-white peer-checked:border-blue-600 dark:border-gray-600 transition-all"><?= tdFormText($label) ?></div>
                                </label>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>

            <div class="p-4 bg-emerald-50/70 rounded-lg border border-emerald-100 dark:bg-emerald-900/10 dark:border-emerald-900/30">
                <h3 class="text-sm font-bold text-emerald-700 dark:text-emerald-300 uppercase tracking-widest">5. Hành động</h3>
                <div class="grid gap-4 mt-4 md:grid-cols-3">
                    <label class="block text-sm">
                        <span class="text-xs font-semibold text-gray-500">Thiết bị/đích tác động</span>
                        <select name="thietBi" class="block w-full mt-1 text-sm rounded-lg border-gray-300 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-200">
                            <option value="relay">Relay</option>
                            <option value="buzzer">Còi báo động</option>
                            <option value="fan">Quạt</option>
                            <option value="all">Tất cả thiết bị</option>
                        </select>
                    </label>
                    <label class="block text-sm">
                        <span class="text-xs font-semibold text-gray-500">Lệnh</span>
                        <select name="lenhThucThi" class="block w-full mt-1 text-sm rounded-lg border-gray-300 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-200">
                            <option value="ON">Bật</option>
                            <option value="OFF">Tắt</option>
                        </select>
                    </label>
                    <label class="block text-sm">
                        <span class="text-xs font-semibold text-gray-500">Trễ thực thi (giây)</span>
                        <input type="number" min="0" step="1" name="thoiGianChoChay" value="0" class="block w-full mt-1 text-sm rounded-lg border-gray-300 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-200">
                    </label>
                </div>

                <label class="inline-flex items-center mt-4 text-sm text-gray-700 dark:text-gray-300">
                    <input name="kichHoat" type="checkbox" class="w-4 h-4 mr-2 text-blue-600 rounded" checked>
                    Kích hoạt ngay sau khi tạo
                </label>
            </div>

            <div class="flex justify-end gap-3 pt-6 border-t dark:border-gray-700">
                <a href="index.php?page=tudong" class="px-6 py-2.5 text-sm font-medium text-gray-600 bg-gray-100 rounded-lg hover:bg-gray-200 transition">Hủy</a>
                <button type="submit" class="px-8 py-2.5 text-sm font-bold text-white bg-blue-600 rounded-lg hover:bg-blue-700 shadow-lg transition">
                    <i class="fas fa-plus-circle mr-2"></i> Tạo kịch bản
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function switchMode(mode) {
        const eventTab = document.getElementById("event-tab");
        const scheduleTab = document.getElementById("schedule-tab");
        const eventPanel = document.getElementById("event-panel");
        const schedulePanel = document.getElementById("schedule-panel");
        const hiddenMode = document.getElementById("loaiKichBan");

        hiddenMode.value = mode;

        const isEvent = mode === "event";
        eventPanel.classList.toggle("hidden", !isEvent);
        schedulePanel.classList.toggle("hidden", isEvent);

        eventTab.className = isEvent
            ? "px-3 py-2 text-sm font-semibold text-blue-600 border border-blue-200 bg-blue-50 rounded-lg"
            : "px-3 py-2 text-sm font-semibold text-gray-600 border border-gray-200 bg-white rounded-lg";
        scheduleTab.className = !isEvent
            ? "px-3 py-2 text-sm font-semibold text-blue-600 border border-blue-200 bg-blue-50 rounded-lg"
            : "px-3 py-2 text-sm font-semibold text-gray-600 border border-gray-200 bg-white rounded-lg";
    }
</script>

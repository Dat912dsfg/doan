<?php $phong = $phong ?? []; ?>

<div class="flex flex-col items-start justify-between w-full gap-4 my-6 sm:flex-row sm:items-center">
    <h2 class="text-2xl font-semibold text-gray-700 dark:text-gray-200">
        Chỉnh sửa thông tin phòng
    </h2>
    <a href="index.php?page=thietbi"
       class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 transition-colors duration-150 bg-white border border-gray-300 rounded-lg hover:text-gray-800 hover:border-gray-400 dark:bg-gray-800 dark:text-gray-300 dark:border-gray-600">
        <i class="fas fa-arrow-left mr-2"></i> Quay lại
    </a>
</div>

<div class="w-full max-w-4xl mx-auto mb-8">
    <div class="p-6 bg-white rounded-xl shadow-xs dark:bg-gray-800 border border-gray-200 dark:border-gray-700">
        <form action="index.php?page=khuvuc_update" method="POST" class="space-y-6">
            <input type="hidden" name="idPhong" value="<?= intval($phong['idPhong'] ?? 0) ?>">

            <div class="grid gap-6 md:grid-cols-2">
                <label class="block text-sm">
                    <span class="text-gray-700 dark:text-gray-300 font-semibold">Tên phòng</span>
                    <input type="text" name="tenPhong" required class="form-input mt-1" value="<?= htmlspecialchars($phong['tenPhong'] ?? '') ?>">
                </label>

                <label class="block text-sm">
                    <span class="text-gray-700 dark:text-gray-300 font-semibold">Loại phòng</span>
                    <input type="text" name="loaiPhong" class="form-input mt-1" value="<?= htmlspecialchars($phong['loaiPhong'] ?? '') ?>">
                </label>

                <label class="block text-sm">
                    <span class="text-gray-700 dark:text-gray-300 font-semibold">Vị trí</span>
                    <input type="text" name="viTri" class="form-input mt-1" value="<?= htmlspecialchars($phong['viTri'] ?? '') ?>">
                </label>

                <label class="block text-sm">
                    <span class="text-gray-700 dark:text-gray-300 font-semibold">Diện tích (m²)</span>
                    <input type="number" step="0.1" name="dienTich" class="form-input mt-1" value="<?= htmlspecialchars($phong['dienTich'] ?? '') ?>">
                </label>

                <label class="block text-sm">
                    <span class="text-gray-700 dark:text-gray-300 font-semibold">Trạng thái phòng</span>
                    <select name="trangThai" class="form-select mt-1">
                        <option value="1" <?= intval($phong['trangThai'] ?? 0) === 1 ? 'selected' : '' ?>>Đang sử dụng</option>
                        <option value="0" <?= intval($phong['trangThai'] ?? 0) === 0 ? 'selected' : '' ?>>Tạm khóa</option>
                    </select>
                </label>

                <label class="block text-sm">
                    <span class="text-gray-700 dark:text-gray-300 font-semibold">Chế độ phòng</span>
                    <select name="cheDoTuDong" class="form-select mt-1">
                        <option value="0" <?= intval($phong['cheDoTuDong'] ?? 0) === 0 ? 'selected' : '' ?>>Thủ công</option>
                        <option value="1" <?= intval($phong['cheDoTuDong'] ?? 0) === 1 ? 'selected' : '' ?>>Tự động</option>
                    </select>
                </label>

                <label class="block text-sm md:col-span-2">
                    <span class="text-gray-700 dark:text-gray-300 font-semibold">Mô tả</span>
                    <textarea name="moTa" rows="4" class="form-textarea mt-1"><?= htmlspecialchars($phong['moTa'] ?? '') ?></textarea>
                </label>
            </div>

            <div class="flex justify-end gap-3 pt-4 border-t dark:border-gray-700">
                <a href="index.php?page=thietbi" class="px-5 py-2.5 text-sm font-medium text-gray-600 bg-gray-100 rounded-lg hover:bg-gray-200 dark:bg-gray-700 dark:text-gray-300">
                    Hủy
                </a>
                <button type="submit" class="px-6 py-2.5 text-sm font-bold text-white bg-blue-600 rounded-lg hover:bg-blue-700">
                    <i class="fas fa-save mr-2"></i> Lưu thay đổi
                </button>
            </div>
        </form>
    </div>
</div>

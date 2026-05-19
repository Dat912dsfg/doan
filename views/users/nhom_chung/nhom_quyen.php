<div class="w-full overflow-hidden rounded-lg shadow-xs border border-gray-200 dark:border-gray-700">
    <div class="w-full overflow-x-auto">
        <table class="w-full whitespace-no-wrap">
            <thead>
                <tr class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase border-b dark:border-gray-700 bg-gray-50 dark:text-gray-400 dark:bg-gray-800">
                    <th class="px-4 py-3 w-12 text-center">
                        <label class="flex items-center justify-center cursor-pointer">
                            <input type="checkbox" onclick="this.checked ? checkAllPermissions() : uncheckAllPermissions()" 
                                   class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                        </label>
                    </th>
                    <th class="px-4 py-3">Mã quyền hạn</th>
                    <th class="px-4 py-3">Tên chức năng</th>
                    <th class="px-4 py-3 text-center">Cho phép</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800">
                <?php if (!empty($quyen)): ?>
                    <?php foreach ($quyen as $item): ?>
                        <tr class="text-gray-700 dark:text-gray-400 hover:bg-blue-50 dark:hover:bg-gray-700 transition-colors">
                            <td class="px-4 py-3 text-center">
                                <i class="fas fa-shield-alt text-gray-300 text-xs"></i>
                            </td>
                            <td class="px-4 py-3 text-sm">
                                <span class="px-2 py-1 font-mono text-xs font-bold bg-blue-50 text-blue-600 rounded border border-blue-100 dark:bg-blue-900/20 dark:text-blue-400 dark:border-blue-800">
                                    <?= htmlspecialchars($item->maQuyen) ?>
                                </span>
                            </td>
                            <td class="px-4 py-3 text-sm font-medium">
                                <?= htmlspecialchars($item->tenQuyen) ?>
                            </td>
                            <td class="px-4 py-3 text-center">
                                <label class="inline-flex items-center justify-center cursor-pointer">
                                    <input type="checkbox" name="permissions[]" value="<?= $item->idQuyen ?>" 
                                           class="perm-checkbox w-5 h-5 text-blue-600 border-gray-300 rounded focus:ring-blue-500" 
                                           <?= (isset($quyenNhom) && in_array($item->idQuyen, $quyenNhom)) ? 'checked' : '' ?>>
                                </label>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="4" class="px-4 py-8 text-center text-gray-500 italic">
                            <i class="fas fa-info-circle mr-2"></i> Không có dữ liệu quyền hạn được tìm thấy.
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
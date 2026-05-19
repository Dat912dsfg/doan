<div class="grid gap-6 mb-8 md:grid-cols-12">
    <?php if (isset($isEdit) && $isEdit === true): ?>
        <input type="hidden" name="idQuyen" value="<?= $quyen->idQuyen ?>">
    <?php endif; ?>

    <div class="md:col-span-7">
        <div class="min-w-0 p-4 bg-white rounded-lg shadow-xs dark:bg-gray-800 border border-gray-200 dark:border-gray-700 h-full">
            <div class="flex items-center mb-4 pb-3 border-b border-gray-200 dark:border-gray-700">
                <h4 class="font-semibold text-gray-700 dark:text-gray-300">Thông tin quyền hạn</h4>
            </div>
            
            <div class="space-y-4">
                <label class="block text-sm">
                    <span class="text-gray-700 dark:text-gray-400 font-medium">Mã quyền <span class="text-blue-600">*</span></span>
                    <div class="relative mt-1">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                            <i class="fas fa-code text-gray-400"></i>
                        </div>
                        <?php if (isset($isEdit) && $isEdit === true): ?>
                            <input type="text" 
                                   value="<?= $quyen->maQuyen ?>" 
                                   class="block w-full pl-10 pr-3 text-sm dark:bg-gray-700 dark:text-gray-300 form-input bg-gray-100 dark:bg-gray-600 cursor-not-allowed opacity-75 font-mono" 
                                   disabled readonly>
                            <input type="hidden" name="maQuyen" value="<?= $quyen->maQuyen ?>">
                        <?php else: ?>
                            <input type="text" 
                                   id="permissionKey"
                                   name="maQuyen" 
                                   class="block w-full pl-10 pr-3 text-sm dark:bg-gray-700 dark:text-gray-300 form-input focus:border-blue-400 focus:shadow-outline-blue font-mono" 
                                   placeholder="module.action" requiblue>
                        <?php endif; ?>
                    </div>
                    <?php if (isset($isEdit) && $isEdit === true): ?>
                        <span class="text-xs text-gray-500 mt-1"><i class="fas fa-lock mr-1"></i> Mã quyền không thể thay đổi sau khi tạo</span>
                    <?php else: ?>
                        <span class="text-xs text-blue-600 hidden mt-1" id="permissionKey_error"></span>
                        <span class="text-xs text-gray-500 mt-1" id="permissionKey_helper">
                            <i class="fas fa-info-circle mr-1"></i> Key dùng để check: <code class="bg-gray-100 dark:bg-gray-700 px-1 rounded">device.create</code>
                        </span>
                    <?php endif; ?>
                </label>

                <label class="block text-sm">
                    <span class="text-gray-700 dark:text-gray-400 font-medium">Tên hiển thị <span class="text-blue-600">*</span></span>
                    <input type="text" 
                           id="permissionName"
                           name="tenQuyen" 
                           value="<?= (isset($isEdit) && $isEdit === true) ? $quyen->tenQuyen : '' ?>"
                           class="block w-full mt-1 text-sm dark:bg-gray-700 dark:text-gray-300 form-input focus:border-blue-400 focus:shadow-outline-blue" 
                           placeholder="VD: Thêm thiết bị mới" requiblue>
                    <span class="text-xs text-blue-600 hidden mt-1" id="permissionName_error"></span>
                </label>
            </div>
        </div>
    </div>

    <div class="md:col-span-5">
        <?php include 'quyen_hd.php'; ?>
    </div>
</div>
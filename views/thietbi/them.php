<div class="flex flex-col items-start justify-between w-full gap-4 my-6 sm:flex-row sm:items-center">
    <h2 class="text-2xl font-semibold text-gray-700 dark:text-gray-200">
        Thêm node thiết bị mới
    </h2>
    <a href="index.php?page=thietbi"
       class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 transition-colors duration-150 bg-white border border-gray-300 rounded-lg hover:text-gray-800 hover:border-gray-400 dark:bg-gray-800 dark:text-gray-300 dark:border-gray-600">
        <i class="fas fa-arrow-left mr-2"></i> Quay lại
    </a>
</div>

<div class="w-full max-w-4xl mx-auto mb-8">
    <div class="p-6 bg-white rounded-xl shadow-xs dark:bg-gray-800 border border-gray-200 dark:border-gray-700">
        <form action="index.php?page=thietbi_store" method="POST" class="space-y-6">
            <div class="grid gap-6 md:grid-cols-2">
                <label class="block text-sm">
                    <span class="text-gray-700 dark:text-gray-300 font-semibold">Tên node</span>
                    <input type="text" name="tenThietBi" required class="form-input mt-1" placeholder="Ví dụ: ESP32 phòng 101">
                </label>

                <label class="block text-sm">
                    <span class="text-gray-700 dark:text-gray-300 font-semibold">Phòng gán thiết bị</span>
                    <select name="idPhong" required class="form-select mt-1">
                        <option value="">Chọn phòng</option>
                        <?php foreach (($danhSachPhong ?? []) as $phong): ?>
                            <option value="<?= intval($phong['idPhong']) ?>">
                                <?= htmlspecialchars($phong['tenPhong']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </label>

                <label class="block text-sm">
                    <span class="text-gray-700 dark:text-gray-300 font-semibold">Loại thiết bị</span>
                    <input type="text" name="loaiThietBi" class="form-input mt-1" placeholder="ESP32 / NodeMCU / Gateway">
                </label>

                <label class="block text-sm">
                    <span class="text-gray-700 dark:text-gray-300 font-semibold">Trạng thái kết nối</span>
                    <select name="trangThaiKetNoi" class="form-select mt-1">
                        <option value="1">Online</option>
                        <option value="0" selected>Offline</option>
                    </select>
                </label>

                <label class="block text-sm">
                    <span class="text-gray-700 dark:text-gray-300 font-semibold">Địa chỉ IP</span>
                    <input type="text" name="diaChiIp" class="form-input mt-1" placeholder="192.168.1.25">
                </label>

                <label class="block text-sm">
                    <span class="text-gray-700 dark:text-gray-300 font-semibold">MAC Address</span>
                    <input type="text" name="macAddress" class="form-input mt-1" placeholder="AA:BB:CC:DD:EE:FF">
                </label>

                <label class="block text-sm md:col-span-2">
                    <span class="text-gray-700 dark:text-gray-300 font-semibold">Topic MQTT</span>
                    <input type="text" name="topicMqtt" class="form-input mt-1" placeholder="nhatro123/room101">
                </label>

                <label class="block text-sm">
                    <span class="text-gray-700 dark:text-gray-300 font-semibold">Firmware Version</span>
                    <input type="text" name="firmwareVersion" class="form-input mt-1" placeholder="v1.0.0">
                </label>

                <label class="block text-sm">
                    <span class="text-gray-700 dark:text-gray-300 font-semibold">Heartbeat gần nhất</span>
                    <input type="datetime-local" name="lastHeartbeat" class="form-input mt-1">
                </label>
            </div>

            <div class="rounded-xl bg-blue-50 border border-blue-100 p-4 text-sm text-blue-700 dark:bg-blue-900/20 dark:border-blue-900 dark:text-blue-200">
                Mỗi node được gán cho một phòng trong dãy trọ để theo dõi đúng cảm biến, trạng thái kết nối và topic điều khiển.
            </div>

            <div class="flex justify-end gap-3 pt-4 border-t dark:border-gray-700">
                <a href="index.php?page=thietbi" class="px-5 py-2.5 text-sm font-medium text-gray-600 bg-gray-100 rounded-lg hover:bg-gray-200 dark:bg-gray-700 dark:text-gray-300">
                    Hủy
                </a>
                <button type="submit" class="px-6 py-2.5 text-sm font-bold text-white bg-blue-600 rounded-lg hover:bg-blue-700">
                    <i class="fas fa-plus-circle mr-2"></i> Thêm node
                </button>
            </div>
        </form>
    </div>
</div>

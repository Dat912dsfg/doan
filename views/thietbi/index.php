<?php
$danhSachThietBi = $danhSachThietBi ?? [];
$danhSachKhuVuc = $danhSachKhuVuc ?? [];
$tongNode = count($danhSachThietBi);
$soNodeOnline = count(array_filter($danhSachThietBi, fn($tb) => !empty($tb['isOnline'])));
$soNodeOffline = $tongNode - $soNodeOnline;
$tongPhong = count($danhSachKhuVuc);
?>

<div class="flex flex-col items-start justify-between w-full gap-4 my-6 lg:flex-row lg:items-center min-w-0">
    <div class="min-w-0 flex-1">
        <h2 class="text-2xl font-semibold text-gray-700 dark:text-gray-200 truncate">
            Quản lý Thiết bị và Phòng
        </h2>
        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
            Theo dõi node theo từng phòng, gửi lệnh MQTT nhanh và bật/tắt trạng thái vận hành của phòng ngay trên một màn hình.
        </p>
    </div>

    <div id="dynamicActionButton" class="flex-shrink-0"></div>
</div>

<div class="grid gap-4 mb-6 md:grid-cols-2 xl:grid-cols-4">
    <div class="p-4 bg-white rounded-xl border border-gray-200 shadow-xs dark:bg-gray-800 dark:border-gray-700">
        <p class="text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">Tổng số node</p>
        <p id="totalNodeCount" class="mt-2 text-3xl font-bold text-gray-800 dark:text-gray-100"><?= $tongNode ?></p>
    </div>
    <div class="p-4 bg-white rounded-xl border border-gray-200 shadow-xs dark:bg-gray-800 dark:border-gray-700">
        <p class="text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">Node online</p>
        <p id="onlineNodeCount" class="mt-2 text-3xl font-bold text-green-600"><?= $soNodeOnline ?></p>
    </div>
    <div class="p-4 bg-white rounded-xl border border-gray-200 shadow-xs dark:bg-gray-800 dark:border-gray-700">
        <p class="text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">Node offline</p>
        <p id="offlineNodeCount" class="mt-2 text-3xl font-bold text-red-500"><?= $soNodeOffline ?></p>
    </div>
    <div class="p-4 bg-white rounded-xl border border-gray-200 shadow-xs dark:bg-gray-800 dark:border-gray-700">
        <p class="text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">Phòng đang quản lý</p>
        <p class="mt-2 text-3xl font-bold text-blue-600"><?= $tongPhong ?></p>
    </div>
</div>

<div class="mb-6 border-b border-gray-200 dark:border-gray-700">
    <ul class="flex flex-wrap -mb-px text-sm font-medium text-center text-gray-500 dark:text-gray-400">
        <li class="mr-2">
            <a href="#" data-bs-toggle="tab" data-bs-target="#tab-thietbi" data-tab="thietbi"
               class="inline-flex items-center justify-center p-4 border-b-2 rounded-t-lg group transition-colors duration-200">
                <i class="fas fa-microchip mr-2"></i>
                Danh sách node
            </a>
        </li>
        <li class="mr-2">
            <a href="#" data-bs-toggle="tab" data-bs-target="#tab-khuvuc" data-tab="khuvuc"
               class="inline-flex items-center justify-center p-4 border-b-2 rounded-t-lg group transition-colors duration-200">
                <i class="fas fa-door-open mr-2"></i>
                Danh sách phòng
            </a>
        </li>
    </ul>
</div>

<div class="tab-content">
    <div id="tab-thietbi" style="display: none;">
        <div class="grid gap-6 mb-6 xl:grid-cols-3">
            <div class="xl:col-span-2 p-5 bg-white rounded-xl border border-gray-200 shadow-xs dark:bg-gray-800 dark:border-gray-700">
                <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-100">Bảng điều khiển node</h3>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Chọn một node trong danh sách để gửi lệnh MQTT nhanh.</p>
                    </div>
                    <span id="deviceMqttBadge" class="inline-flex items-center px-3 py-1 text-xs font-semibold rounded-full bg-amber-100 text-amber-700 dark:bg-amber-900/40 dark:text-amber-200">
                        Đang kết nối MQTT
                    </span>
                </div>

                <div class="grid gap-4 mt-5 lg:grid-cols-3">
                    <div class="rounded-xl bg-gray-50 dark:bg-gray-700 p-4">
                        <p class="text-xs uppercase tracking-wide text-gray-500 dark:text-gray-400">Node đang chọn</p>
                        <p id="selectedDeviceName" class="mt-2 text-lg font-bold text-gray-800 dark:text-gray-100">Chưa chọn node</p>
                        <p id="selectedDeviceRoom" class="mt-1 text-sm text-gray-500 dark:text-gray-400">Hãy bấm "Điều khiển" ở một dòng bất kỳ.</p>
                    </div>
                    <div class="rounded-xl bg-gray-50 dark:bg-gray-700 p-4 lg:col-span-2">
                        <p class="text-xs uppercase tracking-wide text-gray-500 dark:text-gray-400">Topic điều khiển</p>
                        <p id="selectedControlTopic" class="mt-2 text-sm font-mono break-all text-gray-700 dark:text-gray-200">Chưa có topic điều khiển</p>
                        <p id="selectedDeviceMeta" class="mt-2 text-xs text-gray-400">Node offline hoặc chưa cấu hình topic sẽ không gửi được lệnh.</p>
                    </div>
                </div>

                <div class="grid gap-3 mt-5 md:grid-cols-2 xl:grid-cols-3">
                    <button type="button" data-device-control="mode_manual" class="device-control-btn px-4 py-3 text-sm font-semibold text-white rounded-lg bg-blue-600 hover:bg-blue-700">
                        Bật điều khiển tay
                    </button>
                    <button type="button" data-device-control="mode_auto" class="device-control-btn px-4 py-3 text-sm font-semibold text-white rounded-lg bg-slate-600 hover:bg-slate-700">
                        Trả về tự động
                    </button>
                    <button type="button" data-device-control="relay_on" class="device-control-btn px-4 py-3 text-sm font-semibold text-white rounded-lg bg-emerald-600 hover:bg-emerald-700">
                        Bật relay
                    </button>
                    <button type="button" data-device-control="relay_off" class="device-control-btn px-4 py-3 text-sm font-semibold text-white rounded-lg bg-amber-600 hover:bg-amber-700">
                        Tắt relay
                    </button>
                    <button type="button" data-device-control="buzzer_on" class="device-control-btn px-4 py-3 text-sm font-semibold text-white rounded-lg bg-rose-600 hover:bg-rose-700">
                        Bật còi
                    </button>
                    <button type="button" data-device-control="buzzer_stop" class="device-control-btn px-4 py-3 text-sm font-semibold text-white rounded-lg bg-gray-700 hover:bg-gray-800">
                        Tắt còi
                    </button>
                </div>

                <div id="deviceControlNotice" class="mt-4 rounded-lg border border-gray-200 bg-gray-50 px-4 py-3 text-sm text-gray-600 dark:border-gray-700 dark:bg-gray-900/40 dark:text-gray-300">
                    Chọn node cần thao tác, sau đó bật điều khiển tay trước khi gửi lệnh relay hoặc còi.
                </div>
            </div>

            <div class="p-5 bg-white rounded-xl border border-gray-200 shadow-xs dark:bg-gray-800 dark:border-gray-700">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-100">Lọc nhanh</h3>
                <div class="space-y-4 mt-5">
                    <div>
                        <label for="deviceSearch" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Tìm node / phòng</label>
                        <input id="deviceSearch" type="text" class="form-input mt-2 w-full rounded-lg" placeholder="Ví dụ: ESP32 phòng 101">
                    </div>
                    <div>
                        <label for="deviceStatusFilter" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Trạng thái kết nối</label>
                        <select id="deviceStatusFilter" class="form-select mt-2 w-full rounded-lg">
                            <option value="all">Tất cả</option>
                            <option value="online">Online</option>
                            <option value="offline">Offline</option>
                        </select>
                    </div>
                    <div class="rounded-xl bg-gray-50 dark:bg-gray-700 p-4">
                        <p class="text-xs uppercase tracking-wide text-gray-500 dark:text-gray-400">Kết quả hiển thị</p>
                        <p id="deviceFilterSummary" class="mt-2 text-base font-semibold text-gray-800 dark:text-gray-100">
                            <?= $tongNode ?> / <?= $tongNode ?> node
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <div class="w-full mb-8 overflow-hidden rounded-xl shadow-xs border border-gray-200 dark:border-gray-700">
            <div class="w-full overflow-x-auto">
                <table class="w-full whitespace-no-wrap">
                    <thead>
                        <tr class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase border-b dark:border-gray-700 bg-gray-50 dark:text-gray-400 dark:bg-gray-800">
                            <th class="px-4 py-3">Node thiết bị</th>
                            <th class="px-4 py-3">Phòng</th>
                            <th class="px-4 py-3">Kết nối</th>
                            <th class="px-4 py-3">MQTT sensor</th>
                            <th class="px-4 py-3">MQTT control</th>
                            <th class="px-4 py-3">Kỹ thuật</th>
                            <th class="px-4 py-3">Điều khiển</th>
                            <th class="px-4 py-3 text-right">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody id="deviceTableBody" class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800">
                        <?php if (!empty($danhSachThietBi)): ?>
                            <?php foreach ($danhSachThietBi as $tb): ?>
                                <?php
                                $deviceName = htmlspecialchars($tb['tenThietBi']);
                                $deviceRoom = htmlspecialchars($tb['tenPhong']);
                                $sensorTopic = htmlspecialchars($tb['topicDisplay']);
                                $controlTopic = htmlspecialchars($tb['controlTopicDisplay']);
                                $rawControlTopic = htmlspecialchars($tb['controlTopic'] ?? '', ENT_QUOTES);
                                $isOnline = !empty($tb['isOnline']);
                                ?>
                                <?php
                                $searchName = function_exists('mb_strtolower')
                                    ? mb_strtolower((string) ($tb['tenThietBi'] ?? ''), 'UTF-8')
                                    : strtolower((string) ($tb['tenThietBi'] ?? ''));
                                $searchRoom = function_exists('mb_strtolower')
                                    ? mb_strtolower((string) ($tb['tenPhong'] ?? ''), 'UTF-8')
                                    : strtolower((string) ($tb['tenPhong'] ?? ''));
                                ?>
                                <tr class="device-row text-gray-700 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-150"
                                    data-id="<?= intval($tb['idThietBi']) ?>"
                                    data-name="<?= htmlspecialchars($searchName, ENT_QUOTES) ?>"
                                    data-room="<?= htmlspecialchars($searchRoom, ENT_QUOTES) ?>"
                                    data-sensor-topic="<?= htmlspecialchars($tb['sensorTopic'] ?? '', ENT_QUOTES) ?>"
                                    data-last-heartbeat="<?= !empty($tb['lastHeartbeat']) ? intval(strtotime((string) $tb['lastHeartbeat'])) : 0 ?>"
                                    data-status="<?= $isOnline ? 'online' : 'offline' ?>">
                                    <td class="px-4 py-3 text-sm">
                                        <div class="flex items-center">
                                            <div class="p-2 mr-3 rounded-full bg-blue-100 text-blue-600 dark:bg-blue-900 dark:text-blue-200">
                                                <i class="fas fa-microchip"></i>
                                            </div>
                                            <div class="min-w-0">
                                                <p class="font-semibold truncate"><?= $deviceName ?></p>
                                                <p class="text-[11px] text-gray-500"><?= htmlspecialchars($tb['loaiDisplay']) ?></p>
                                            </div>
                                        </div>
                                    </td>

                                    <td class="px-4 py-3 text-sm">
                                        <span class="font-medium text-gray-800 dark:text-gray-200">
                                            <i class="fas fa-home mr-1 text-gray-400"></i>
                                            <?= $deviceRoom ?>
                                        </span>
                                    </td>

                                    <td class="px-4 py-3 text-xs">
                                        <?php if ($isOnline): ?>
                                            <span class="device-status-badge inline-flex items-center px-2 py-1 font-bold text-green-700 bg-green-100 rounded-full dark:bg-green-700 dark:text-green-100">
                                                <span class="w-2 h-2 bg-green-500 rounded-full mr-2 animate-pulse"></span> Online
                                            </span>
                                        <?php else: ?>
                                            <span class="device-status-badge inline-flex items-center px-2 py-1 font-bold text-red-700 bg-red-100 rounded-full dark:bg-red-900 dark:text-red-100">
                                                <span class="w-2 h-2 bg-red-500 rounded-full mr-2"></span> Offline
                                            </span>
                                        <?php endif; ?>
                                    </td>

                                    <td class="px-4 py-3 text-sm font-mono text-blue-600 dark:text-blue-400 break-all">
                                        <?= $sensorTopic ?>
                                    </td>

                                    <td class="px-4 py-3 text-sm font-mono text-gray-700 dark:text-gray-200 break-all">
                                        <?= $controlTopic ?>
                                    </td>

                                    <td class="px-4 py-3 text-xs">
                                        <div class="space-y-1">
                                            <p><span class="font-semibold">IP:</span> <?= htmlspecialchars($tb['ipDisplay']) ?></p>
                                            <p><span class="font-semibold">FW:</span> <?= htmlspecialchars($tb['firmwareDisplay']) ?></p>
                                            <p><span class="font-semibold">Heartbeat:</span> <span class="device-heartbeat-value"><?= htmlspecialchars($tb['heartbeatDisplay']) ?></span></p>
                                        </div>
                                    </td>

                                    <td class="px-4 py-3 text-sm">
                                        <button type="button"
                                                class="js-select-device inline-flex items-center px-3 py-2 text-xs font-semibold text-white bg-blue-600 rounded-lg hover:bg-blue-700"
                                                data-device-id="<?= intval($tb['idThietBi']) ?>"
                                                data-device-name="<?= htmlspecialchars($tb['tenThietBi'], ENT_QUOTES) ?>"
                                                data-device-room="<?= htmlspecialchars($tb['tenPhong'], ENT_QUOTES) ?>"
                                                data-device-topic="<?= $rawControlTopic ?>"
                                                data-device-sensor-topic="<?= htmlspecialchars($tb['sensorTopic'] ?? '', ENT_QUOTES) ?>"
                                                data-device-online="<?= $isOnline ? '1' : '0' ?>">
                                            Điều khiển
                                        </button>
                                    </td>

                                    <td class="px-4 py-3 text-sm text-right">
                                        <a href="index.php?page=thietbi_sua&id=<?= intval($tb['idThietBi']) ?>" class="text-blue-600 hover:text-blue-800 dark:text-blue-400 p-2">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button @click="openModal" onclick="triggerModal({ title: 'Xóa node thiết bị', description: 'Bạn muốn xóa node <b><?= htmlspecialchars($tb['tenThietBi'], ENT_QUOTES) ?></b> khỏi hệ thống?', confirmUrl: 'index.php?page=thietbi_xuly_xoa&id=<?= intval($tb['idThietBi']) ?>', btnClass: 'bg-red-600 hover:bg-red-700' })" class="text-red-500 hover:text-red-700 p-2">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="8" class="px-4 py-10 text-center text-gray-400">
                                    Chưa có node nào được cấu hình cho dãy trọ.
                                </td>
                            </tr>
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
                    <div class="flex items-start justify-between gap-3 mb-4">
                        <div class="flex items-center min-w-0">
                            <div class="p-3 mr-4 rounded-lg bg-blue-50 dark:bg-blue-900/30 text-blue-600">
                                <i class="fas fa-door-closed text-xl"></i>
                            </div>
                            <div class="min-w-0">
                                <h4 class="text-lg font-bold text-gray-800 dark:text-gray-100 truncate"><?= htmlspecialchars($kv['tenPhong']) ?></h4>
                                <p class="text-[10px] text-gray-400 uppercase tracking-widest">ID: <?= intval($kv['idPhong']) ?></p>
                            </div>
                        </div>
                        <span class="px-2 py-1 text-[10px] font-black rounded bg-gray-100 dark:bg-gray-700 dark:text-gray-300 whitespace-nowrap">
                            <?= htmlspecialchars($kv['cheDoLabel']) ?>
                        </span>
                    </div>

                    <div class="space-y-3 mb-4 text-sm text-gray-600 dark:text-gray-300">
                        <p><?= htmlspecialchars($kv['moTaDisplay']) ?></p>
                        <div class="grid gap-2">
                            <div class="flex items-center justify-between">
                                <span>Vị trí</span>
                                <span class="font-semibold"><?= htmlspecialchars($kv['viTriDisplay']) ?></span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span>Diện tích</span>
                                <span class="font-semibold"><?= htmlspecialchars($kv['dienTichDisplay']) ?></span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span>Trạng thái</span>
                                <span class="font-semibold <?= intval($kv['trangThai'] ?? 0) === 1 ? 'text-green-600' : 'text-red-500' ?>">
                                    <?= htmlspecialchars($kv['trangThaiLabel']) ?>
                                </span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span>Số node</span>
                                <span class="font-semibold"><?= intval($kv['soThietBi'] ?? 0) ?></span>
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-2 pt-4 border-t dark:border-gray-700">
                        <a href="index.php?page=khuvuc_toggle_trangthai&id=<?= intval($kv['idPhong']) ?>"
                           class="inline-flex items-center justify-center px-3 py-2 text-xs font-semibold rounded-lg <?= intval($kv['trangThai'] ?? 0) === 1 ? 'bg-red-50 text-red-600 hover:bg-red-100' : 'bg-green-50 text-green-700 hover:bg-green-100' ?>">
                            <?= intval($kv['trangThai'] ?? 0) === 1 ? 'Tắt phòng' : 'Bật phòng' ?>
                        </a>
                        <a href="index.php?page=khuvuc_toggle_chedo&id=<?= intval($kv['idPhong']) ?>"
                           class="inline-flex items-center justify-center px-3 py-2 text-xs font-semibold rounded-lg <?= intval($kv['cheDoTuDong'] ?? 0) === 1 ? 'bg-amber-50 text-amber-700 hover:bg-amber-100' : 'bg-blue-50 text-blue-700 hover:bg-blue-100' ?>">
                            <?= intval($kv['cheDoTuDong'] ?? 0) === 1 ? 'Chuyển thủ công' : 'Bật tự động' ?>
                        </a>
                    </div>

                    <div class="flex items-center justify-end gap-3 pt-4 mt-4 border-t dark:border-gray-700">
                        <a href="index.php?page=khuvuc_sua&id=<?= intval($kv['idPhong']) ?>" class="text-xs font-bold text-blue-600 uppercase">Chỉnh sửa</a>
                        <button @click="openModal" onclick="triggerModal({ title: 'Xóa phòng', description: 'Bạn muốn xóa phòng <b><?= htmlspecialchars($kv['tenPhong'], ENT_QUOTES) ?></b>? Toàn bộ node và dữ liệu cảm biến thuộc phòng này sẽ bị xóa khỏi database.', confirmUrl: 'index.php?page=khuvuc_xuly_xoa&id=<?= intval($kv['idPhong']) ?>', btnClass: 'bg-red-600 hover:bg-red-700' })" class="text-gray-400 hover:text-red-600 transition">
                            <i class="fas fa-trash-alt"></i>
                        </button>
                    </div>
                </div>
            <?php endforeach; else: ?>
                <div class="col-span-full p-10 text-center bg-white rounded-xl border-2 border-dashed border-gray-200 dark:border-gray-700">
                    <p class="text-gray-400">Nhà trọ hiện chưa có phòng nào được khai báo.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/paho-mqtt/1.0.1/mqttws31.min.js"></script>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const tabs = document.querySelectorAll('[data-bs-toggle="tab"]');
        const tabPanes = {
            thietbi: document.getElementById("tab-thietbi"),
            khuvuc: document.getElementById("tab-khuvuc")
        };
        const actionBtnContainer = document.getElementById("dynamicActionButton");
        const searchInput = document.getElementById("deviceSearch");
        const statusFilter = document.getElementById("deviceStatusFilter");
        const filterSummary = document.getElementById("deviceFilterSummary");
        const totalNodeCount = document.getElementById("totalNodeCount");
        const onlineNodeCount = document.getElementById("onlineNodeCount");
        const offlineNodeCount = document.getElementById("offlineNodeCount");
        const rows = Array.from(document.querySelectorAll(".device-row"));
        const selectButtons = document.querySelectorAll(".js-select-device");
        const mqttBadge = document.getElementById("deviceMqttBadge");
        const notice = document.getElementById("deviceControlNotice");
        const controlButtons = document.querySelectorAll(".device-control-btn");
        const selectedDeviceName = document.getElementById("selectedDeviceName");
        const selectedDeviceRoom = document.getElementById("selectedDeviceRoom");
        const selectedControlTopic = document.getElementById("selectedControlTopic");
        const selectedDeviceMeta = document.getElementById("selectedDeviceMeta");
        const onlineTimeoutSeconds = 6;

        const broker = "broker.hivemq.com";
        const wsPort = 8000;
        const mqttClient = new Paho.MQTT.Client(broker, wsPort, "DeviceManager-" + Math.random().toString(16).slice(2, 10));

        const selectedDevice = {
            id: "",
            name: "",
            room: "",
            topic: "",
            sensorTopic: "",
            online: false
        };
        const heartbeatPostedAt = {};

        function setMqttBadge(type, text) {
            const classes = {
                warning: ["bg-amber-100", "text-amber-700", "dark:bg-amber-900/40", "dark:text-amber-200"],
                success: ["bg-green-100", "text-green-700", "dark:bg-green-900/40", "dark:text-green-200"],
                danger: ["bg-red-100", "text-red-700", "dark:bg-red-900/40", "dark:text-red-200"]
            };

            mqttBadge.className = "inline-flex items-center px-3 py-1 text-xs font-semibold rounded-full";
            mqttBadge.classList.add(...(classes[type] || classes.warning));
            mqttBadge.textContent = text;
        }

        function setNotice(text, tone) {
            const tones = {
                neutral: ["border-gray-200", "bg-gray-50", "text-gray-600", "dark:border-gray-700", "dark:bg-gray-900/40", "dark:text-gray-300"],
                success: ["border-green-200", "bg-green-50", "text-green-700", "dark:border-green-900", "dark:bg-green-900/20", "dark:text-green-200"],
                danger: ["border-red-200", "bg-red-50", "text-red-700", "dark:border-red-900", "dark:bg-red-900/20", "dark:text-red-200"]
            };

            notice.className = "mt-4 rounded-lg border px-4 py-3 text-sm";
            notice.classList.add(...(tones[tone] || tones.neutral));
            notice.textContent = text;
        }

        function updateControlAvailability() {
            const canControl = selectedDevice.topic !== "" && selectedDevice.online;

            controlButtons.forEach((button) => {
                button.disabled = !canControl;
                button.classList.toggle("opacity-50", !canControl);
                button.classList.toggle("cursor-not-allowed", !canControl);
            });
        }

        function selectDevice(button) {
            selectButtons.forEach((item) => {
                item.classList.remove("ring-2", "ring-blue-300");
            });

            button.classList.add("ring-2", "ring-blue-300");

            selectedDevice.id = button.dataset.deviceId || "";
            selectedDevice.name = button.dataset.deviceName || "";
            selectedDevice.room = button.dataset.deviceRoom || "";
            selectedDevice.topic = button.dataset.deviceTopic || "";
            selectedDevice.sensorTopic = button.dataset.deviceSensorTopic || "";
            selectedDevice.online = button.dataset.deviceOnline === "1";

            selectedDeviceName.textContent = selectedDevice.name || "Chưa chọn node";
            selectedDeviceRoom.textContent = selectedDevice.room
                ? `Phòng: ${selectedDevice.room}`
                : "Chưa gán phòng";
            selectedControlTopic.textContent = selectedDevice.topic || "Chưa có topic điều khiển";
            selectedDeviceMeta.textContent = selectedDevice.online
                ? "Node đang online. Hãy bật điều khiển tay trước khi gửi lệnh relay hoặc còi."
                : "Node đang offline hoặc không phản hồi. Lệnh có thể không đến được thiết bị.";

            updateControlAvailability();
            setNotice(`Đã chọn ${selectedDevice.name}. Bạn có thể bật chế độ tay rồi gửi lệnh MQTT.`, "neutral");
        }

        function getStatusBadge(row) {
            return row.querySelector(".device-status-badge");
        }

        function getHeartbeatLabel(row) {
            return row.querySelector(".device-heartbeat-value");
        }

        function formatNowForUi() {
            return new Date().toLocaleString("vi-VN", {
                hour: "2-digit",
                minute: "2-digit",
                second: "2-digit",
                day: "2-digit",
                month: "2-digit",
                year: "numeric"
            });
        }

        function updateSummaryCounts() {
            let onlineCount = 0;
            rows.forEach((row) => {
                if (row.dataset.status === "online") {
                    onlineCount += 1;
                }
            });

            const total = rows.length;
            const offlineCount = total - onlineCount;

            if (totalNodeCount) totalNodeCount.textContent = String(total);
            if (onlineNodeCount) onlineNodeCount.textContent = String(onlineCount);
            if (offlineNodeCount) offlineNodeCount.textContent = String(offlineCount);
        }

        function markRowOnline(row, shouldPersist) {
            if (!row) {
                return;
            }

            row.dataset.status = "online";
            row.dataset.lastHeartbeat = String(Math.floor(Date.now() / 1000));

            const badge = getStatusBadge(row);
            if (badge) {
                badge.className = "device-status-badge inline-flex items-center px-2 py-1 font-bold text-green-700 bg-green-100 rounded-full dark:bg-green-700 dark:text-green-100";
                badge.innerHTML = '<span class="w-2 h-2 bg-green-500 rounded-full mr-2 animate-pulse"></span> Online';
            }

            const heartbeatLabel = getHeartbeatLabel(row);
            if (heartbeatLabel) {
                heartbeatLabel.textContent = formatNowForUi();
            }

            const controlButton = row.querySelector(".js-select-device");
            if (controlButton) {
                controlButton.dataset.deviceOnline = "1";
                if (selectedDevice.id !== "" && selectedDevice.id === controlButton.dataset.deviceId) {
                    selectedDevice.online = true;
                    selectedDeviceMeta.textContent = "Node đang online và vừa gửi dữ liệu MQTT. Bạn có thể gửi lệnh điều khiển.";
                    updateControlAvailability();
                }
            }

            updateSummaryCounts();
            filterRows();

            if (!shouldPersist) {
                return;
            }

            const sensorTopic = row.dataset.sensorTopic || "";
            const now = Date.now();
            if (sensorTopic === "" || (heartbeatPostedAt[sensorTopic] && now - heartbeatPostedAt[sensorTopic] < 15000)) {
                return;
            }

            heartbeatPostedAt[sensorTopic] = now;
            fetch("api/device_heartbeat.php", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    Accept: "application/json"
                },
                body: JSON.stringify({
                    topic: sensorTopic,
                    firmwareVersion: "web-mqtt-sync"
                })
            }).catch((error) => {
                console.error("[MQTT] Failed to sync heartbeat to local API:", error);
            });
        }

        function markRowOffline(row) {
            if (!row || row.dataset.status === "offline") {
                return;
            }

            row.dataset.status = "offline";

            const badge = getStatusBadge(row);
            if (badge) {
                badge.className = "device-status-badge inline-flex items-center px-2 py-1 font-bold text-red-700 bg-red-100 rounded-full dark:bg-red-900 dark:text-red-100";
                badge.innerHTML = '<span class="w-2 h-2 bg-red-500 rounded-full mr-2"></span> Offline';
            }

            const controlButton = row.querySelector(".js-select-device");
            if (controlButton) {
                controlButton.dataset.deviceOnline = "0";
                if (selectedDevice.id !== "" && selectedDevice.id === controlButton.dataset.deviceId) {
                    selectedDevice.online = false;
                    selectedDeviceMeta.textContent = "Node đang offline hoặc đã quá thời gian heartbeat. Lệnh có thể không đến được thiết bị.";
                    updateControlAvailability();
                }
            }

            updateSummaryCounts();
            filterRows();
        }

        function refreshDeviceStatuses() {
            const now = Math.floor(Date.now() / 1000);

            rows.forEach((row) => {
                const lastHeartbeat = parseInt(row.dataset.lastHeartbeat || "0", 10);
                if (lastHeartbeat <= 0) {
                    return;
                }

                if ((now - lastHeartbeat) > onlineTimeoutSeconds) {
                    markRowOffline(row);
                }
            });
        }

        function sendDeviceCommand(action) {
            if (!selectedDevice.topic) {
                setNotice("Node này chưa có topic điều khiển nên không thể gửi lệnh.", "danger");
                return;
            }

            if (!selectedDevice.online) {
                setNotice("Node hiện đang offline. Hãy kiểm tra kết nối trước khi gửi lệnh.", "danger");
                return;
            }

            const payload = action === "mode_manual"
                ? { mode: 1 }
                : action === "mode_auto"
                    ? { mode: 0 }
                    : { control: action };

            try {
                const message = new Paho.MQTT.Message(JSON.stringify(payload));
                message.destinationName = selectedDevice.topic;
                mqttClient.send(message);
                setNotice(`Đã gửi lệnh ${action} đến ${selectedDevice.name}.`, "success");
            } catch (error) {
                console.error("[MQTT] Failed to send device command:", error);
                setNotice("Gửi lệnh thất bại. Vui lòng thử lại sau.", "danger");
            }
        }

        function filterRows() {
            const keyword = (searchInput.value || "").trim().toLowerCase();
            const status = statusFilter.value;
            let visibleCount = 0;

            rows.forEach((row) => {
                const matchKeyword = keyword === ""
                    || row.dataset.name.includes(keyword)
                    || row.dataset.room.includes(keyword);
                const matchStatus = status === "all" || row.dataset.status === status;
                const visible = matchKeyword && matchStatus;

                row.style.display = visible ? "" : "none";
                if (visible) {
                    visibleCount += 1;
                }
            });

            filterSummary.textContent = `${visibleCount} / ${rows.length} node`;
        }

        function updateUI(activeId) {
            tabs.forEach((tab) => {
                const isMatch = tab.getAttribute("data-tab") === activeId;
                tab.className = `inline-flex items-center justify-center p-4 border-b-2 rounded-t-lg transition-all duration-200 ${
                    isMatch ? "text-blue-600 border-blue-600 dark:text-blue-400 dark:border-blue-400 font-bold" : "border-transparent text-gray-400"
                }`;
            });

            const config = {
                thietbi: { text: "Thêm node mới", page: "thietbi_them" },
                khuvuc: { text: "Thêm phòng mới", page: "khuvuc_them" }
            }[activeId];

            actionBtnContainer.innerHTML = `
                <a href="index.php?page=${config.page}" class="flex items-center px-4 py-2 text-sm font-bold text-white bg-blue-600 rounded-lg hover:bg-blue-700 shadow-md">
                    <i class="fas fa-plus mr-2"></i> ${config.text}
                </a>
            `;
        }

        function connectMqtt() {
            setMqttBadge("warning", "Đang kết nối MQTT");

            mqttClient.connect({
                useSSL: false,
                keepAliveInterval: 30,
                onSuccess: function() {
                    const subscribedTopics = new Set();
                    rows.forEach((row) => {
                        const topic = row.dataset.sensorTopic || "";
                        if (topic !== "" && !subscribedTopics.has(topic)) {
                            mqttClient.subscribe(topic);
                            subscribedTopics.add(topic);
                        }
                    });
                    setMqttBadge("success", "MQTT đã sẵn sàng");
                    setNotice("Kết nối MQTT đã sẵn sàng cho bảng điều khiển node và trạng thái online realtime.", "neutral");
                },
                onFailure: function(error) {
                    console.error("[MQTT] Device page connect failed:", error.errorMessage);
                    setMqttBadge("danger", "MQTT mất kết nối");
                    setNotice("Không kết nối được MQTT. Hệ thống sẽ tự thử lại sau vài giây.", "danger");
                    window.setTimeout(connectMqtt, 3000);
                }
            });
        }

        mqttClient.onConnectionLost = function(respObj) {
            if (respObj.errorCode !== 0) {
                console.error("[MQTT] Device page lost connection:", respObj.errorMessage);
            }
            setMqttBadge("danger", "MQTT mất kết nối");
            setNotice("Kết nối MQTT vừa bị ngắt. Hệ thống đang tự kết nối lại...", "danger");
            window.setTimeout(connectMqtt, 3000);
        };

        mqttClient.onMessageArrived = function(message) {
            const topic = message.destinationName || "";
            const row = rows.find((item) => item.dataset.sensorTopic === topic);
            if (!row) {
                return;
            }

            markRowOnline(row, true);
        };

        tabs.forEach((tab) => {
            tab.addEventListener("click", function(e) {
                e.preventDefault();
                const targetId = this.getAttribute("data-tab");
                Object.values(tabPanes).forEach((pane) => {
                    pane.style.display = "none";
                });
                tabPanes[targetId].style.display = "block";
                updateUI(targetId);
            });
        });

        if (tabs.length > 0) {
            tabs[0].click();
        }

        if (searchInput) {
            searchInput.addEventListener("input", filterRows);
        }

        if (statusFilter) {
            statusFilter.addEventListener("change", filterRows);
        }

        selectButtons.forEach((button) => {
            button.addEventListener("click", function() {
                selectDevice(this);
            });
        });

        controlButtons.forEach((button) => {
            button.addEventListener("click", function() {
                sendDeviceCommand(this.dataset.deviceControl);
            });
        });

        filterRows();
        updateSummaryCounts();
        updateControlAvailability();
        connectMqtt();
        window.setInterval(refreshDeviceStatuses, 1000);

        if (selectButtons.length > 0) {
            selectButtons[0].click();
        }
    });
</script>

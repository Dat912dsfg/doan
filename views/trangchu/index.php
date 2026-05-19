<?php
$danhSachThietBi = $danhSachThietBi ?? [];
$danhSachKhuVuc = $danhSachKhuVuc ?? [];

$dashboardDevices = array_map(static function ($tb) {
    return [
        'id' => intval($tb['idThietBi'] ?? 0),
        'name' => (string) ($tb['tenThietBi'] ?? ''),
        'roomId' => intval($tb['idPhong'] ?? 0),
        'roomName' => (string) ($tb['tenPhong'] ?? 'Chưa gán phòng'),
        'sensorTopic' => (string) ($tb['sensorTopic'] ?? ''),
        'controlTopic' => (string) ($tb['controlTopic'] ?? ''),
        'lastHeartbeatTs' => !empty($tb['lastHeartbeat']) ? intval(strtotime((string) $tb['lastHeartbeat'])) : 0,
        'isOnline' => !empty($tb['isOnline']),
    ];
}, $danhSachThietBi);

$dashboardRooms = array_map(static function ($room) {
    return [
        'id' => intval($room['idPhong'] ?? 0),
        'name' => (string) ($room['tenPhong'] ?? ''),
    ];
}, $danhSachKhuVuc);
?>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<h2 class="my-6 text-2xl font-semibold text-gray-700 dark:text-gray-200">
    Tổng quan hệ thống
</h2>

<div class="grid gap-6 mb-8 md:grid-cols-2 xl:grid-cols-4">
    <div class="flex items-center p-4 bg-white rounded-xl shadow-xs dark:bg-gray-800 border border-gray-200 dark:border-gray-700">
        <div class="p-3 mr-4 text-red-500 bg-red-100 rounded-full dark:text-red-100 dark:bg-red-500">
            <i class="fas fa-temperature-high text-xl w-5 h-5 flex items-center justify-center"></i>
        </div>
        <div>
            <p class="mb-1 text-sm font-medium text-gray-600 dark:text-gray-400">Nhiệt độ</p>
            <p class="text-2xl font-bold text-gray-800 dark:text-gray-100" id="val-nhietdo">-- °C</p>
            <p class="text-xs text-gray-400">Cảm biến DHT22</p>
        </div>
    </div>

    <div class="flex items-center p-4 bg-white rounded-xl shadow-xs dark:bg-gray-800 border border-gray-200 dark:border-gray-700">
        <div class="p-3 mr-4 text-cyan-500 bg-cyan-100 rounded-full dark:text-cyan-100 dark:bg-cyan-500">
            <i class="fas fa-tint text-xl w-5 h-5 flex items-center justify-center"></i>
        </div>
        <div>
            <p class="mb-1 text-sm font-medium text-gray-600 dark:text-gray-400">Độ ẩm</p>
            <p class="text-2xl font-bold text-gray-800 dark:text-gray-100" id="val-doam">-- %</p>
            <p class="text-xs text-gray-400">Cảm biến DHT22</p>
        </div>
    </div>

    <div class="flex items-center p-4 bg-white rounded-xl shadow-xs dark:bg-gray-800 border border-gray-200 dark:border-gray-700">
        <div class="p-3 mr-4 text-amber-500 bg-amber-100 rounded-full dark:text-amber-100 dark:bg-amber-500">
            <i class="fas fa-smog text-xl w-5 h-5 flex items-center justify-center"></i>
        </div>
        <div>
            <p class="mb-1 text-sm font-medium text-gray-600 dark:text-gray-400">Gas / khói</p>
            <p class="text-2xl font-bold text-gray-800 dark:text-gray-100" id="val-gas">-- ppm</p>
            <p class="text-xs text-gray-400">Cảm biến MQ-2</p>
        </div>
    </div>

    <div class="flex items-center p-4 bg-white rounded-xl shadow-xs dark:bg-gray-800 border border-gray-200 dark:border-gray-700">
        <div class="p-3 mr-4 text-violet-500 bg-violet-100 rounded-full dark:text-violet-100 dark:bg-violet-500">
            <i class="fas fa-walking text-xl w-5 h-5 flex items-center justify-center"></i>
        </div>
        <div>
            <p class="mb-1 text-sm font-medium text-gray-600 dark:text-gray-400">Chuyển động</p>
            <p class="text-lg font-bold text-gray-800 dark:text-gray-100" id="val-pir">Đang chờ dữ liệu</p>
            <p class="text-xs text-gray-400">Cảm biến PIR</p>
        </div>
    </div>
</div>

<div class="grid gap-6 mb-8 xl:grid-cols-3">
    <div class="p-5 bg-white rounded-xl shadow-xs dark:bg-gray-800 border border-gray-200 dark:border-gray-700">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-semibold text-gray-500 dark:text-gray-400">Relay</p>
                <p id="status-relay" class="mt-2 text-2xl font-bold text-gray-800 dark:text-gray-100">Chưa có dữ liệu</p>
            </div>
            <div class="p-3 rounded-full bg-blue-100 text-blue-600 dark:bg-blue-900 dark:text-blue-200">
                <i class="fas fa-plug text-xl"></i>
            </div>
        </div>
        <p class="mt-3 text-xs text-gray-400">Theo dõi tức thời trạng thái tải điện từ node đang được chọn.</p>
    </div>

    <div class="p-5 bg-white rounded-xl shadow-xs dark:bg-gray-800 border border-gray-200 dark:border-gray-700">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-semibold text-gray-500 dark:text-gray-400">Còi cảnh báo</p>
                <p id="status-buzzer" class="mt-2 text-2xl font-bold text-gray-800 dark:text-gray-100">Chưa có dữ liệu</p>
            </div>
            <div class="p-3 rounded-full bg-rose-100 text-rose-600 dark:bg-rose-900 dark:text-rose-200">
                <i class="fas fa-bell text-xl"></i>
            </div>
        </div>
        <p class="mt-3 text-xs text-gray-400">Hiển thị nhanh trạng thái còi để phát hiện cảnh báo bất thường.</p>
    </div>

    <div class="p-5 bg-white rounded-xl shadow-xs dark:bg-gray-800 border border-gray-200 dark:border-gray-700">
        <div class="flex items-center justify-between gap-4">
            <div>
                <p class="text-sm font-semibold text-gray-500 dark:text-gray-400">Chế độ điều khiển</p>
                <p id="modeLabel" class="mt-2 text-xl font-bold text-gray-800 dark:text-gray-100">Kịch bản đang chạy</p>
            </div>
            <label class="relative inline-flex items-center cursor-pointer">
                <input type="checkbox" id="masterSwitch" class="sr-only peer">
                <div class="w-12 h-7 bg-gray-200 rounded-full peer peer-checked:bg-blue-600 transition-all duration-300"></div>
                <div class="absolute left-1 top-1 w-5 h-5 bg-white rounded-full shadow-sm peer-checked:translate-x-5 transition-all duration-300"></div>
            </label>
        </div>
        <p class="mt-3 text-xs text-gray-400">Bật để chuyển sang điều khiển tay cho đúng node đang chọn.</p>
    </div>
</div>

<div class="grid gap-6 mb-8 xl:grid-cols-3">
    <div class="xl:col-span-2 p-5 bg-white rounded-xl shadow-xs dark:bg-gray-800 border border-gray-200 dark:border-gray-700">
        <div class="flex flex-col gap-3 mb-5 lg:flex-row lg:items-center lg:justify-between">
            <div>
                <h4 class="text-lg font-semibold text-gray-800 dark:text-gray-100">Điều khiển nhanh</h4>
                <p class="text-sm text-gray-500 dark:text-gray-400">Topic điều khiển sẽ thay đổi theo node đang chọn thay vì cố định một phòng.</p>
            </div>
            <div class="flex items-center gap-2">
                <span id="mqttBadge" class="inline-flex items-center px-3 py-1 text-xs font-semibold rounded-full bg-amber-100 text-amber-700 dark:bg-amber-900/40 dark:text-amber-200">
                    Đang kết nối MQTT
                </span>
                <span id="lastUpdateLabel" class="text-xs text-gray-400">Chưa nhận dữ liệu</span>
            </div>
        </div>

        <div class="grid gap-4 md:grid-cols-2" id="controlGrid">
            <div class="rounded-xl border border-gray-200 dark:border-gray-700 p-4 opacity-50 transition-all duration-200">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <p class="font-semibold text-gray-800 dark:text-gray-100">Relay</p>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Bật hoặc tắt tải điện theo lệnh tường minh.</p>
                    </div>
                    <div class="p-2 rounded-full bg-blue-50 text-blue-600 dark:bg-blue-900 dark:text-blue-200">
                        <i class="fas fa-bolt"></i>
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-3 mt-4">
                    <button type="button" data-control="relay_on" class="btn-device px-4 py-3 text-sm font-semibold text-white bg-gray-400 rounded-lg" disabled>
                        Bật relay
                    </button>
                    <button type="button" data-control="relay_off" class="btn-device px-4 py-3 text-sm font-semibold text-white bg-gray-400 rounded-lg" disabled>
                        Tắt relay
                    </button>
                </div>
            </div>

            <div class="rounded-xl border border-gray-200 dark:border-gray-700 p-4 opacity-50 transition-all duration-200">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <p class="font-semibold text-gray-800 dark:text-gray-100">Còi cảnh báo</p>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Chủ động bật kiểm tra hoặc tắt còi khi đang ở chế độ tay.</p>
                    </div>
                    <div class="p-2 rounded-full bg-rose-50 text-rose-600 dark:bg-rose-900 dark:text-rose-200">
                        <i class="fas fa-bell"></i>
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-3 mt-4">
                    <button type="button" data-control="buzzer_on" class="btn-device px-4 py-3 text-sm font-semibold text-white bg-gray-400 rounded-lg" disabled>
                        Bật còi
                    </button>
                    <button type="button" data-control="buzzer_stop" class="btn-device px-4 py-3 text-sm font-semibold text-white bg-gray-400 rounded-lg" disabled>
                        Tắt còi
                    </button>
                </div>
            </div>
        </div>

        <div id="controlNotice" class="mt-4 rounded-lg border border-gray-200 bg-gray-50 px-4 py-3 text-sm text-gray-600 dark:border-gray-700 dark:bg-gray-900/40 dark:text-gray-300">
            Chọn phòng và node để giám sát đúng topic, sau đó bật chế độ điều khiển tay nếu muốn gửi lệnh.
        </div>
    </div>

    <div class="p-5 bg-white rounded-xl shadow-xs dark:bg-gray-800 border border-gray-200 dark:border-gray-700">
        <h4 class="text-lg font-semibold text-gray-800 dark:text-gray-100">Giám sát nhanh</h4>
        <div class="space-y-4 mt-4">
            <div>
                <label for="dashboardRoomSelect" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Phòng</label>
                <select id="dashboardRoomSelect" class="form-select mt-2 w-full rounded-lg">
                    <option value="all">Tất cả phòng</option>
                    <?php foreach ($dashboardRooms as $room): ?>
                        <option value="<?= $room['id'] ?>"><?= htmlspecialchars($room['name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div>
                <label for="dashboardDeviceSelect" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Node</label>
                <select id="dashboardDeviceSelect" class="form-select mt-2 w-full rounded-lg"></select>
            </div>
            <div class="rounded-xl bg-gray-50 dark:bg-gray-700 p-4">
                <p class="text-xs uppercase tracking-wide text-gray-500 dark:text-gray-400">Node đang theo dõi</p>
                <p id="selectedNodeName" class="mt-2 text-base font-semibold text-gray-800 dark:text-gray-100">Chưa chọn node</p>
                <p id="selectedNodeRoom" class="mt-1 text-sm text-gray-500 dark:text-gray-400">Hãy chọn một phòng hoặc node.</p>
                <p id="selectedNodeState" class="mt-2 text-xs font-semibold text-gray-400">Chưa có trạng thái kết nối.</p>
            </div>
            <div class="rounded-xl bg-gray-50 dark:bg-gray-700 p-4">
                <p class="text-xs uppercase tracking-wide text-gray-500 dark:text-gray-400">PIR</p>
                <p id="pir-status-detail" class="mt-2 text-base font-semibold text-gray-800 dark:text-gray-100">Chưa có dữ liệu</p>
            </div>
            <div class="rounded-xl bg-gray-50 dark:bg-gray-700 p-4">
                <p class="text-xs uppercase tracking-wide text-gray-500 dark:text-gray-400">MQTT sensor</p>
                <p id="selectedSensorTopic" class="mt-2 text-sm font-mono break-all text-gray-700 dark:text-gray-200">Chưa cấu hình</p>
            </div>
            <div class="rounded-xl bg-gray-50 dark:bg-gray-700 p-4">
                <p class="text-xs uppercase tracking-wide text-gray-500 dark:text-gray-400">MQTT control</p>
                <p id="selectedControlTopic" class="mt-2 text-sm font-mono break-all text-gray-700 dark:text-gray-200">Chưa cấu hình</p>
            </div>
        </div>
    </div>
</div>

<div class="min-w-0 p-5 mb-8 bg-white rounded-xl shadow-xs dark:bg-gray-800 border border-gray-200 dark:border-gray-700">
    <div class="flex flex-col gap-4 mb-4 lg:flex-row lg:items-start lg:justify-between">
        <div>
            <h4 class="text-lg font-semibold text-gray-800 dark:text-gray-100">Xu hướng môi trường 24 giờ</h4>
            <p id="chartStatus" class="mt-2 text-sm text-gray-500 dark:text-gray-400">Đang tải dữ liệu lịch sử từ database...</p>
        </div>
        <div class="flex flex-wrap gap-2">
            <button type="button" class="chart-toggle inline-flex items-center px-3 py-2 text-xs font-semibold rounded-full bg-red-100 text-red-700 dark:bg-red-900/40 dark:text-red-200" data-dataset="temp">
                Nhiệt độ
            </button>
            <button type="button" class="chart-toggle inline-flex items-center px-3 py-2 text-xs font-semibold rounded-full bg-cyan-100 text-cyan-700 dark:bg-cyan-900/40 dark:text-cyan-200" data-dataset="hum">
                Độ ẩm
            </button>
        </div>
    </div>
    <div class="relative w-full h-72">
        <canvas id="lineChart"></canvas>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/paho-mqtt/1.0.1/mqttws31.min.js"></script>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const mqttBroker = "broker.hivemq.com";
        const mqttPort = 8000;
        const dashboardDevices = <?= json_encode($dashboardDevices, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) ?>;
        const onlineTimeoutSeconds = 6;

        const dom = {
            roomSelect: document.getElementById("dashboardRoomSelect"),
            deviceSelect: document.getElementById("dashboardDeviceSelect"),
            selectedNodeName: document.getElementById("selectedNodeName"),
            selectedNodeRoom: document.getElementById("selectedNodeRoom"),
            selectedNodeState: document.getElementById("selectedNodeState"),
            selectedSensorTopic: document.getElementById("selectedSensorTopic"),
            selectedControlTopic: document.getElementById("selectedControlTopic"),
            masterSwitch: document.getElementById("masterSwitch"),
            modeLabel: document.getElementById("modeLabel"),
            controlCards: document.querySelectorAll("#controlGrid > div"),
            buttons: document.querySelectorAll(".btn-device"),
            chartButtons: document.querySelectorAll(".chart-toggle"),
            chartStatus: document.getElementById("chartStatus"),
            chartCanvas: document.getElementById("lineChart"),
            mqttBadge: document.getElementById("mqttBadge"),
            controlNotice: document.getElementById("controlNotice"),
            lastUpdateLabel: document.getElementById("lastUpdateLabel"),
            relayStatus: document.getElementById("status-relay"),
            buzzerStatus: document.getElementById("status-buzzer"),
            pirValue: document.getElementById("val-pir"),
            pirDetail: document.getElementById("pir-status-detail"),
            tempValue: document.getElementById("val-nhietdo"),
            humValue: document.getElementById("val-doam"),
            gasValue: document.getElementById("val-gas")
        };

        const state = {
            lastStoredAt: 0,
            mqttConnected: false,
            currentSubscription: "",
            selectedDevice: null,
            datasetsVisible: {
                temp: true,
                hum: true
            }
        };

        const clientId = "WebClient-" + Math.random().toString(16).slice(2, 10);
        const client = new Paho.MQTT.Client(mqttBroker, mqttPort, clientId);

        const chart = new Chart(dom.chartCanvas.getContext("2d"), {
            type: "line",
            data: {
                labels: [],
                datasets: [
                    {
                        label: "Nhiệt độ (°C)",
                        data: [],
                        borderColor: "#ef4444",
                        backgroundColor: "rgba(239, 68, 68, 0.12)",
                        pointRadius: 2,
                        pointHoverRadius: 4,
                        tension: 0.35,
                        fill: true,
                        spanGaps: true,
                        yAxisID: "yTemp"
                    },
                    {
                        label: "Độ ẩm (%)",
                        data: [],
                        borderColor: "#06b6d4",
                        backgroundColor: "rgba(6, 182, 212, 0.12)",
                        pointRadius: 2,
                        pointHoverRadius: 4,
                        tension: 0.35,
                        fill: true,
                        spanGaps: true,
                        yAxisID: "yHum"
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                interaction: {
                    mode: "index",
                    intersect: false
                },
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    x: {
                        ticks: {
                            maxTicksLimit: 12
                        },
                        grid: {
                            display: false
                        }
                    },
                    yTemp: {
                        type: "linear",
                        position: "left",
                        suggestedMin: 15,
                        suggestedMax: 45,
                        ticks: {
                            callback: (value) => `${value}°C`
                        }
                    },
                    yHum: {
                        type: "linear",
                        position: "right",
                        suggestedMin: 0,
                        suggestedMax: 100,
                        grid: {
                            drawOnChartArea: false
                        },
                        ticks: {
                            callback: (value) => `${value}%`
                        }
                    }
                }
            }
        });

        const datasetMap = {
            temp: 0,
            hum: 1
        };

        function setBadgeState(type, text) {
            const classes = {
                warning: ["bg-amber-100", "text-amber-700", "dark:bg-amber-900/40", "dark:text-amber-200"],
                success: ["bg-green-100", "text-green-700", "dark:bg-green-900/40", "dark:text-green-200"],
                danger: ["bg-red-100", "text-red-700", "dark:bg-red-900/40", "dark:text-red-200"]
            };

            dom.mqttBadge.className = "inline-flex items-center px-3 py-1 text-xs font-semibold rounded-full";
            dom.mqttBadge.classList.add(...(classes[type] || classes.warning));
            dom.mqttBadge.textContent = text;
        }

        function setControlNotice(text, tone) {
            const tones = {
                neutral: ["border-gray-200", "bg-gray-50", "text-gray-600", "dark:border-gray-700", "dark:bg-gray-900/40", "dark:text-gray-300"],
                success: ["border-green-200", "bg-green-50", "text-green-700", "dark:border-green-900", "dark:bg-green-900/20", "dark:text-green-200"],
                danger: ["border-red-200", "bg-red-50", "text-red-700", "dark:border-red-900", "dark:bg-red-900/20", "dark:text-red-200"]
            };

            dom.controlNotice.className = "mt-4 rounded-lg border px-4 py-3 text-sm";
            dom.controlNotice.classList.add(...(tones[tone] || tones.neutral));
            dom.controlNotice.textContent = text;
        }

        function applyChartData(labels, tempSeries, humSeries) {
            chart.data.labels = labels;
            chart.data.datasets[0].data = tempSeries;
            chart.data.datasets[1].data = humSeries;
            chart.update();
        }

        function refreshChartToggleButtons() {
            dom.chartButtons.forEach((button) => {
                const datasetKey = button.dataset.dataset;
                const isVisible = state.datasetsVisible[datasetKey];
                button.classList.toggle("opacity-40", !isVisible);
                button.classList.toggle("line-through", !isVisible);
            });
        }

        function resetRealtimePanel() {
            dom.tempValue.textContent = "-- °C";
            dom.humValue.textContent = "-- %";
            dom.gasValue.textContent = "-- ppm";
            dom.pirValue.textContent = "Đang chờ dữ liệu";
            dom.pirValue.className = "text-lg font-bold text-gray-800 dark:text-gray-100";
            dom.pirDetail.textContent = "Chưa có dữ liệu";
            dom.pirDetail.className = "mt-2 text-base font-semibold text-gray-800 dark:text-gray-100";
            dom.relayStatus.textContent = "Chưa có dữ liệu";
            dom.relayStatus.className = "mt-2 text-2xl font-bold text-gray-800 dark:text-gray-100";
            dom.buzzerStatus.textContent = "Chưa có dữ liệu";
            dom.buzzerStatus.className = "mt-2 text-2xl font-bold text-gray-800 dark:text-gray-100";
            updateDeviceState('[data-control="relay_on"]', false, "ring-green-400");
            updateDeviceState('[data-control="relay_off"]', false, "ring-red-400");
            updateDeviceState('[data-control="buzzer_on"]', false, "ring-red-400");
            updateDeviceState('[data-control="buzzer_stop"]', false, "ring-green-400");
            dom.lastUpdateLabel.textContent = "Chưa nhận dữ liệu";
        }

        function updateSelectedDeviceInfo() {
            const device = state.selectedDevice;

            if (!device) {
                dom.selectedNodeName.textContent = "Chưa chọn node";
                dom.selectedNodeRoom.textContent = "Hãy chọn một phòng hoặc node.";
                dom.selectedNodeState.textContent = "Chưa có trạng thái kết nối.";
                dom.selectedNodeState.className = "mt-2 text-xs font-semibold text-gray-400";
                dom.selectedSensorTopic.textContent = "Chưa cấu hình";
                dom.selectedControlTopic.textContent = "Chưa cấu hình";
                return;
            }

            dom.selectedNodeName.textContent = device.name || "Chưa có tên node";
            dom.selectedNodeRoom.textContent = device.roomName
                ? `Phòng: ${device.roomName}`
                : "Chưa gán phòng";
            dom.selectedSensorTopic.textContent = device.sensorTopic || "Chưa cấu hình";
            dom.selectedControlTopic.textContent = device.controlTopic || "Chưa cấu hình";

            if (device.isOnline) {
                dom.selectedNodeState.textContent = "Node đang online và sẵn sàng giám sát.";
                dom.selectedNodeState.className = "mt-2 text-xs font-semibold text-emerald-600";
            } else {
                dom.selectedNodeState.textContent = "Node đang offline hoặc chưa gửi heartbeat gần đây.";
                dom.selectedNodeState.className = "mt-2 text-xs font-semibold text-amber-600";
            }
        }

        function updateDeviceState(buttonSelector, isActive, activeRingClass) {
            const buttons = document.querySelectorAll(buttonSelector);
            buttons.forEach((button) => {
                button.classList.remove("ring-2", "ring-green-400", "ring-red-400");
                if (isActive) {
                    button.classList.add("ring-2", activeRingClass);
                }
            });
        }

        function canControlSelectedDevice() {
            return Boolean(
                state.selectedDevice &&
                state.selectedDevice.controlTopic &&
                state.selectedDevice.isOnline
            );
        }

        function refreshDeviceOnlineStates() {
            const now = Math.floor(Date.now() / 1000);
            let selectedChanged = false;

            dashboardDevices.forEach((device) => {
                if (!device.lastHeartbeatTs) {
                    return;
                }

                const shouldBeOnline = (now - device.lastHeartbeatTs) <= onlineTimeoutSeconds;
                if (device.isOnline !== shouldBeOnline) {
                    device.isOnline = shouldBeOnline;
                    if (state.selectedDevice && state.selectedDevice.id === device.id) {
                        selectedChanged = true;
                    }
                }
            });

            if (selectedChanged) {
                updateSelectedDeviceInfo();
                updateModeUI(dom.masterSwitch.checked);
            }
        }

        function updateModeUI(isManual) {
            const canControl = canControlSelectedDevice();
            dom.modeLabel.textContent = isManual ? "Đang điều khiển tay" : "Kịch bản đang chạy";

            dom.controlCards.forEach((card) => {
                card.classList.toggle("opacity-50", !(isManual && canControl));
                card.classList.toggle("ring-1", isManual && canControl);
                card.classList.toggle("ring-blue-200", isManual && canControl);
            });

            dom.buttons.forEach((button) => {
                const enabled = isManual && canControl;
                button.disabled = !enabled;
                button.classList.remove("bg-blue-600", "hover:bg-blue-700", "bg-rose-600", "hover:bg-rose-700", "bg-gray-400", "cursor-pointer", "cursor-not-allowed");
                button.classList.add(enabled ? "cursor-pointer" : "cursor-not-allowed");

                if (!enabled) {
                    button.classList.add("bg-gray-400");
                    return;
                }

                if ((button.dataset.control || "").indexOf("buzzer") === 0) {
                    button.classList.add("bg-rose-600", "hover:bg-rose-700");
                } else {
                    button.classList.add("bg-blue-600", "hover:bg-blue-700");
                }
            });
        }

        function getFilteredDevices() {
            const roomValue = dom.roomSelect ? dom.roomSelect.value : "all";
            return dashboardDevices.filter((device) => roomValue === "all" || String(device.roomId) === roomValue);
        }

        function renderDeviceOptions(preferredId = "") {
            const devices = getFilteredDevices();
            dom.deviceSelect.innerHTML = "";

            if (devices.length === 0) {
                const option = document.createElement("option");
                option.value = "";
                option.textContent = "Chưa có node trong phòng này";
                dom.deviceSelect.appendChild(option);
                dom.deviceSelect.disabled = true;
                state.selectedDevice = null;
                updateSelectedDeviceInfo();
                resetRealtimePanel();
                applyChartData([], [], []);
                updateModeUI(false);
                setControlNotice("Phòng này chưa có node nào được cấu hình topic để theo dõi.", "danger");
                return;
            }

            dom.deviceSelect.disabled = false;

            devices.forEach((device) => {
                const option = document.createElement("option");
                option.value = String(device.id);
                option.textContent = `${device.name} - ${device.roomName}`;
                dom.deviceSelect.appendChild(option);
            });

            const targetId = devices.some((device) => String(device.id) === String(preferredId))
                ? String(preferredId)
                : String(devices[0].id);

            dom.deviceSelect.value = targetId;
            selectDevice(targetId);
        }

        async function loadChartHistory() {
            if (!state.selectedDevice) {
                applyChartData([], [], []);
                dom.chartStatus.textContent = "Chưa có node được chọn để tải lịch sử.";
                return;
            }

            dom.chartStatus.textContent = `Đang tải lịch sử 24 giờ của ${state.selectedDevice.name}...`;

            try {
                const response = await fetch(`api/sensor_history.php?deviceId=${encodeURIComponent(state.selectedDevice.id)}`, {
                    headers: {
                        Accept: "application/json"
                    }
                });

                if (!response.ok) {
                    throw new Error(`HTTP ${response.status}`);
                }

                const result = await response.json();
                if (!result.success) {
                    throw new Error(result.message || "Không thể tải dữ liệu biểu đồ.");
                }

                applyChartData(result.labels || [], result.datasets?.temp || [], result.datasets?.hum || []);
                dom.chartStatus.textContent = result.hasData
                    ? `Biểu đồ đang hiển thị lịch sử 24 giờ gần nhất của ${state.selectedDevice.name}.`
                    : `Chưa có lịch sử 24 giờ cho ${state.selectedDevice.name}. Biểu đồ sẽ đầy dần khi node gửi dữ liệu mới.`;
            } catch (error) {
                console.error("[CHART] Failed to load history:", error);
                applyChartData([], [], []);
                dom.chartStatus.textContent = "Không tải được lịch sử cảm biến của node đang chọn.";
            }
        }

        async function persistSensorData(payload) {
            const now = Date.now();
            if (!state.selectedDevice || !state.selectedDevice.sensorTopic) {
                return;
            }

            if (now - state.lastStoredAt < 60000) {
                return;
            }

            if (![payload.nhiet_do, payload.do_am, payload.gas, payload.pir].every(Number.isFinite)) {
                return;
            }

            state.lastStoredAt = now;

            try {
                await fetch("api/sensor_store.php", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        Accept: "application/json"
                    },
                    body: JSON.stringify({
                        topic: state.selectedDevice.sensorTopic,
                        ...payload
                    })
                });
            } catch (error) {
                console.error("[STORE] Failed to persist sensor data:", error);
            }
        }

        function updateLastUpdateLabel() {
            const now = new Date();
            dom.lastUpdateLabel.textContent = `Cập nhật lúc ${now.toLocaleTimeString("vi-VN", {
                hour: "2-digit",
                minute: "2-digit",
                second: "2-digit"
            })}`;
        }

        function upsertRealtimeChart(tempValue, humValue) {
            if (!Number.isFinite(tempValue) && !Number.isFinite(humValue)) {
                return;
            }

            const labels = chart.data.labels;
            const now = new Date();
            const currentLabel = `${String(now.getHours()).padStart(2, "0")}:${String(now.getMinutes()).padStart(2, "0")}`;
            let pointIndex = labels.lastIndexOf(currentLabel);

            if (pointIndex === -1) {
                labels.push(currentLabel);
                chart.data.datasets[0].data.push(null);
                chart.data.datasets[1].data.push(null);

                while (labels.length > 240) {
                    labels.shift();
                    chart.data.datasets[0].data.shift();
                    chart.data.datasets[1].data.shift();
                }

                pointIndex = labels.length - 1;
            }

            if (Number.isFinite(tempValue)) {
                chart.data.datasets[0].data[pointIndex] = Number(tempValue.toFixed(2));
            }

            if (Number.isFinite(humValue)) {
                chart.data.datasets[1].data[pointIndex] = Number(humValue.toFixed(2));
            }

            chart.update("none");
        }

        function syncSubscription() {
            if (!state.mqttConnected) {
                return;
            }

            const nextTopic = state.selectedDevice ? (state.selectedDevice.sensorTopic || "") : "";
            const prevTopic = state.currentSubscription;

            if (prevTopic && prevTopic !== nextTopic) {
                try {
                    client.unsubscribe(prevTopic);
                } catch (error) {
                    console.error("[MQTT] Failed to unsubscribe:", error);
                }
            }

            state.currentSubscription = nextTopic;

            if (!nextTopic) {
                setControlNotice("Node đang chọn chưa có topic sensor nên chưa thể giám sát realtime.", "danger");
                return;
            }

            try {
                client.subscribe(nextTopic);
                setControlNotice(`Đang giám sát realtime topic ${nextTopic}.`, "neutral");
            } catch (error) {
                console.error("[MQTT] Failed to subscribe:", error);
                setControlNotice("Không subscribe được topic của node đang chọn.", "danger");
            }
        }

        function selectDevice(deviceId) {
            const device = dashboardDevices.find((item) => String(item.id) === String(deviceId)) || null;
            state.selectedDevice = device;
            state.lastStoredAt = 0;
            dom.masterSwitch.checked = false;
            updateSelectedDeviceInfo();
            resetRealtimePanel();
            updateModeUI(false);
            loadChartHistory();

            if (!device) {
                setControlNotice("Không tìm thấy node để giám sát.", "danger");
                return;
            }

            if (state.mqttConnected) {
                syncSubscription();
            }

            if (!device.sensorTopic || !device.controlTopic) {
                setControlNotice("Node đang chọn chưa cấu hình đủ topic sensor/control.", "danger");
                return;
            }

            setControlNotice(`Đã chuyển sang giám sát ${device.name}. MQTT sẽ dùng topic của node này.`, "success");
        }

        function publishMessage(payload) {
            if (!state.selectedDevice) {
                setControlNotice("Bạn cần chọn node trước khi gửi lệnh.", "danger");
                return false;
            }

            if (!state.selectedDevice.controlTopic) {
                setControlNotice("Node đang chọn chưa có topic điều khiển.", "danger");
                return false;
            }

            if (!state.selectedDevice.isOnline) {
                setControlNotice("Node đang chọn đang offline, chưa nên gửi lệnh.", "danger");
                return false;
            }

            if (!state.mqttConnected) {
                setControlNotice("MQTT đang mất kết nối. Hãy chờ kết nối lại trước khi gửi lệnh.", "danger");
                return false;
            }

            try {
                const msg = new Paho.MQTT.Message(JSON.stringify(payload));
                msg.destinationName = state.selectedDevice.controlTopic;
                client.send(msg);
                return true;
            } catch (error) {
                console.error("[MQTT] Failed to send message:", error);
                setControlNotice("Không gửi được lệnh đến node. Vui lòng thử lại sau.", "danger");
                return false;
            }
        }

        function sendControl(controlName) {
            if (!dom.masterSwitch.checked) {
                setControlNotice("Bạn cần bật chế độ điều khiển tay trước khi thao tác bật/tắt thiết bị.", "danger");
                return;
            }

            if (publishMessage({ control: controlName })) {
                setControlNotice(`Đã gửi lệnh ${controlName} đến ${state.selectedDevice.name}.`, "success");
            }
        }

        function connectMqtt() {
            setBadgeState("warning", "Đang kết nối MQTT");

            client.connect({
                useSSL: false,
                keepAliveInterval: 30,
                onSuccess: function() {
                    state.mqttConnected = true;
                    setBadgeState("success", "MQTT đã sẵn sàng");
                    syncSubscription();
                },
                onFailure: function(error) {
                    state.mqttConnected = false;
                    console.error("[MQTT] Connection failed:", error.errorMessage);
                    setBadgeState("danger", "MQTT mất kết nối");
                    setControlNotice("Không kết nối được MQTT. Hệ thống sẽ tự thử lại sau vài giây.", "danger");
                    window.setTimeout(connectMqtt, 3000);
                }
            });
        }

        client.onConnectionLost = function(respObj) {
            state.mqttConnected = false;
            state.currentSubscription = "";
            if (respObj.errorCode !== 0) {
                console.error("[MQTT] Connection lost:", respObj.errorMessage);
            }
            setBadgeState("danger", "MQTT mất kết nối");
            setControlNotice("Kết nối MQTT vừa bị ngắt. Hệ thống đang tự kết nối lại...", "danger");
            window.setTimeout(connectMqtt, 3000);
        };

        client.onMessageArrived = function(message) {
            if (!state.selectedDevice || message.destinationName !== state.selectedDevice.sensorTopic) {
                return;
            }

            try {
                const data = JSON.parse(message.payloadString);
                const tempValue = Number(data.nhiet_do);
                const humValue = Number(data.do_am);
                const gasValue = Number(data.gas);
                const pirValue = Number(data.pir);

                state.selectedDevice.isOnline = true;
                state.selectedDevice.lastHeartbeatTs = Math.floor(Date.now() / 1000);
                updateSelectedDeviceInfo();
                updateModeUI(dom.masterSwitch.checked);

                if (Number.isFinite(tempValue)) {
                    dom.tempValue.textContent = `${tempValue.toFixed(1)} °C`;
                }

                if (Number.isFinite(humValue)) {
                    dom.humValue.textContent = `${humValue.toFixed(1)} %`;
                }

                if (Number.isFinite(gasValue)) {
                    dom.gasValue.textContent = `${gasValue} ppm`;
                }

                if (data.pir !== undefined) {
                    if (pirValue === 1) {
                        dom.pirValue.textContent = "Phát hiện chuyển động";
                        dom.pirValue.className = "text-lg font-bold text-red-600";
                        dom.pirDetail.textContent = "Có chuyển động bất thường trong vùng giám sát";
                        dom.pirDetail.className = "mt-2 text-base font-bold text-red-600";
                    } else {
                        dom.pirValue.textContent = "Bình thường";
                        dom.pirValue.className = "text-lg font-bold text-emerald-600";
                        dom.pirDetail.textContent = "Không phát hiện chuyển động";
                        dom.pirDetail.className = "mt-2 text-base font-semibold text-emerald-600";
                    }
                }

                if (data.relay !== undefined) {
                    const relayOn = Number(data.relay) === 1;
                    dom.relayStatus.textContent = relayOn ? "Đang bật" : "Đang tắt";
                    dom.relayStatus.className = relayOn
                        ? "mt-2 text-2xl font-bold text-emerald-600"
                        : "mt-2 text-2xl font-bold text-gray-800 dark:text-gray-100";
                    updateDeviceState('[data-control="relay_on"]', relayOn, "ring-green-400");
                    updateDeviceState('[data-control="relay_off"]', !relayOn, "ring-red-400");
                }

                if (data.buzzer !== undefined) {
                    const buzzerOn = Number(data.buzzer) === 1;
                    dom.buzzerStatus.textContent = buzzerOn ? "Đang kêu" : "Đã tắt";
                    dom.buzzerStatus.className = buzzerOn
                        ? "mt-2 text-2xl font-bold text-rose-600"
                        : "mt-2 text-2xl font-bold text-gray-800 dark:text-gray-100";
                    updateDeviceState('[data-control="buzzer_on"]', buzzerOn, "ring-red-400");
                    updateDeviceState('[data-control="buzzer_stop"]', !buzzerOn, "ring-green-400");
                }

                if (data.mode !== undefined) {
                    const isManual = Number(data.mode) === 1;
                    dom.masterSwitch.checked = isManual;
                    updateModeUI(isManual);
                }

                upsertRealtimeChart(tempValue, humValue);
                persistSensorData({
                    nhiet_do: tempValue,
                    do_am: humValue,
                    gas: gasValue,
                    pir: pirValue
                });
                updateLastUpdateLabel();
            } catch (error) {
                console.error("[MQTT] Failed to parse message:", error);
            }
        };

        if (dom.roomSelect) {
            dom.roomSelect.addEventListener("change", function() {
                renderDeviceOptions();
            });
        }

        if (dom.deviceSelect) {
            dom.deviceSelect.addEventListener("change", function() {
                selectDevice(this.value);
            });
        }

        dom.masterSwitch.addEventListener("change", function() {
            const isManual = this.checked;
            const previousState = !isManual;
            updateModeUI(isManual);

            if (!publishMessage({ mode: isManual ? 1 : 0 })) {
                this.checked = previousState;
                updateModeUI(previousState);
                return;
            }

            setControlNotice(
                isManual
                    ? `Đã gửi lệnh chuyển ${state.selectedDevice.name} sang điều khiển tay.`
                    : `Đã trả ${state.selectedDevice.name} về chế độ tự động.`,
                "success"
            );
        });

        dom.buttons.forEach((button) => {
            button.addEventListener("click", function() {
                sendControl(this.dataset.control);
            });
        });

        dom.chartButtons.forEach((button) => {
            button.addEventListener("click", function() {
                const datasetKey = this.dataset.dataset;
                const datasetIndex = datasetMap[datasetKey];
                state.datasetsVisible[datasetKey] = !state.datasetsVisible[datasetKey];
                chart.setDatasetVisibility(datasetIndex, state.datasetsVisible[datasetKey]);
                chart.update();
                refreshChartToggleButtons();
            });
        });

        refreshChartToggleButtons();
        resetRealtimePanel();
        updateModeUI(false);
        renderDeviceOptions();
        connectMqtt();
        window.setInterval(refreshDeviceOnlineStates, 1000);
    });
</script>

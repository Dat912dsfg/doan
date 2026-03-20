<h2 class="my-6 text-2xl font-semibold text-gray-700 dark:text-gray-200">
    Tổng quan hệ thống
</h2>

<div class="grid gap-6 mb-8 md:grid-cols-2 xl:grid-cols-4">
    <div class="flex items-center p-4 bg-white rounded-lg shadow-xs dark:bg-gray-800 border-b-4 border-blue-500">
        <div class="p-3 mr-4 text-blue-500 bg-blue-100 rounded-full dark:text-blue-100 dark:bg-blue-500">
            <i class="fas fa-temperature-high text-xl w-5 h-5 flex items-center justify-center"></i>
        </div>
        <div>
            <p class="mb-2 text-sm font-medium text-gray-600 dark:text-gray-400">Nhiệt độ (DHT11)</p>
            <p class="text-lg font-semibold text-gray-700 dark:text-gray-200" id="val-nhietdo">-- °C</p>
        </div>
    </div>

    <div class="flex items-center p-4 bg-white rounded-lg shadow-xs dark:bg-gray-800 border-b-4 border-teal-500">
        <div class="p-3 mr-4 text-teal-500 bg-teal-100 rounded-full dark:text-teal-100 dark:bg-teal-500">
            <i class="fas fa-tint text-xl w-5 h-5 flex items-center justify-center"></i>
        </div>
        <div>
            <p class="mb-2 text-sm font-medium text-gray-600 dark:text-gray-400">Độ ẩm (DHT11)</p>
            <p class="text-lg font-semibold text-gray-700 dark:text-gray-200" id="val-doam">-- %</p>
        </div>
    </div>

    <div class="flex items-center p-4 bg-white rounded-lg shadow-xs dark:bg-gray-800 border-b-4 border-orange-500">
        <div class="p-3 mr-4 text-orange-500 bg-orange-100 rounded-full dark:text-orange-100 dark:bg-orange-500">
            <i class="fas fa-smog text-xl w-5 h-5 flex items-center justify-center"></i>
        </div>
        <div>
            <p class="mb-2 text-sm font-medium text-gray-600 dark:text-gray-400">Gas / Khói (MQ-2)</p>
            <p class="text-lg font-semibold text-gray-700 dark:text-gray-200" id="val-gas">-- ppm</p>
        </div>
    </div>

    <div class="flex items-center p-4 bg-white rounded-lg shadow-xs dark:bg-gray-800 border-b-4 border-purple-500">
        <div class="p-3 mr-4 text-purple-500 bg-purple-100 rounded-full dark:text-purple-100 dark:bg-purple-500">
            <i class="fas fa-walking text-xl w-5 h-5 flex items-center justify-center"></i>
        </div>
        <div>
            <p class="mb-2 text-sm font-medium text-gray-600 dark:text-gray-400">Chuyển động (PIR)</p>
            <p class="text-lg font-semibold text-gray-700 dark:text-gray-200" id="val-pir">Đang kiểm tra...</p>
        </div>
    </div>
</div>

<div class="grid gap-6 mb-8 md:grid-cols-2">
    <div class="min-w-0 p-4 bg-white rounded-lg shadow-xs dark:bg-gray-800">
        <h4 class="mb-4 font-semibold text-gray-800 dark:text-gray-300">Điều khiển & Trạng thái thiết bị</h4>
        
        <div class="flex items-center justify-between p-4 mb-6 bg-purple-50 rounded-lg dark:bg-gray-700">
            <div>
                <p class="font-semibold text-purple-700 dark:text-purple-300">Chế độ hệ thống</p>
                <p class="text-xs text-gray-600 dark:text-gray-400" id="modeLabel">AUTO</p>
            </div>
            <label class="relative inline-flex items-center cursor-pointer">
                <input type="checkbox" id="masterSwitch" class="sr-only peer">
                <div class="w-11 h-6 bg-gray-200 rounded-full peer peer-checked:bg-blue-600 transition-all duration-300"></div>
                <div class="absolute left-0.5 top-0.5 w-5 h-5 bg-white rounded-full shadow-sm peer-checked:translate-x-5 transition-all duration-300"></div>
            </label>
        </div>

        <div class="w-full overflow-x-auto">
            <table class="w-full whitespace-no-wrap">
                <tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800" id="controlTable">
                    <tr class="text-gray-700 dark:text-gray-400 opacity-50 transition-opacity">
                        <td class="px-4 py-3 text-sm flex items-center">
                            <div class="p-2 mr-3 rounded-full bg-blue-100 dark:bg-blue-900 text-blue-500">
                                <i class="fas fa-toggle-on"></i>
                            </div>
                            <div>
                                <span class="font-medium block">Relay</span>
                                <span class="text-xs" id="status-relay">Ngoại tuyến</span>
                            </div>
                        </td>
                        <td class="px-4 py-3 text-right">
                            <button id="btn-relay" disabled class="btn-device px-4 py-2 text-xs font-medium text-white bg-gray-400 rounded-md">
                                BẬT/TẮT
                            </button>
                        </td>
                    </tr>
                    <tr class="text-gray-700 dark:text-gray-400 opacity-50 transition-opacity">
                        <td class="px-4 py-3 text-sm flex items-center">
                            <div class="p-2 mr-3 rounded-full bg-red-100 dark:bg-red-900 text-red-500">
                                <i class="fas fa-bell"></i>
                            </div>
                            <div>
                                <span class="font-medium block">Còi báo động</span>
                                <span class="text-xs" id="status-buzzer">Im lặng</span>
                            </div>
                        </td>
                        <td class="px-4 py-3 text-right">
                            <button id="btn-buzzer" disabled class="btn-device px-4 py-2 text-xs font-medium text-white bg-gray-400 rounded-md">
                                TẮT CÒI NGAY
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <div class="min-w-0 p-4 bg-white rounded-lg shadow-xs dark:bg-gray-800">
        <h4 class="mb-4 font-semibold text-gray-800 dark:text-gray-300">Xu hướng môi trường (24h)</h4>
        <div class="relative w-full h-64">
            <canvas id="lineChart"></canvas>
        </div>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/paho-mqtt/1.0.1/mqttws31.min.js"></script>

<script>
document.addEventListener("DOMContentLoaded", function() {

    const mqtt_broker = "broker.hivemq.com"; // FIX
    const mqtt_port = 8000; // FIX WebSocket
    const topic_sub = "nhatro123/room1/sensor"; // FIX đúng ESP32
    const topic_pub = "nhatro123/room1/control";

    const clientId = "Web-Client-" + Math.random().toString(16).substr(2, 8);
    const client = new Paho.MQTT.Client(mqtt_broker, mqtt_port, clientId);

    const options = {
        onSuccess: () => {
            console.log("✅ MQTT Connected");
            client.subscribe(topic_sub);
        },
        onFailure: (msg) => {
            console.error("❌ MQTT Fail: " + msg.errorMessage);
        }
    };

    client.onMessageArrived = function(message) {
        try {
            const data = JSON.parse(message.payloadString);
            console.log("DATA MQTT:", data);

            // 🌡 NHIỆT ĐỘ
            if (data.temp !== undefined) {
                document.getElementById("val-nhietdo").innerText =
                    parseFloat(data.temp).toFixed(1) + "°C";
            }

            // 💧 ĐỘ ẨM
            if (data.hum !== undefined) {
                document.getElementById("val-doam").innerText =
                    parseFloat(data.hum).toFixed(1) + "%";
            }

            // 🔥 GAS
            if (data.gas !== undefined) {
                const gasEl = document.getElementById("val-gas");
                gasEl.innerText = data.gas + " ppm";

                if (data.gas > 300) {
                    gasEl.style.color = "red";
                } else {
                    gasEl.style.color = ""; // FIX reset màu
                }
            }

            // 🚶 PIR
            if (data.pir !== undefined) {
                const pirTxt = document.getElementById("val-pir");

                if (data.pir == 1) {
                    pirTxt.innerText = "🚨 PHÁT HIỆN CHUYỂN ĐỘNG";
                    pirTxt.className = "text-lg font-bold text-red-600";
                } else {
                    pirTxt.innerText = "Bình thường";
                    pirTxt.className = "text-lg font-semibold text-gray-700 dark:text-gray-200";
                }
            }

            // ⚡ RELAY
            if (data.relay !== undefined) {
                document.getElementById("status-relay").innerText =
                    data.relay == 1 ? "Đang bật" : "Đang tắt";
            }

            // 🔔 BUZZER
            if (data.buzzer !== undefined) {
                document.getElementById("status-buzzer").innerText =
                    data.buzzer == 1 ? "ĐANG KÊU!" : "Im lặng";
            }

            // 🔥 LƯU DATABASE
            fetch("insert_data.php", {
                method: "POST",
                headers: {"Content-Type": "application/json"},
                body: JSON.stringify(data)
            });

        } catch (e) {
            console.error("❌ Data error:", e);
        }
    };

    client.connect(options);

    // ================= CONTROL =================
    const masterSwitch = document.getElementById('masterSwitch');
    const modeLabel = document.getElementById('modeLabel');
    const tableRows = document.querySelectorAll('#controlTable tr');
    const buttons = document.querySelectorAll('.btn-device');

    masterSwitch.addEventListener('change', function() {
        const isManual = this.checked;
        modeLabel.innerText = isManual ? "MANUAL" : "AUTO";
        
        tableRows.forEach(r => isManual ? r.classList.remove('opacity-50') : r.classList.add('opacity-50'));
        buttons.forEach(btn => {
            btn.disabled = !isManual;
            if(isManual) {
                btn.classList.replace('bg-gray-400', 'bg-blue-600');
                btn.classList.add('hover:bg-blue-700');
            } else {
                btn.classList.replace('bg-blue-600', 'bg-gray-400');
                btn.classList.remove('hover:bg-blue-700');
            }
        });
        
        const msg = new Paho.MQTT.Message(JSON.stringify({ mode: isManual ? 1 : 0 }));
        msg.destinationName = topic_pub;
        client.send(msg);
    });

    document.getElementById('btn-relay').addEventListener('click', () => {
        const msg = new Paho.MQTT.Message(JSON.stringify({ control: "relay_toggle" }));
        msg.destinationName = topic_pub;
        client.send(msg);
    });

    document.getElementById('btn-buzzer').addEventListener('click', () => {
        const msg = new Paho.MQTT.Message(JSON.stringify({ control: "buzzer_stop" }));
        msg.destinationName = topic_pub;
        client.send(msg);
    });

    // ================= CHART (LẤY DB) =================
    const ctx = document.getElementById('lineChart').getContext('2d');

    fetch("get_chart.php")
    .then(res => res.json())
    .then(data => {

        const labels = data.map(x => x.time);
        const temp = data.map(x => x.temp);
        const hum = data.map(x => x.hum);

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [
                    { label: 'Nhiệt độ', data: temp, borderColor: '#ef4444', tension: 0.4 },
                    { label: 'Độ ẩm', data: hum, borderColor: '#3b82f6', tension: 0.4 }
                ]
            },
            options: { responsive: true, maintainAspectRatio: false }
        });

    });

});
</script>
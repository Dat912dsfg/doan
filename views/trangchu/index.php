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
        // ===== MQTT CONFIG =====
        const mqtt_broker = "broker.hivemq.com";  // Public HiveMQ broker
        const mqtt_port = 8000;                    // WebSocket port
        const topic_sub = "nhatro123/room1/sensor";
        const topic_pub = "nhatro123/room1/control";
        
        const clientId = "WebClient-" + Math.random().toString(16).substr(2, 8);
        const client = new Paho.MQTT.Client(mqtt_broker, mqtt_port, clientId);

        // ===== CONNECTION OPTIONS =====
        const options = {
            useSSL: false,
            keepAliveInterval: 30,
            onSuccess: onConnected,
            onFailure: onConnectionFailed
        };

        // ===== CONNECTION CALLBACKS =====
        function onConnected() {
            console.log("[MQTT] Connected successfully");
            client.subscribe(topic_sub);
            console.log("[MQTT] Subscribed to: " + topic_sub);
        }

        function onConnectionFailed(errorMsg) {
            console.error("[MQTT] Connection failed: " + errorMsg.errorMessage);
        }

        client.onConnectionLost = function(respObj) {
            if (respObj.errorCode !== 0) {
                console.error("[MQTT] Connection lost: " + respObj.errorMessage);
            }
        };

        // ===== MESSAGE HANDLER =====
        client.onMessageArrived = function(message) {
            try {
                const data = JSON.parse(message.payloadString);
                
                // Update sensor values
                if(data.nhiet_do !== undefined) {
                    document.getElementById("val-nhietdo").innerText = data.nhiet_do.toFixed(1) + "°C";
                }
                if(data.do_am !== undefined) {
                    document.getElementById("val-doam").innerText = data.do_am.toFixed(1) + "%";
                }
                if(data.gas !== undefined) {
                    document.getElementById("val-gas").innerText = data.gas + " ppm";
                }
                
                // Update PIR sensor
                if(data.pir !== undefined) {
                    const pirTxt = document.getElementById("val-pir");
                    if(data.pir === 1) {
                        pirTxt.innerText = "🚨 PHÁT HIỆN XÂM NHẬP";
                        pirTxt.className = "text-lg font-bold text-red-600 animate-pulse";
                    } else {
                        pirTxt.innerText = "✓ Bình thường";
                        pirTxt.className = "text-lg font-semibold text-gray-700 dark:text-gray-200";
                    }
                }

                // Update device status
                if(data.relay !== undefined) {
                    const relayStatus = document.getElementById("status-relay");
                    relayStatus.innerText = data.relay === 1 ? "✓ Đang bật" : "✗ Đang tắt";
                    updateButtonStyle('btn-relay', data.relay === 1);
                }
                if(data.buzzer !== undefined) {
                    const buzzerStatus = document.getElementById("status-buzzer");
                    buzzerStatus.innerText = data.buzzer === 1 ? "🔔 ĐANG KÊU!" : "🔇 Im lặng";
                    if(data.buzzer === 1) buzzerStatus.classList.add('animate-pulse');
                    else buzzerStatus.classList.remove('animate-pulse');
                }

                // Update mode display
                if(data.mode !== undefined) {
                    const masterSwitch = document.getElementById('masterSwitch');
                    masterSwitch.checked = (data.mode === 1);
                    updateModeUI(data.mode === 1);
                }
            } catch (e) { 
                console.error("[ERROR] Failed to parse message:", e); 
            }
        };

        // ===== CONNECT TO MQTT =====
        client.connect(options);

        // ===== UI ELEMENTS =====
        const masterSwitch = document.getElementById('masterSwitch');
        const modeLabel = document.getElementById('modeLabel');
        const tableRows = document.querySelectorAll('#controlTable tr');
        const buttons = document.querySelectorAll('.btn-device');

        // ===== UPDATE MODE UI =====
        function updateModeUI(isManual) {
            modeLabel.innerText = isManual ? "MANUAL" : "AUTO";
            
            tableRows.forEach(r => {
                isManual ? r.classList.remove('opacity-50') : r.classList.add('opacity-50');
            });
            
            buttons.forEach(btn => {
                btn.disabled = !isManual;
                if(isManual) {
                    btn.classList.remove('bg-gray-400');
                    btn.classList.add('bg-blue-600', 'hover:bg-blue-700', 'cursor-pointer');
                } else {
                    btn.classList.remove('bg-blue-600', 'hover:bg-blue-700', 'cursor-pointer');
                    btn.classList.add('bg-gray-400');
                }
            });
        }

        // ===== UPDATE BUTTON STYLE =====
        function updateButtonStyle(btnId, isActive) {
            const btn = document.getElementById(btnId);
            if(isActive) {
                btn.classList.add('ring-2', 'ring-green-400');
            } else {
                btn.classList.remove('ring-2', 'ring-green-400');
            }
        }

        // ===== MODE SWITCH =====
        masterSwitch.addEventListener('change', function() {
            const isManual = this.checked;
            updateModeUI(isManual);
            
            const msg = new Paho.MQTT.Message(JSON.stringify({ mode: isManual ? 1 : 0 }));
            msg.destinationName = topic_pub;
            client.send(msg);
            console.log("[CONTROL] Mode changed to: " + (isManual ? "MANUAL" : "AUTO"));
        });

        // ===== RELAY CONTROL =====
        document.getElementById('btn-relay').addEventListener('click', function() {
            if(!masterSwitch.checked) {
                console.warn("[WARNING] Manual mode not active");
                return;
            }
            
            const msg = new Paho.MQTT.Message(JSON.stringify({ control: "relay_toggle" }));
            msg.destinationName = topic_pub;
            client.send(msg);
            console.log("[CONTROL] Relay toggle command sent");
        });

        // ===== BUZZER CONTROL =====
        document.getElementById('btn-buzzer').addEventListener('click', function() {
            if(!masterSwitch.checked) {
                console.warn("[WARNING] Manual mode not active");
                return;
            }
            
            const msg = new Paho.MQTT.Message(JSON.stringify({ control: "buzzer_stop" }));
            msg.destinationName = topic_pub;
            client.send(msg);
            console.log("[CONTROL] Buzzer stop command sent");
        });

        // ===== CHART INITIALIZATION =====
        const ctx = document.getElementById('lineChart').getContext('2d');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: ['0h', '4h', '8h', '12h', '16h', '20h'],
                datasets: [
                    { 
                        label: 'Nhiệt độ (°C)', 
                        data: [26, 25, 28, 32, 30, 27], 
                        borderColor: '#ef4444', 
                        backgroundColor: 'rgba(239, 68, 68, 0.1)',
                        tension: 0.4, 
                        fill: true
                    },
                    { 
                        label: 'Độ ẩm (%)', 
                        data: [70, 72, 68, 60, 62, 68], 
                        borderColor: '#3b82f6', 
                        backgroundColor: 'rgba(59, 130, 246, 0.1)',
                        tension: 0.4, 
                        fill: true
                    }
                ]
            },
            options: { 
                responsive: true, 
                maintainAspectRatio: false,
                plugins: { 
                    legend: { 
                        display: true,
                        position: 'top'
                    } 
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        max: 100
                    }
                }
            }
        });

        console.log("[INIT] IoT Dashboard initialized successfully");
    });
</script>
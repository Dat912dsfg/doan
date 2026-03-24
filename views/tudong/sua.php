<?php
$kichBan = $kichBan ?? [];

function td_parse_payload_edit($payload) {
    $result = ['mode' => 'raw', 'data' => [], 'text' => (string) $payload];
    if (!is_string($payload) || strpos($payload, '|') === false) {
        return $result;
    }

    $parts = explode('|', $payload);
    $mode = trim(array_shift($parts));
    if (!in_array($mode, ['sensor', 'timer', 'action'], true)) {
        return $result;
    }

    $data = [];
    foreach ($parts as $part) {
        $pair = explode('=', $part, 2);
        if (count($pair) === 2) {
            $data[trim($pair[0])] = trim($pair[1]);
        }
    }

    return ['mode' => $mode, 'data' => $data, 'text' => $payload];
}

$condition = td_parse_payload_edit($kichBan['dieuKien'] ?? '');
$action = td_parse_payload_edit($kichBan['hanhDong'] ?? '');

$mode = $condition['mode'] === 'timer' ? 'timer' : 'sensor';
$sensorType = $condition['data']['type'] ?? 'temp';
$sensorOp = $condition['data']['op'] ?? '>';
$sensorValue = $condition['data']['value'] ?? '30';
$timerStart = $condition['data']['start'] ?? ($kichBan['gioBatDau'] ?? '18:00');
$timerEnd = $condition['data']['end'] ?? ($kichBan['gioKetThuc'] ?? '06:00');
$timerDays = !empty($condition['data']['days']) ? explode(',', $condition['data']['days']) : ['T2', 'T3', 'T4', 'T5', 'T6'];
$actionDevice = $action['data']['device'] ?? 'relay';
$actionState = $action['data']['state'] ?? 'ON';
?>

<div class="flex flex-col items-start justify-between w-full gap-4 my-6 sm:flex-row sm:items-center">
    <h2 class="text-2xl font-semibold text-gray-700 dark:text-gray-200">
        Sửa kịch bản tự động
    </h2>
    <a href="index.php?page=tudong"
        class="inline-flex items-center px-4 py-2 text-sm font-medium leading-5 text-gray-700 transition-colors duration-150 bg-white border border-gray-300 rounded-lg hover:text-gray-800 hover:border-gray-400 focus:outline-none focus:shadow-outline-blue dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-gray-300 dark:hover:border-gray-500 flex-shrink-0">
        <i class="fas fa-arrow-left mr-2"></i> Quay lại
    </a>
</div>

<div class="w-full max-w-3xl mx-auto mb-8">
    <div class="min-w-0 p-6 bg-white rounded-xl shadow-sm dark:bg-gray-800 border border-gray-200 dark:border-gray-700">

        <div class="mb-6 border-b border-gray-200 dark:border-gray-700">
            <ul class="flex flex-wrap -mb-px text-sm font-medium text-center" id="scriptTabs" role="tablist">
                <li class="mr-2">
                    <button type="button" class="inline-block p-4 border-b-2 rounded-t-lg transition-colors duration-150"
                            id="sensor-tab"
                            onclick="switchTab('sensor')">
                        <i class="fas fa-microchip text-blue-600 mr-2"></i> Theo cảm biến
                    </button>
                </li>
                <li class="mr-2">
                    <button type="button" class="inline-block p-4 border-b-2 border-transparent rounded-t-lg hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300 transition-colors duration-150"
                            id="timer-tab"
                            onclick="switchTab('timer')">
                        <i class="fas fa-clock text-yellow-600 mr-2"></i> Theo thời gian
                    </button>
                </li>
            </ul>
        </div>

        <form id="automationEditForm" action="index.php?page=tudong-update" method="POST" class="space-y-6">
            <input type="hidden" name="idKichBan" value="<?= intval($kichBan['idKichBan'] ?? 0) ?>">
            <input type="hidden" name="dieuKien" id="dieuKienPayload" value="<?= htmlspecialchars($kichBan['dieuKien'] ?? '') ?>">
            <input type="hidden" name="hanhDong" id="hanhDongPayload" value="<?= htmlspecialchars($kichBan['hanhDong'] ?? '') ?>">
            <input type="hidden" name="moTa" id="moTaPayload" value="<?= htmlspecialchars($kichBan['moTa'] ?? '') ?>">
            <input type="hidden" name="kichHoat" value="<?= !empty($kichBan['kichHoat']) ? '1' : '0' ?>">

            <label class="block text-sm">
                <span class="text-gray-700 dark:text-gray-400 font-bold uppercase text-xs">1. Tên kịch bản</span>
                <input type="text" id="scriptName" name="tenKichBan" value="<?= htmlspecialchars($kichBan['tenKichBan'] ?? '') ?>"
                        class="block w-full mt-1 text-sm dark:bg-gray-700 dark:text-gray-300 form-input focus:border-blue-400 focus:shadow-outline-blue dark:focus:shadow-outline-gray p-2 border rounded"
                        placeholder="Ví dụ: Cảnh báo rò rỉ Gas" required>
            </label>

            <div id="sensor-tab-content" class="tab-content space-y-4">
                <div class="p-4 bg-gray-50 rounded-lg border border-gray-200 dark:bg-gray-700 dark:border-gray-600 shadow-inner">
                    <h6 class="text-[10px] font-black text-blue-600 uppercase tracking-widest mb-4">ĐIỀU KIỆN KÍCH HOẠT (NẾU)</h6>

                    <div class="grid gap-4 md:grid-cols-12">
                        <div class="md:col-span-5">
                            <label class="block text-xs text-gray-500 dark:text-gray-400 mb-1">Cảm biến nguồn</label>
                            <select id="sensorType" name="sensorType" onchange="updateUnit()" class="block w-full text-sm dark:bg-gray-800 dark:text-gray-300 form-select focus:border-blue-400 h-10 border rounded px-2">
                                <option value="temp" <?= $sensorType === 'temp' ? 'selected' : '' ?>>Nhiệt độ (DHT11)</option>
                                <option value="hum" <?= $sensorType === 'hum' ? 'selected' : '' ?>>Độ ẩm (DHT11)</option>
                                <option value="gas" <?= $sensorType === 'gas' ? 'selected' : '' ?>>Khí Gas (MQ-2)</option>
                                <option value="pir" <?= $sensorType === 'pir' ? 'selected' : '' ?>>Chuyển động (PIR)</option>
                            </select>
                        </div>
                        <div class="md:col-span-3">
                            <label class="block text-xs text-gray-500 dark:text-gray-400 mb-1">So sánh</label>
                            <select id="sensorOperator" name="sensorOperator" class="block w-full text-sm dark:bg-gray-800 dark:text-gray-300 form-select focus:border-blue-400 h-10 border rounded px-2">
                                <option value=">" <?= $sensorOp === '>' ? 'selected' : '' ?>>Lớn hơn (>)</option>
                                <option value="<" <?= $sensorOp === '<' ? 'selected' : '' ?>>Nhỏ hơn (<)</option>
                                <option value="=" <?= $sensorOp === '=' ? 'selected' : '' ?>>Bằng (=)</option>
                            </select>
                        </div>
                        <div class="md:col-span-4">
                            <label class="block text-xs text-gray-500 dark:text-gray-400 mb-1">Ngưỡng</label>
                            <div class="relative">
                                <input type="number" id="sensorThreshold" name="sensorThreshold" value="<?= htmlspecialchars($sensorValue) ?>" class="block w-full pr-12 text-sm dark:bg-gray-800 dark:text-gray-300 form-input h-10 border rounded px-2">
                                <div class="absolute inset-y-0 right-0 flex items-center px-3 bg-gray-100 dark:bg-gray-600 rounded-r border-l dark:border-gray-500">
                                    <span id="unitLabel" class="text-xs font-bold text-gray-500 dark:text-gray-300">°C</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div id="timer-tab-content" class="tab-content space-y-4 hidden">
                <div class="p-4 bg-gray-50 rounded-lg border border-gray-200 dark:bg-gray-700 dark:border-gray-600 shadow-inner">
                    <h6 class="text-[10px] font-black text-yellow-600 uppercase tracking-widest mb-4">THIẾT LẬP THỜI GIAN (NẾU)</h6>

                    <div class="space-y-4">
                        <div class="grid gap-4 md:grid-cols-2">
                            <div>
                                <label class="block text-xs text-gray-500 dark:text-gray-400 mb-1">Giờ Bật</label>
                                <input type="time" id="gioBatDau" name="gioBatDau" class="block w-full text-sm dark:bg-gray-800 dark:text-gray-300 form-input h-10 border rounded px-2" value="<?= htmlspecialchars($timerStart) ?>">
                            </div>
                            <div>
                                <label class="block text-xs text-gray-500 dark:text-gray-400 mb-1">Giờ Tắt</label>
                                <input type="time" id="gioKetThuc" name="gioKetThuc" class="block w-full text-sm dark:bg-gray-800 dark:text-gray-300 form-input h-10 border rounded px-2" value="<?= htmlspecialchars($timerEnd) ?>">
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs text-gray-500 dark:text-gray-400 mb-2">Lặp lại các ngày trong tuần</label>
                            <div class="flex flex-wrap gap-2">
                                <?php $days = ['T2', 'T3', 'T4', 'T5', 'T6', 'T7', 'CN']; ?>
                                <?php foreach ($days as $day): ?>
                                    <label class="day-checkbox">
                                        <input type="checkbox" name="days[]" value="<?= $day ?>" class="sr-only peer" <?= in_array($day, $timerDays, true) ? 'checked' : '' ?>>
                                        <span class="w-9 h-9 flex items-center justify-center text-xs font-bold border rounded-lg cursor-pointer peer-checked:bg-blue-600 peer-checked:text-white peer-checked:border-blue-600 dark:bg-gray-800 dark:border-gray-600 transition-all"><?= $day ?></span>
                                    </label>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="p-4 bg-white rounded-lg border border-green-200 dark:bg-gray-800 dark:border-green-900 shadow-sm">
                <h6 class="text-[10px] font-black text-green-600 uppercase tracking-widest mb-4">HÀNH ĐỘNG THỰC THI (THÌ)</h6>
                <div class="grid gap-4 md:grid-cols-2">
                    <div>
                        <label class="block text-xs text-gray-500 dark:text-gray-400 mb-1">Thiết bị chấp hành</label>
                        <select id="actionDevice" name="actionDevice" class="block w-full text-sm dark:bg-gray-800 dark:text-gray-300 form-select focus:border-green-400 h-10 border rounded px-2">
                            <option value="relay" <?= $actionDevice === 'relay' ? 'selected' : '' ?>>Relay (Quạt/Phun sương)</option>
                            <option value="buzzer" <?= $actionDevice === 'buzzer' ? 'selected' : '' ?>>Còi báo động (Buzzer)</option>
                            <option value="all" <?= $actionDevice === 'all' ? 'selected' : '' ?>>Tất cả thiết bị</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs text-gray-500 dark:text-gray-400 mb-1">Trạng thái mong muốn</label>
                        <select id="actionState" name="actionState" class="block w-full text-sm dark:bg-gray-800 dark:text-green-400 form-select focus:border-green-400 h-10 border rounded px-2 font-bold">
                            <option value="ON" <?= strtoupper($actionState) === 'ON' ? 'selected' : '' ?>>KÍCH HOẠT (ON)</option>
                            <option value="OFF" <?= strtoupper($actionState) === 'OFF' ? 'selected' : '' ?>>NGẮT KẾT NỐI (OFF)</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="flex justify-end gap-3 pt-6 border-t dark:border-gray-700">
                <a href="index.php?page=tudong"
                   class="px-5 py-2 text-sm font-medium text-gray-600 bg-gray-100 rounded-lg hover:bg-gray-200 transition">
                    Hủy bỏ
                </a>
                <button type="submit"
                        class="px-6 py-2 text-sm font-bold text-white bg-blue-600 rounded-lg hover:bg-blue-700 shadow-lg transform active:scale-95 transition">
                    <i class="fas fa-check-circle mr-2"></i> Cập nhật kịch bản
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    let currentTab = '<?= $mode ?>';

    function switchTab(tab) {
        currentTab = tab;
        document.querySelectorAll('.tab-content').forEach(el => el.classList.add('hidden'));
        document.getElementById(tab + '-tab-content').classList.remove('hidden');

        document.querySelectorAll('[id$="-tab"]').forEach(el => {
            el.classList.remove('text-blue-600', 'border-blue-600', 'dark:text-blue-500', 'dark:border-blue-500');
            el.classList.add('border-transparent', 'text-gray-500');
        });

        const activeTab = document.getElementById(tab + '-tab');
        activeTab.classList.remove('border-transparent', 'text-gray-500');
        activeTab.classList.add('text-blue-600', 'border-blue-600', 'dark:text-blue-500', 'dark:border-blue-500');
    }

    function updateUnit() {
        const sensor = document.getElementById('sensorType').value;
        const label = document.getElementById('unitLabel');
        const units = {
            temp: '°C',
            hum: '%',
            gas: 'ppm',
            pir: 'Trạng thái'
        };
        label.innerText = units[sensor] || 'đv';
    }

    function buildConditionPayload() {
        if (currentTab === 'timer') {
            const start = document.getElementById('gioBatDau').value || '';
            const end = document.getElementById('gioKetThuc').value || '';
            const days = Array.from(document.querySelectorAll('input[name="days[]"]:checked')).map(el => el.value);
            return `timer|start=${start}|end=${end}|days=${days.join(',') || 'all'}`;
        }

        const type = document.getElementById('sensorType').value;
        const op = document.getElementById('sensorOperator').value;
        const value = document.getElementById('sensorThreshold').value;
        const unit = document.getElementById('unitLabel').innerText;
        return `sensor|type=${type}|op=${op}|value=${value}|unit=${unit}`;
    }

    function buildActionPayload() {
        const device = document.getElementById('actionDevice').value;
        const state = document.getElementById('actionState').value;
        return `action|device=${device}|state=${state}`;
    }

    function buildDescription() {
        return `IF ${buildConditionPayload()} THEN ${buildActionPayload()}`;
    }

    document.addEventListener('DOMContentLoaded', function() {
        switchTab(currentTab);
        updateUnit();

        document.getElementById('automationEditForm').addEventListener('submit', function(e) {
            const scriptName = document.getElementById('scriptName').value.trim();
            if (!scriptName) {
                e.preventDefault();
                alert('Vui lòng nhập tên kịch bản');
                return;
            }

            document.getElementById('dieuKienPayload').value = buildConditionPayload();
            document.getElementById('hanhDongPayload').value = buildActionPayload();
            document.getElementById('moTaPayload').value = buildDescription();
        });
    });
</script>

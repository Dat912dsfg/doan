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
                    <button class="inline-block p-4 border-b-2 rounded-t-lg transition-colors duration-150" 
                            id="sensor-tab" 
                            onclick="switchTab('sensor')">
                        <i class="fas fa-microchip text-blue-600 mr-2"></i> Theo cảm biến
                    </button>
                </li>
                <li class="mr-2">
                    <button class="inline-block p-4 border-b-2 border-transparent rounded-t-lg hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300 transition-colors duration-150" 
                            id="timer-tab" 
                            onclick="switchTab('timer')">
                        <i class="fas fa-clock text-yellow-600 mr-2"></i> Theo thời gian
                    </button>
                </li>
            </ul>
        </div>

        <form action="index.php?page=tudong-update" method="POST" class="space-y-6">
            
            <label class="block text-sm">
                <span class="text-gray-700 dark:text-gray-400 font-bold uppercase text-xs">1. Tên kịch bản</span>
                <input type="text" name="script_name" value=""
                        class="block w-full mt-1 text-sm dark:bg-gray-700 dark:text-gray-300 form-input focus:border-blue-400 focus:shadow-outline-blue dark:focus:shadow-outline-gray p-2 border rounded" 
                        placeholder="Ví dụ: Cảnh báo rò rỉ Gas">
            </label>

            <div id="sensor-tab-content" class="tab-content space-y-4">
                <div class="p-4 bg-gray-50 rounded-lg border border-gray-200 dark:bg-gray-700 dark:border-gray-600 shadow-inner">
                    <h6 class="text-[10px] font-black text-blue-600 uppercase tracking-widest mb-4">ĐIỀU KIỆN KÍCH HOẠT (NẾU)</h6>
                    
                    <div class="grid gap-4 md:grid-cols-12">
                        <div class="md:col-span-5">
                            <label class="block text-xs text-gray-500 dark:text-gray-400 mb-1">Cảm biến nguồn</label>
                            <select id="sensorType" onchange="updateUnit()" class="block w-full text-sm dark:bg-gray-800 dark:text-gray-300 form-select focus:border-blue-400 h-10 border rounded px-2">
                                <option value="temp" selected>Nhiệt độ (DHT11)</option>
                                <option value="hum">Độ ẩm (DHT11)</option>
                                <option value="gas">Khí Gas (MQ-2)</option>
                                <option value="pir">Chuyển động (PIR)</option>
                            </select>
                        </div>
                        <div class="md:col-span-3">
                            <label class="block text-xs text-gray-500 dark:text-gray-400 mb-1">So sánh</label>
                            <select class="block w-full text-sm dark:bg-gray-800 dark:text-gray-300 form-select focus:border-blue-400 h-10 border rounded px-2">
                                <option value=">">Lớn hơn (>)</option>
                                <option value="<">Nhỏ hơn (<)</option>
                                <option value="=">Bằng (=)</option>
                            </select>
                        </div>
                        <div class="md:col-span-4">
                            <label class="block text-xs text-gray-500 dark:text-gray-400 mb-1">Ngưỡng</label>
                            <div class="relative">
                                <input type="number" value="30" class="block w-full pr-12 text-sm dark:bg-gray-800 dark:text-gray-300 form-input h-10 border rounded px-2">
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
                                <input type="time" class="block w-full text-sm dark:bg-gray-800 dark:text-gray-300 form-input h-10 border rounded px-2" value="18:00">
                            </div>
                            <div>
                                <label class="block text-xs text-gray-500 dark:text-gray-400 mb-1">Giờ Tắt</label>
                                <input type="time" class="block w-full text-sm dark:bg-gray-800 dark:text-gray-300 form-input h-10 border rounded px-2" value="06:00">
                            </div>
                        </div>
                        
                        <div>
                            <label class="block text-xs text-gray-500 dark:text-gray-400 mb-2">Lặp lại các ngày trong tuần</label>
                            <div class="flex flex-wrap gap-2">
                                <label class="day-checkbox">
                                    <input type="checkbox" class="sr-only peer" checked>
                                    <span class="w-9 h-9 flex items-center justify-center text-xs font-bold border rounded-lg cursor-pointer peer-checked:bg-blue-600 peer-checked:text-white peer-checked:border-blue-600 dark:bg-gray-800 dark:border-gray-600 transition-all">T2</span>
                                </label>
                                <label class="day-checkbox">
                                    <input type="checkbox" class="sr-only peer" checked>
                                    <span class="w-9 h-9 flex items-center justify-center text-xs font-bold border rounded-lg cursor-pointer peer-checked:bg-blue-600 peer-checked:text-white peer-checked:border-blue-600 dark:bg-gray-800 dark:border-gray-600 transition-all">T3</span>
                                </label>
                                <label class="day-checkbox">
                                    <input type="checkbox" class="sr-only peer" checked>
                                    <span class="w-9 h-9 flex items-center justify-center text-xs font-bold border rounded-lg cursor-pointer peer-checked:bg-blue-600 peer-checked:text-white peer-checked:border-blue-600 dark:bg-gray-800 dark:border-gray-600 transition-all">T4</span>
                                </label>
                                <label class="day-checkbox">
                                    <input type="checkbox" class="sr-only peer" checked>
                                    <span class="w-9 h-9 flex items-center justify-center text-xs font-bold border rounded-lg cursor-pointer peer-checked:bg-blue-600 peer-checked:text-white peer-checked:border-blue-600 dark:bg-gray-800 dark:border-gray-600 transition-all">T5</span>
                                </label>
                                <label class="day-checkbox">
                                    <input type="checkbox" class="sr-only peer" checked>
                                    <span class="w-9 h-9 flex items-center justify-center text-xs font-bold border rounded-lg cursor-pointer peer-checked:bg-blue-600 peer-checked:text-white peer-checked:border-blue-600 dark:bg-gray-800 dark:border-gray-600 transition-all">T6</span>
                                </label>
                                <label class="day-checkbox">
                                    <input type="checkbox" class="sr-only peer">
                                    <span class="w-9 h-9 flex items-center justify-center text-xs font-bold border rounded-lg cursor-pointer peer-checked:bg-blue-600 peer-checked:text-white peer-checked:border-blue-600 dark:bg-gray-800 dark:border-gray-600 transition-all">T7</span>
                                </label>
                                <label class="day-checkbox">
                                    <input type="checkbox" class="sr-only peer">
                                    <span class="w-9 h-9 flex items-center justify-center text-xs font-bold border rounded-lg cursor-pointer peer-checked:bg-blue-600 peer-checked:text-white peer-checked:border-blue-600 dark:bg-gray-800 dark:border-gray-600 transition-all">CN</span>
                                </label>
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
                        <select class="block w-full text-sm dark:bg-gray-800 dark:text-gray-300 form-select focus:border-green-400 h-10 border rounded px-2">
                            <option value="relay">Relay (Quạt/Phun sương)</option>
                            <option value="buzzer">Còi báo động (Buzzer)</option>
                            <option value="all">Tất cả thiết bị</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs text-gray-500 dark:text-gray-400 mb-1">Trạng thái mong muốn</label>
                        <select class="block w-full text-sm dark:bg-gray-800 dark:text-green-400 form-select focus:border-green-400 h-10 border rounded px-2 font-bold">
                            <option value="ON">KÍCH HOẠT (ON)</option>
                            <option value="OFF">NGẮT KẾT NỐI (OFF)</option>
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
    function switchTab(tab) {
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
            'temp': '°C',
            'hum': '%',
            'gas': 'ppm',
            'pir': 'Trạng thái'
        };
        label.innerText = units[sensor] || 'đv';
    }

    document.addEventListener('DOMContentLoaded', function() {
        switchTab('sensor');
        updateUnit();
    });
</script>
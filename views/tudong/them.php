<div class="flex flex-col items-start justify-between w-full gap-4 my-6 sm:flex-row sm:items-center">
    <h2 class="text-2xl font-semibold text-gray-700 dark:text-gray-200">
        Thêm kịch bản tự động mới
    </h2>
    <a href="index.php?page=tudong" 
        class="inline-flex items-center px-4 py-2 text-sm font-medium leading-5 text-gray-700 transition-colors duration-150 bg-white border border-gray-300 rounded-lg hover:text-gray-800 hover:border-gray-400 focus:outline-none dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-gray-300 flex-shrink-0">
        <i class="fas fa-arrow-left mr-2"></i> Quay lại
    </a>
</div>

<div class="w-full max-w-3xl mx-auto mb-8">
    <div class="min-w-0 p-6 bg-white rounded-xl shadow-xs dark:bg-gray-800 border border-gray-200 dark:border-gray-700">
        
        <div class="mb-6 border-b border-gray-200 dark:border-gray-700">
            <ul class="flex flex-wrap -mb-px text-sm font-medium text-center" id="scriptTabs">
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

        <form action="index.php?page=tudong-store" method="POST" class="space-y-6">
            
            <label class="block text-sm">
                <span class="text-gray-700 dark:text-gray-400 font-bold uppercase text-[10px] tracking-widest">1. Tên kịch bản</span>
                <input type="text" name="name" required
                        class="block w-full mt-1 text-sm dark:bg-gray-700 dark:text-gray-300 form-input focus:border-blue-400 p-2.5 border rounded-lg" 
                        placeholder="Ví dụ: Bật còi khi phát hiện khói">
            </label>

            <div id="sensor-tab-content" class="tab-content space-y-4">
                <div class="p-4 bg-blue-50/50 rounded-xl border border-blue-100 dark:bg-gray-700/50 dark:border-gray-600">
                    <h6 class="text-[10px] font-black text-blue-600 dark:text-blue-400 uppercase tracking-widest mb-4 flex items-center">
                        <span class="w-2 h-2 bg-blue-600 rounded-full mr-2"></span> ĐIỀU KIỆN (NẾU...)
                    </h6>
                    
                    <div class="grid gap-4 md:grid-cols-12">
                        <div class="md:col-span-5">
                            <label class="block text-xs text-gray-500 mb-1">Cảm biến đầu vào</label>
                            <select id="sensorSelect" onchange="changeUnit()" class="block w-full text-sm dark:bg-gray-800 dark:text-gray-300 form-select h-11 border rounded-lg px-3">
                                <option value="temp">Nhiệt độ (°C)</option>
                                <option value="hum">Độ ẩm (%)</option>
                                <option value="gas">Nồng độ Gas (ppm)</option>
                                <option value="pir">Xâm nhập (PIR)</option>
                            </select>
                        </div>
                        <div class="md:col-span-3">
                            <label class="block text-xs text-gray-500 mb-1">Điều kiện</label>
                            <select class="block w-full text-sm dark:bg-gray-800 dark:text-gray-300 form-select h-11 border rounded-lg px-3">
                                <option value=">">Lớn hơn (>)</option>
                                <option value="<">Nhỏ hơn (<)</option>
                                <option value="=">Bằng (=)</option>
                            </select>
                        </div>
                        <div class="md:col-span-4">
                            <label class="block text-xs text-gray-500 mb-1">Giá trị kích hoạt</label>
                            <div class="relative">
                                <input type="number" step="0.1" class="block w-full pr-14 text-sm dark:bg-gray-800 dark:text-gray-300 form-input h-11 border rounded-lg px-3" placeholder="0.0">
                                <div class="absolute inset-y-0 right-0 flex items-center px-3 bg-gray-100 dark:bg-gray-600 rounded-r-lg border-l dark:border-gray-500">
                                    <span id="unitText" class="text-[10px] font-bold text-gray-500 dark:text-gray-400">°C</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div id="timer-tab-content" class="tab-content space-y-4 hidden">
                <div class="p-4 bg-yellow-50/50 rounded-xl border border-yellow-100 dark:bg-gray-700/50 dark:border-gray-600">
                    <h6 class="text-[10px] font-black text-yellow-600 dark:text-yellow-400 uppercase tracking-widest mb-4 flex items-center">
                        <span class="w-2 h-2 bg-yellow-600 rounded-full mr-2"></span> LỊCH TRÌNH (NẾU...)
                    </h6>
                    
                    <div class="space-y-4">
                        <div class="grid gap-4 md:grid-cols-2">
                            <div>
                                <label class="block text-xs text-gray-500 mb-1">Giờ bắt đầu</label>
                                <input type="time" class="block w-full text-sm dark:bg-gray-800 dark:text-gray-300 form-input h-11 border rounded-lg px-3" value="18:00">
                            </div>
                            <div>
                                <label class="block text-xs text-gray-500 mb-1">Giờ kết thúc</label>
                                <input type="time" class="block w-full text-sm dark:bg-gray-800 dark:text-gray-300 form-input h-11 border rounded-lg px-3" value="06:00">
                            </div>
                        </div>
                        
                        <div>
                            <label class="block text-xs text-gray-500 mb-2">Các ngày áp dụng</label>
                            <div class="flex flex-wrap gap-2">
                                <label class="cursor-pointer">
                                    <input type="checkbox" class="sr-only peer" checked>
                                    <div class="w-10 h-10 flex items-center justify-center text-xs font-bold border-2 rounded-xl peer-checked:bg-blue-600 peer-checked:text-white peer-checked:border-blue-600 dark:border-gray-600 transition-all">T2</div>
                                </label>
                                <label class="cursor-pointer">
                                    <input type="checkbox" class="sr-only peer" checked>
                                    <div class="w-10 h-10 flex items-center justify-center text-xs font-bold border-2 rounded-xl peer-checked:bg-blue-600 peer-checked:text-white peer-checked:border-blue-600 dark:border-gray-600 transition-all">T3</div>
                                </label>
                                <label class="cursor-pointer">
                                    <input type="checkbox" class="sr-only peer" checked>
                                    <div class="w-10 h-10 flex items-center justify-center text-xs font-bold border-2 rounded-xl peer-checked:bg-blue-600 peer-checked:text-white peer-checked:border-blue-600 dark:border-gray-600 transition-all">T4</div>
                                </label>
                                <label class="cursor-pointer">
                                    <input type="checkbox" class="sr-only peer" checked>
                                    <div class="w-10 h-10 flex items-center justify-center text-xs font-bold border-2 rounded-xl peer-checked:bg-blue-600 peer-checked:text-white peer-checked:border-blue-600 dark:border-gray-600 transition-all">T5</div>
                                </label>
                                <label class="cursor-pointer">
                                    <input type="checkbox" class="sr-only peer" checked>
                                    <div class="w-10 h-10 flex items-center justify-center text-xs font-bold border-2 rounded-xl peer-checked:bg-blue-600 peer-checked:text-white peer-checked:border-blue-600 dark:border-gray-600 transition-all">T6</div>
                                </label>
                                <label class="cursor-pointer">
                                    <input type="checkbox" class="sr-only peer">
                                    <div class="w-10 h-10 flex items-center justify-center text-xs font-bold border-2 rounded-xl peer-checked:bg-blue-600 peer-checked:text-white peer-checked:border-blue-600 dark:border-gray-600 transition-all">T7</div>
                                </label>
                                <label class="cursor-pointer">
                                    <input type="checkbox" class="sr-only peer">
                                    <div class="w-10 h-10 flex items-center justify-center text-xs font-bold border-2 rounded-xl peer-checked:bg-blue-600 peer-checked:text-white peer-checked:border-blue-600 dark:border-gray-600 transition-all">CN</div>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="p-4 bg-green-50/50 rounded-xl border border-green-100 dark:bg-gray-700/50 dark:border-gray-600">
                <h6 class="text-[10px] font-black text-green-600 dark:text-green-400 uppercase tracking-widest mb-4 flex items-center">
                    <span class="w-2 h-2 bg-green-600 rounded-full mr-2"></span> HÀNH ĐỘNG (THÌ...)
                </h6>
                <div class="grid gap-4 md:grid-cols-2">
                    <div>
                        <label class="block text-xs text-gray-500 mb-1">Thiết bị điều khiển</label>
                        <select class="block w-full text-sm dark:bg-gray-800 dark:text-gray-300 form-select h-11 border rounded-lg px-3">
                            <option value="relay">Relay (Quạt/Phun sương)</option>
                            <option value="buzzer">Còi báo động</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs text-gray-500 mb-1">Lệnh thực thi</label>
                        <select class="block w-full text-sm dark:bg-gray-800 dark:text-blue-500 form-select h-11 border rounded-lg px-3 font-bold">
                            <option value="ON">BẬT (ON)</option>
                            <option value="OFF">TẮT (OFF)</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="flex justify-end gap-3 pt-6 border-t dark:border-gray-700">
                <button type="button" onclick="window.location.href='index.php?page=tudong'"
                   class="px-6 py-2.5 text-sm font-medium text-gray-600 bg-gray-100 rounded-xl hover:bg-gray-200 transition">
                    Hủy
                </button>
                <button type="submit" 
                        class="px-8 py-2.5 text-sm font-bold text-white bg-blue-600 rounded-xl hover:bg-blue-700 shadow-lg shadow-blue-200 dark:shadow-none transition transform active:scale-95">
                    <i class="fas fa-plus-circle mr-2"></i> Tạo kịch bản ngay
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function switchTab(tab) {
        document.querySelectorAll('.tab-content').forEach(el => el.classList.add('hidden'));
        document.getElementById(tab + '-tab-content').classList.remove('hidden');
        
        document.querySelectorAll('#scriptTabs button').forEach(el => {
            el.classList.remove('text-blue-600', 'border-blue-600', 'dark:text-blue-500', 'dark:border-blue-500');
            el.classList.add('border-transparent', 'text-gray-500');
        });
        
        const activeBtn = document.getElementById(tab + '-tab');
        activeBtn.classList.remove('border-transparent', 'text-gray-500');
        activeBtn.classList.add('text-blue-600', 'border-blue-600', 'dark:text-blue-500', 'dark:border-blue-500');
    }

    function changeUnit() {
        const type = document.getElementById('sensorSelect').value;
        const unit = document.getElementById('unitText');
        const map = {
            'temp': '°C',
            'hum': '%',
            'gas': 'ppm',
            'pir': 'MỨC'
        };
        unit.innerText = map[type] || '';
    }

    document.addEventListener('DOMContentLoaded', () => {
        switchTab('sensor');
        changeUnit();
    });
</script>
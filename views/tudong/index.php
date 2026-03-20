<div class="pb-10">
    <div class="flex flex-col items-start justify-between gap-4 my-6 sm:flex-row sm:items-center">
        <h2 class="text-2xl font-semibold text-gray-700 dark:text-gray-200">
            Tự động hóa & Kịch bản
        </h2>
        <a href="index.php?page=tudong_them" 
           class="inline-flex items-center px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-blue-600 border border-transparent rounded-lg active:bg-blue-600 hover:bg-blue-700 focus:outline-none shadow-md">
            <i class="fas fa-plus mr-2"></i>
            <span>Thêm kịch bản mới</span>
        </a>
    </div>

    <div class="grid gap-6 mb-8 md:grid-cols-2">
        <div class="p-5 bg-white rounded-xl shadow-sm dark:bg-gray-800 border border-gray-100 dark:border-gray-700 flex items-center justify-between">
            <div class="flex items-center">
                <div class="p-3 mr-4 text-blue-500 bg-blue-100 rounded-full dark:text-blue-100 dark:bg-blue-500">
                    <i class="fas fa-robot"></i>
                </div>
                <div>
                    <h4 class="font-bold text-gray-800 dark:text-gray-200">Tự động hóa tổng</h4>
                    <p class="text-xs text-gray-500 dark:text-gray-400">Bật/Tắt toàn bộ kịch bản</p>
                </div>
            </div>
            <label class="relative inline-flex items-center cursor-pointer">
                <input type="checkbox" id="masterAutoSwitch" class="sr-only peer" checked>
                <div class="w-12 h-6 bg-gray-200 rounded-full peer-checked:bg-blue-600 transition-all dark:bg-gray-700"></div>
                <div class="absolute left-1 top-1 bg-white w-4 h-4 rounded-full transition-all peer-checked:translate-x-6 shadow-sm"></div>
            </label>
        </div>

        <div class="p-5 bg-white rounded-xl shadow-sm dark:bg-gray-800 border border-gray-100 dark:border-gray-700 flex items-center justify-between border-l-4 border-l-red-500">
            <div class="flex items-center">
                <div class="p-3 mr-4 text-red-500 bg-red-100 rounded-full dark:text-red-100 dark:bg-red-500">
                    <i class="fas fa-shield-alt"></i>
                </div>
                <div>
                    <h4 class="font-bold text-gray-800 dark:text-gray-200">Chế độ Vắng nhà</h4>
                    <p class="text-xs text-gray-500 dark:text-gray-400">Kích hoạt báo động xâm nhập ngay</p>
                </div>
            </div>
            <label class="relative inline-flex items-center cursor-pointer">
                <input type="checkbox" id="awayMode" class="sr-only peer">
                <div class="w-12 h-6 bg-gray-200 rounded-full peer-checked:bg-red-600 transition-all dark:bg-gray-700"></div>
                <div class="absolute left-1 top-1 bg-white w-4 h-4 rounded-full transition-all peer-checked:translate-x-6 shadow-sm"></div>
            </label>
        </div>
    </div>

    <div class="grid gap-6 md:grid-cols-2" id="automationGrid">
        
        <div class="min-w-0 p-5 bg-white rounded-xl shadow-sm dark:bg-gray-800 border dark:border-gray-700">
            <h4 class="mb-6 font-bold text-gray-800 dark:text-gray-200 flex items-center">
                <i class="fas fa-microchip mr-2 text-blue-500"></i> Theo Cảm biến
            </h4>
            
            <div class="space-y-6">
                <div class="automation-item group">
                    <div class="flex justify-between items-center mb-2">
                        <span class="font-bold text-sm dark:text-gray-200">Xử lý rò rỉ Gas</span>
                        <input type="checkbox" checked class="w-4 h-4 text-blue-600">
                    </div>
                    <div class="p-3 bg-red-50 dark:bg-red-900/20 rounded-lg border border-red-100 dark:border-red-800 flex items-center justify-between text-xs">
                        <span class="dark:text-red-200">NẾU Gas <b class="text-red-600">> 300 ppm</b></span>
                        <i class="fas fa-chevron-right text-gray-300"></i>
                        <span class="font-bold text-red-600 uppercase">BẬT Còi + Quạt</span>
                    </div>
                    <div class="flex justify-end mt-1 gap-3 opacity-0 group-hover:opacity-100 transition-all">
                        <button class="text-[10px] font-bold text-gray-400 hover:text-blue-500 uppercase">Sửa</button>
                        <button class="text-[10px] font-bold text-gray-400 hover:text-red-500 uppercase">Xóa</button>
                    </div>
                </div>

                <div class="automation-item group">
                    <div class="flex justify-between items-center mb-2">
                        <span class="font-bold text-sm dark:text-gray-200">Làm mát tự động</span>
                        <input type="checkbox" checked class="w-4 h-4 text-blue-600">
                    </div>
                    <div class="p-3 bg-blue-50 dark:bg-blue-900/20 rounded-lg border border-blue-100 dark:border-blue-800 flex items-center justify-between text-xs">
                        <span class="dark:text-blue-200">NẾU Nhiệt độ <b class="text-blue-600">> 35°C</b></span>
                        <i class="fas fa-chevron-right text-gray-300"></i>
                        <span class="font-bold text-blue-600 uppercase">BẬT Relay 1</span>
                    </div>
                    <div class="flex justify-end mt-1 gap-3 opacity-0 group-hover:opacity-100 transition-all">
                        <a href="index.php?page=tudong_sua">
                            <button class="text-[10px] font-bold text-gray-400 hover:text-blue-500 uppercase">
                                Sửa
                            </button>
                        </a>
                        <button class="text-[10px] font-bold text-gray-400 hover:text-red-500 uppercase">Xóa</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="min-w-0 p-5 bg-white rounded-xl shadow-sm dark:bg-gray-800 border dark:border-gray-700">
            <h4 class="mb-6 font-bold text-gray-800 dark:text-gray-200 flex items-center">
                <i class="fas fa-clock mr-2 text-orange-500"></i> Lịch trình & An ninh
            </h4>

            <div class="space-y-6">
                <div class="automation-item group">
                    <div class="flex justify-between items-center mb-2">
                        <span class="font-bold text-sm dark:text-gray-200">Bảo vệ ban đêm (PIR)</span>
                        <input type="checkbox" checked class="w-4 h-4 text-blue-600">
                    </div>
                    <div class="p-3 bg-gray-50 dark:bg-gray-700 rounded-lg border border-gray-100 dark:border-gray-600">
                        <div class="flex items-center justify-between mb-1">
                            <span class="text-xl font-black dark:text-white">23:00 - 05:00</span>
                            <span class="text-[10px] font-bold bg-gray-200 dark:bg-gray-600 px-2 py-0.5 rounded text-gray-600 dark:text-gray-300">HẰNG ĐÊM</span>
                        </div>
                        <p class="text-[11px] text-gray-500 dark:text-gray-400 uppercase font-bold"><i class="fas fa-exclamation-triangle mr-1 text-orange-500"></i>Kích hoạt: Còi hú khi có người</p>
                    </div>
                    <div class="flex justify-end mt-1 gap-3 opacity-0 group-hover:opacity-100 transition-all">
                        <button class="text-[10px] font-bold text-gray-400 hover:text-blue-500 uppercase">Cài đặt giờ</button>
                        <button class="text-[10px] font-bold text-gray-400 hover:text-red-500 uppercase">Xóa</button>
                    </div>
                </div>

                <div class="automation-item group">
                    <div class="flex justify-between items-center mb-2">
                        <span class="font-bold text-sm dark:text-gray-200">Tắt thiết bị giờ nghỉ</span>
                        <input type="checkbox" class="w-4 h-4 text-blue-600">
                    </div>
                    <div class="p-3 bg-gray-50 dark:bg-gray-700 rounded-lg border border-gray-100 dark:border-gray-600">
                        <div class="flex items-center justify-between mb-1 text-gray-400">
                            <span class="text-xl font-black">22:00</span>
                            <span class="text-[10px] font-bold border border-gray-300 px-2 py-0.5 rounded">HÀNG NGÀY</span>
                        </div>
                        <p class="text-[11px] text-gray-400 uppercase font-bold">Kích hoạt: TẮT tất cả Relay</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const masterSwitch = document.getElementById('masterAutoSwitch');
        const awayMode = document.getElementById('awayMode');
        const grid = document.getElementById('automationGrid');

        // Logic Switch tổng
        masterSwitch.addEventListener('change', function() {
            if (!this.checked) {
                grid.classList.add('opacity-40', 'pointer-events-none');
                console.log("Toàn bộ kịch bản tự động đã bị vô hiệu hóa.");
            } else {
                grid.classList.remove('opacity-40', 'pointer-events-none');
                console.log("Hệ thống tự động đã sẵn sàng.");
            }
        });

        // Logic Chế độ vắng nhà
        awayMode.addEventListener('change', function() {
            if (this.checked) {
                alert("Đã kích hoạt CHẾ ĐỘ VẮNG NHÀ. Cảm biến chuyển động sẽ báo động ngay lập tức nếu phát hiện xâm nhập!");
            }
        });
    });
</script>

<style>
    .automation-item {
        border-bottom: 1px solid rgba(0,0,0,0.05);
        padding-bottom: 1rem;
    }
    .automation-item:last-child {
        border-bottom: none;
    }
    .dark .automation-item {
        border-bottom-color: rgba(255,255,255,0.05);
    }
</style>
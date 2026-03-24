<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<div x-data="{ activeTab: 'realtime' }">
    <div class="flex flex-col items-start justify-between w-full gap-4 my-6 sm:flex-row sm:items-center">
        <h2 class="text-2xl font-semibold text-gray-700 dark:text-gray-200">
            Phân tích dữ liệu
        </h2>
        
        <div class="flex flex-col w-full gap-3 sm:w-auto sm:flex-row">
            <select id="sensorSelect" class="block w-full text-sm rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 form-select sm:w-40 p-2 border">
                <option value="temp">Nhiệt độ (°C)</option>
                <option value="hum">Độ ẩm (%)</option>
                <option value="gas">Nồng độ Gas (ppm)</option>
            </select>

            <input type="date" id="dateFilter" value="<?= date('Y-m-d') ?>" class="block w-full text-sm rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 form-input sm:w-auto p-2 border" />

            <button class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 shadow-md">
                <i class="fas fa-sync-alt"></i>
            </button>
        </div>
    </div>

    <div class="mb-6 border-b border-gray-200 dark:border-gray-700">
        <ul class="flex flex-wrap -mb-px text-sm font-medium text-center">
            <li class="mr-2">
                <button @click="activeTab = 'realtime'" 
                        :class="{ 'text-blue-600 border-blue-600 dark:text-blue-400 dark:border-blue-400': activeTab === 'realtime', 'text-gray-500 border-transparent hover:text-gray-600 hover:border-gray-300': activeTab !== 'realtime' }"
                        class="inline-block p-4 border-b-2 rounded-t-lg transition-all duration-150">
                    <i class="fas fa-chart-line me-2"></i> Giám sát Thời gian thực
                </button>
            </li>
            <li class="mr-2">
                <button @click="activeTab = 'advanced'" 
                        :class="{ 'text-blue-600 border-blue-600 dark:text-blue-400 dark:border-blue-400': activeTab === 'advanced', 'text-gray-500 border-transparent hover:text-gray-600 hover:border-gray-300': activeTab !== 'advanced' }"
                        class="inline-block p-4 border-b-2 rounded-t-lg transition-all duration-150">
                    <i class="fas fa-microchip me-2"></i> Phân tích Xu hướng
                </button>
            </li>
            <li class="mr-2">
                <button @click="activeTab = 'export'" 
                        :class="{ 'text-blue-600 border-blue-600 dark:text-blue-400 dark:border-blue-400': activeTab === 'export', 'text-gray-500 border-transparent hover:text-gray-600 hover:border-gray-300': activeTab !== 'export' }"
                        class="inline-block p-4 border-b-2 rounded-t-lg transition-all duration-150">
                    <i class="fas fa-file-export me-2"></i> Xuất báo cáo
                </button>
            </li>
        </ul>
    </div>

    <div x-show="activeTab === 'realtime'" x-transition>
        <div class="min-w-0 p-4 bg-white rounded-lg shadow-xs dark:bg-gray-800 border dark:border-gray-700">
            <h4 class="mb-4 font-semibold text-gray-800 dark:text-gray-300">Biến thiên dữ liệu theo giờ</h4>
            <div class="relative w-full" style="height: 400px;">
                <canvas id="realtimeChart"></canvas>
            </div>
        </div>
    </div>

    <div x-show="activeTab === 'advanced'" x-transition style="display: none;">
        <div class="min-w-0 p-4 mb-8 bg-white rounded-lg shadow-xs dark:bg-gray-800 border dark:border-gray-700">
            <div class="flex justify-between items-center mb-4">
                <h4 class="font-semibold text-gray-800 dark:text-gray-300">Vùng dao động chuẩn (24h)</h4>
                <span class="px-2 py-1 font-semibold text-blue-700 bg-blue-100 rounded-full text-xs">Phân tích chuyên sâu</span>
            </div>
            <div class="relative w-full" style="height: 350px;">
                <canvas id="deviationChart"></canvas>
            </div>
        </div>

        <div class="grid gap-6 mb-8 md:grid-cols-2">
            <div class="p-4 bg-white rounded-lg shadow-xs dark:bg-gray-800 border dark:border-gray-700">
                <h4 class="mb-4 font-semibold text-gray-700 dark:text-gray-200">Đánh giá hệ thống</h4>
                <div class="p-3 mb-4 text-sm text-blue-800 bg-blue-100 rounded-lg border-l-4 border-blue-600 dark:bg-blue-900 dark:text-blue-200">
                    <p class="font-bold mb-1"><i class="fas fa-check-circle mr-1"></i> Trạng thái ổn định</p>
                    <p>Dữ liệu ghi nhận không có sự biến động đột ngột gây nguy hiểm trong 24h qua.</p>
                </div>
                <div class="space-y-3 text-sm dark:text-gray-400">
                    <div class="flex justify-between"><span>Độ lệch chuẩn:</span><span class="font-bold text-gray-800 dark:text-gray-200">±1.2</span></div>
                    <div class="flex justify-between"><span>Giá trị trung bình:</span><span class="font-bold text-gray-800 dark:text-gray-200">28.5°C</span></div>
                </div>
            </div>

            <div class="p-4 text-white bg-blue-600 rounded-lg shadow-md">
                <h4 class="mb-4 font-semibold text-lg">Gợi ý tối ưu vận hành</h4>
                <p class="mb-4 text-blue-100 text-sm">Hệ thống phân tích cho thấy bạn có thể tăng ngưỡng nhiệt độ để tiết kiệm điện mà vẫn đảm bảo an toàn.</p>
                <div class="flex items-center justify-between p-3 bg-blue-700 rounded-lg">
                    <div class="text-center"><p class="text-xs text-blue-200">HIỆN TẠI</p><p class="text-xl font-bold">30.0°C</p></div>
                    <i class="fas fa-chevron-right text-blue-400"></i>
                    <div class="text-center"><p class="text-xs text-green-300">ĐỀ XUẤT</p><p class="text-xl font-bold text-green-300">32.5°C</p></div>
                </div>
                <button class="w-full mt-4 py-2 bg-white text-blue-600 font-bold rounded-lg hover:bg-gray-100 shadow">Áp dụng ngay</button>
            </div>
        </div>
    </div>

    <div x-show="activeTab === 'export'" x-transition style="display: none;">
        <div class="p-6 bg-white rounded-lg shadow-xs dark:bg-gray-800 border dark:border-gray-700">
            <h4 class="mb-6 font-semibold text-gray-800 dark:text-gray-300 text-lg">Cấu hình xuất báo cáo</h4>
            <div class="grid gap-6 md:grid-cols-3">
                <div class="space-y-2">
                    <label class="text-sm font-medium text-gray-600 dark:text-gray-400">Khoảng thời gian</label>
                    <select class="w-full border p-2 rounded dark:bg-gray-700 dark:text-white dark:border-gray-600"><option>Hôm nay</option><option>7 ngày qua</option><option>30 ngày qua</option></select>
                </div>
                <div class="space-y-2">
                    <label class="text-sm font-medium text-gray-600 dark:text-gray-400">Định dạng file</label>
                    <div class="flex gap-4 pt-2">
                        <label class="flex items-center"><input type="radio" name="fmt" checked class="mr-2"> Excel (.xlsx)</label>
                        <label class="flex items-center"><input type="radio" name="fmt" class="mr-2"> PDF (.pdf)</label>
                    </div>
                </div>
                <div class="flex items-end">
                    <button class="w-full py-2 bg-green-600 text-white font-bold rounded-lg hover:bg-green-700"><i class="fas fa-download mr-2"></i> Tải báo cáo</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const ctxRealtime = document.getElementById('realtimeChart').getContext('2d');
        const rtChart = new Chart(ctxRealtime, {
            type: 'line',
            data: {
                labels: ['08:00', '09:00', '10:00', '11:00', '12:00', '13:00', '14:00'],
                datasets: [{
                    label: 'Giá trị thực tế',
                    data: [27, 27.5, 28, 29, 30.5, 31, 29.5],
                    borderColor: '#2563eb',
                    backgroundColor: 'rgba(37, 99, 235, 0.1)',
                    tension: 0.4,
                    fill: true
                }]
            },
            options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { display: false } } }
        });

        const ctxDeviation = document.getElementById('deviationChart').getContext('2d');
        new Chart(ctxDeviation, {
            type: 'line',
            data: {
                labels: ['0h', '4h', '8h', '12h', '16h', '20h'],
                datasets: [
                    { label: 'Giới hạn trên', data: [32, 32, 34, 35, 33, 32], borderColor: '#d1d5db', borderDash: [5, 5], fill: false, pointRadius: 0 },
                    { label: 'Giá trị đo', data: [28, 27, 29, 31, 29, 28], borderColor: '#1d4ed8', borderWidth: 3, fill: false },
                    { label: 'Giới hạn dưới', data: [24, 24, 26, 27, 25, 24], borderColor: '#d1d5db', borderDash: [5, 5], fill: false, pointRadius: 0 }
                ]
            },
            options: { responsive: true, maintainAspectRatio: false }
        });
    });
</script>
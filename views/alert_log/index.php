<div class="pb-10">
    <div class="flex flex-col items-start justify-between w-full gap-4 my-6 sm:flex-row sm:items-center">
        <h2 class="text-2xl font-semibold text-gray-700 dark:text-gray-200">
            Cảnh báo & Nhật ký hệ thống
        </h2>
        <div class="flex gap-2">
            <button onclick="confirmClearLogs()" class="inline-flex items-center px-4 py-2 text-sm font-bold text-red-700 transition-colors duration-150 bg-red-100 border border-transparent rounded-lg hover:bg-red-200 dark:bg-red-900/30 dark:text-red-400">
                <i class="fas fa-trash-alt mr-2"></i> Xóa lịch sử
            </button>
            <div class="relative inline-block text-left" x-data="{ open: false }">
                <button @click="open = !open" class="inline-flex items-center px-4 py-2 text-sm font-bold text-blue-700 bg-blue-100 border border-transparent rounded-lg hover:bg-blue-200 dark:bg-blue-900/30 dark:text-blue-400">
                    <i class="fas fa-file-export mr-2"></i> Xuất báo cáo
                </button>
                <div x-show="open" @click.away="open = false" class="absolute right-0 w-40 mt-2 origin-top-right bg-white border border-gray-100 rounded-md shadow-lg dark:bg-gray-700 dark:border-gray-600 z-50">
                    <div class="py-1">
                        <a href="#" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-blue-50 dark:hover:bg-gray-600"><i class="far fa-file-excel mr-2 text-green-600"></i> Xuất Excel</a>
                        <a href="#" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-blue-50 dark:hover:bg-gray-600"><i class="far fa-file-pdf mr-2 text-red-600"></i> Xuất PDF</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="mb-6 p-4 bg-white rounded-lg shadow-sm dark:bg-gray-800 border dark:border-gray-700 flex flex-col md:flex-row gap-4">
        <div class="relative flex-1">
            <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                <i class="fas fa-search text-xs"></i>
            </span>
            <input type="text" class="w-full pl-10 pr-4 py-2 text-sm border rounded-lg focus:ring-blue-500 dark:bg-gray-700 dark:text-white dark:border-gray-600" placeholder="Tìm kiếm hành động, người dùng hoặc sự kiện...">
        </div>
        <select class="px-4 py-2 text-sm border rounded-lg dark:bg-gray-700 dark:text-white dark:border-gray-600">
            <option value="">Tất cả mức độ</option>
            <option value="danger" class="text-red-600 font-bold">Đỏ - Nguy hiểm</option>
            <option value="warning" class="text-yellow-600 font-bold">Vàng - Cảnh báo</option>
            <option value="info" class="text-blue-600">Xanh - Thông tin</option>
        </select>
        <input type="date" class="px-4 py-2 text-sm border rounded-lg dark:bg-gray-700 dark:text-white dark:border-gray-600">
    </div>

    <div class="flex flex-col gap-6 w-full">
        
        <div class="bg-white rounded-xl shadow-sm dark:bg-gray-800 border dark:border-gray-700 overflow-hidden h-fit">
            <div class="px-4 py-4 border-b dark:border-gray-700 bg-gray-50/50 dark:bg-gray-800">
                <h4 class="font-bold text-gray-800 dark:text-gray-200 flex items-center uppercase text-xs tracking-wider">
                    <span class="w-2 h-2 bg-red-500 rounded-full mr-2 animate-pulse"></span>
                    Luồng cảnh báo trực tiếp
                </h4>
            </div>
            
            <div class="divide-y dark:divide-gray-700">
                <div class="p-4 bg-red-50/50 dark:bg-red-900/10 border-l-4 border-red-600 flex justify-between items-start">
                    <div>
                        <span class="text-[10px] font-black text-red-600 uppercase">Khẩn cấp - Gas</span>
                        <h5 class="text-sm font-bold text-gray-800 dark:text-gray-100 mt-0.5">Phát hiện rò rỉ GAS tại Phòng 102</h5>
                        <p class="text-xs text-gray-600 dark:text-gray-400 mt-1">Nồng độ: <span class="font-bold text-red-600">450 ppm</span> (Vượt ngưỡng 300)</p>
                    </div>
                    <span class="text-[10px] text-gray-400 font-mono italic">14:30:05</span>
                </div>

                <div class="p-4 bg-orange-50/50 dark:bg-orange-900/10 border-l-4 border-orange-500 flex justify-between items-start">
                    <div>
                        <span class="text-[10px] font-black text-orange-600 uppercase">An ninh</span>
                        <h5 class="text-sm font-bold text-gray-800 dark:text-gray-100 mt-0.5">Xâm nhập trái phép</h5>
                        <p class="text-xs text-gray-600 dark:text-gray-400 mt-1">Cảm biến PIR kích hoạt trong khung giờ giới nghiêm.</p>
                    </div>
                    <span class="text-[10px] text-gray-400 font-mono italic">12:15:22</span>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm dark:bg-gray-800 border dark:border-gray-700 overflow-hidden h-fit">
            <div class="px-4 py-4 border-b dark:border-gray-700 bg-gray-50/50 dark:bg-gray-800">
                <h4 class="font-bold text-gray-800 dark:text-gray-200 uppercase text-xs tracking-wider">
                    Nhật ký hoạt động & Vận hành
                </h4>
            </div>

            <div class="w-full overflow-x-auto">
                <table class="w-full whitespace-no-wrap">
                    <thead>
                        <tr class="text-[10px] font-bold tracking-widest text-left text-gray-500 uppercase border-b dark:border-gray-700 bg-gray-50 dark:text-gray-400 dark:bg-gray-800">
                            <th class="px-4 py-3">Thời gian</th>
                            <th class="px-4 py-3">Người thực hiện</th>
                            <th class="px-4 py-3">Hành động</th>
                            <th class="px-4 py-3 text-right">Chi tiết</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800 text-sm">
                        <tr class="text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                            <td class="px-4 py-3 font-mono text-xs">21/02 08:30:10</td>
                            <td class="px-4 py-3"><span class="font-bold">Admin</span></td>
                            <td class="px-4 py-3">
                                <span class="px-2 py-0.5 text-[10px] font-bold bg-blue-100 text-blue-700 rounded-md dark:bg-blue-900 dark:text-blue-100 uppercase">Bật Relay</span>
                            </td>
                            <td class="px-4 py-3 text-right text-xs">Phòng 101 - Quạt thông gió</td>
                        </tr>
                        <tr class="text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                            <td class="px-4 py-3 font-mono text-xs">21/02 08:25:00</td>
                            <td class="px-4 py-3 text-green-600 font-bold italic">Hệ thống</td>
                            <td class="px-4 py-3">
                                <span class="px-2 py-0.5 text-[10px] font-bold bg-green-100 text-green-700 rounded-md uppercase">Tự động hóa</span>
                            </td>
                            <td class="px-4 py-3 text-right text-xs">Kích hoạt kịch bản: Tưới cây sáng</td>
                        </tr>
                        <tr class="text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                            <td class="px-4 py-3 font-mono text-xs">20/02 23:45:12</td>
                            <td class="px-4 py-3"><span class="font-bold">Huy Nguyen</span></td>
                            <td class="px-4 py-3">
                                <span class="px-2 py-0.5 text-[10px] font-bold bg-yellow-100 text-yellow-700 rounded-md uppercase">Cấu hình</span>
                            </td>
                            <td class="px-4 py-3 text-right text-xs">Sửa ngưỡng Gas: 200 -> 300 ppm</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>
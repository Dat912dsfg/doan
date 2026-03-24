<div
    x-show="isModalOpen"
    x-cloak
    x-transition:enter="transition ease-out duration-150"
    x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100"
    x-transition:leave="transition ease-in duration-150"
    x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0"
    class="fixed inset-0 z-30 flex items-end bg-black bg-opacity-50 sm:items-center sm:justify-center"
>
    <div
        x-show="isModalOpen"
        @click.away="closeModal"
        @keydown.escape="closeModal"
        class="w-full px-6 py-4 overflow-hidden bg-white rounded-t-lg dark:bg-gray-800 sm:rounded-lg sm:m-4 sm:max-w-xl shadow-xl"
        role="dialog"
    >
        <header class="flex justify-end">
            <button @click="closeModal" class="inline-flex items-center justify-center w-6 h-6 text-gray-400 transition-colors duration-150 hover:text-gray-700 dark:hover:text-gray-200">
                <i class="fas fa-times"></i>
            </button>
        </header>
        
        <div class="mt-4 mb-6">
            <p id="modal-title" class="mb-2 text-lg font-semibold text-gray-700 dark:text-gray-300">
                Xác nhận thao tác
            </p>
            <p id="modal-description" class="text-sm text-gray-700 dark:text-gray-400">
                Hành động này không thể hoàn tác. Bạn có chắc chắn muốn tiếp tục không?
            </p>
        </div>

        <footer class="flex flex-col items-center justify-end px-6 py-3 -mx-6 -mb-4 space-y-4 sm:space-y-0 sm:space-x-6 sm:flex-row bg-gray-50 dark:bg-gray-800 border-t border-gray-100 dark:border-gray-700">
            <button @click="closeModal" class="w-full px-5 py-3 text-sm font-medium text-gray-700 border border-gray-300 rounded-lg sm:w-auto hover:bg-gray-100 focus:outline-none focus:ring focus:ring-gray-200 active:bg-gray-200 transition-colors duration-150 dark:text-gray-400 dark:border-gray-600 dark:hover:bg-gray-700">
                Hủy bỏ
            </button>
            <a id="modal-confirm-btn" href="#" class="w-full px-5 py-3 text-sm font-medium text-center text-white bg-blue-600 rounded-lg sm:w-auto hover:bg-blue-700 focus:outline-none focus:ring focus:ring-blue-200 active:bg-blue-800 transition-colors duration-150 shadow-md">
                Xác nhận
            </a>
        </footer>
    </div>
</div>
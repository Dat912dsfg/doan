<?php 
$currentPage = $_GET['page'] ?? 'dashboard';
?>

<aside class="z-20 hidden w-64 overflow-y-auto bg-white dark:bg-gray-800 md:block flex-shrink-0">
    <div class="py-4 text-gray-500 dark:text-gray-400">
        <a class="ml-6 text-lg font-bold text-gray-800 dark:text-gray-200 flex items-center" href="index.php?page=dashboard">
            <div class="p-1 bg-blue-600 rounded-lg mr-3">
                <i class="fas fa-shield-alt text-white text-sm"></i>
            </div>
            <span>SafeStay IoT</span>
        </a>
        
        <ul class="mt-6">
            <?php if (hasPermission('trangchu.view')): ?>
            <li class="relative px-6 py-3">
                <?php if ($currentPage == 'dashboard'): ?>
                    <span class="absolute inset-y-0 left-0 w-1 bg-blue-600 rounded-tr-lg rounded-br-lg" aria-hidden="true"></span>
                <?php endif; ?>
                <a class="inline-flex items-center w-full text-sm font-semibold transition-colors duration-150 hover:text-gray-800 dark:hover:text-gray-200 <?php echo $currentPage == 'dashboard' ? 'text-gray-800 dark:text-gray-100' : '' ?>" href="index.php?page=dashboard">
                    <i class="fas fa-home w-5 h-5 flex items-center justify-center"></i>
                    <span class="ml-4">Tổng quan</span>
                </a>
            </li>
            <?php endif; ?>

            <?php if (hasPermission('phantich.view')): ?>
            <li class="relative px-6 py-3">
                <?php if ($currentPage == 'phantich'): ?>
                    <span class="absolute inset-y-0 left-0 w-1 bg-blue-600 rounded-tr-lg rounded-br-lg" aria-hidden="true"></span>
                <?php endif; ?>
                <a class="inline-flex items-center w-full text-sm font-semibold transition-colors duration-150 hover:text-gray-800 dark:hover:text-gray-200 <?php echo $currentPage == 'phantich' ? 'text-gray-800 dark:text-gray-100' : '' ?>" href="index.php?page=phantich">
                    <i class="fas fa-chart-line w-5 h-5 flex items-center justify-center"></i>
                    <span class="ml-4">Phân tích và Báo cáo</span>
                </a>
            </li>
            <?php endif; ?>

            <?php if (hasPermission('canhbao.view')): ?>
            <li class="relative px-6 py-3">
                <?php if ($currentPage == 'alert_log'): ?>
                    <span class="absolute inset-y-0 left-0 w-1 bg-blue-600 rounded-tr-lg rounded-br-lg" aria-hidden="true"></span>
                <?php endif; ?>
                <a class="inline-flex items-center w-full text-sm font-semibold transition-colors duration-150 hover:text-gray-800 dark:hover:text-gray-200 <?php echo $currentPage == 'alert_log' ? 'text-gray-800 dark:text-gray-100' : '' ?>" href="index.php?page=alert_log">
                    <i class="fas fa-history w-5 h-5 flex items-center justify-center"></i>
                    <span class="ml-4">Nhật ký và Cảnh báo</span>
                </a>
            </li>
            <?php endif; ?>

            <?php if (hasPermission('tudong.view')): ?>
            <li class="relative px-6 py-3">
                <?php if ($currentPage == 'tudong'): ?>
                    <span class="absolute inset-y-0 left-0 w-1 bg-blue-600 rounded-tr-lg rounded-br-lg" aria-hidden="true"></span>
                <?php endif; ?>
                <a class="inline-flex items-center w-full text-sm font-semibold transition-colors duration-150 hover:text-gray-800 dark:hover:text-gray-200 <?php echo $currentPage == 'tudong' ? 'text-gray-800 dark:text-gray-100' : '' ?>" href="index.php?page=tudong">
                    <i class="fas fa-magic w-5 h-5 flex items-center justify-center"></i>
                    <span class="ml-4">Cấu hình và Kịch bản</span>
                </a>
            </li>
            <?php endif; ?>

            <?php if (hasPermission('thietbi.view')): ?>
            <li class="relative px-6 py-3">
                <?php if ($currentPage == 'thietbi'): ?>
                    <span class="absolute inset-y-0 left-0 w-1 bg-blue-600 rounded-tr-lg rounded-br-lg" aria-hidden="true"></span>
                <?php endif; ?>
                <a class="inline-flex items-center w-full text-sm font-semibold transition-colors duration-150 hover:text-gray-800 dark:hover:text-gray-200 <?php echo $currentPage == 'thietbi' ? 'text-gray-800 dark:text-gray-100' : '' ?>" href="index.php?page=thietbi">
                    <i class="fas fa-door-open w-5 h-5 flex items-center justify-center"></i>
                    <span class="ml-4">Phòng và Thiết bị</span>
                </a>
            </li>
            <?php endif; ?>

            <?php if (hasPermission('nguoidung.view')): ?>
            <li class="relative px-6 py-3 border-t dark:border-gray-700 mt-2">
                <?php if ($currentPage == 'users'): ?>
                    <span class="absolute inset-y-0 left-0 w-1 bg-blue-600 rounded-tr-lg rounded-br-lg" aria-hidden="true"></span>
                <?php endif; ?>
                <a class="inline-flex items-center w-full text-sm font-semibold transition-colors duration-150 hover:text-gray-800 dark:hover:text-gray-200 <?php echo $currentPage == 'users' ? 'text-gray-800 dark:text-gray-100' : '' ?>" href="index.php?page=users">
                    <i class="fas fa-user-shield w-5 h-5 flex items-center justify-center"></i>
                    <span class="ml-4">Người dùng và Phân quyền</span>
                </a>
            </li>
            <?php endif; ?>
        </ul>
    </div>
</aside>

<div x-show="isSideMenuOpen" 
     x-transition:enter="transition ease-in-out duration-150"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     x-transition:leave="transition ease-in-out duration-150"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0"
     class="fixed inset-0 z-10 flex items-end bg-black bg-opacity-50 sm:items-center sm:justify-center">
</div>

<aside class="fixed inset-y-0 z-20 flex-shrink-0 w-64 mt-16 overflow-y-auto bg-white dark:bg-gray-800 md:hidden"
       x-show="isSideMenuOpen"
       x-transition:enter="transition ease-in-out duration-150"
       x-transition:enter-start="opacity-0 transform -translate-x-20"
       x-transition:enter-end="opacity-100"
       x-transition:leave="transition ease-in-out duration-150"
       x-transition:leave-start="opacity-100"
       x-transition:leave-end="opacity-0 transform -translate-x-20"
       @click.away="closeSideMenu"
       @keydown.escape="closeSideMenu">
    
    <div class="py-4 text-gray-500 dark:text-gray-400">
        <a class="ml-6 text-lg font-bold text-gray-800 dark:text-gray-200 flex items-center" href="index.php?page=dashboard">
            <div class="p-1 bg-blue-600 rounded-lg mr-3">
                <i class="fas fa-shield-alt text-white text-sm"></i>
            </div>
            <span>SafeStay IoT</span>
        </a>
        
        <ul class="mt-6">
            <?php if (hasPermission('trangchu.view')): ?>
            <li class="relative px-6 py-3">
                <?php if ($currentPage == 'dashboard'): ?>
                    <span class="absolute inset-y-0 left-0 w-1 bg-blue-600 rounded-tr-lg rounded-br-lg" aria-hidden="true"></span>
                <?php endif; ?>
                <a class="inline-flex items-center w-full text-sm font-semibold transition-colors duration-150 hover:text-gray-800 dark:hover:text-gray-200 <?php echo $currentPage == 'dashboard' ? 'text-gray-800 dark:text-gray-100' : '' ?>" href="index.php?page=dashboard">
                    <i class="fas fa-home w-5 h-5 flex items-center justify-center"></i>
                    <span class="ml-4">Tổng quan</span>
                </a>
            </li>
            <?php endif; ?>

            <?php if (hasPermission('phantich.view')): ?>
            <li class="relative px-6 py-3">
                <?php if ($currentPage == 'phantich'): ?>
                    <span class="absolute inset-y-0 left-0 w-1 bg-blue-600 rounded-tr-lg rounded-br-lg" aria-hidden="true"></span>
                <?php endif; ?>
                <a class="inline-flex items-center w-full text-sm font-semibold transition-colors duration-150 hover:text-gray-800 dark:hover:text-gray-200 <?php echo $currentPage == 'phantich' ? 'text-gray-800 dark:text-gray-100' : '' ?>" href="index.php?page=phantich">
                    <i class="fas fa-chart-line w-5 h-5 flex items-center justify-center"></i>
                    <span class="ml-4">Phân tích và Báo cáo</span>
                </a>
            </li>
            <?php endif; ?>

            <?php if (hasPermission('canhbao.view')): ?>
            <li class="relative px-6 py-3">
                <?php if ($currentPage == 'alert_log'): ?>
                    <span class="absolute inset-y-0 left-0 w-1 bg-blue-600 rounded-tr-lg rounded-br-lg" aria-hidden="true"></span>
                <?php endif; ?>
                <a class="inline-flex items-center w-full text-sm font-semibold transition-colors duration-150 hover:text-gray-800 dark:hover:text-gray-200 <?php echo $currentPage == 'alert_log' ? 'text-gray-800 dark:text-gray-100' : '' ?>" href="index.php?page=alert_log">
                    <i class="fas fa-history w-5 h-5 flex items-center justify-center"></i>
                    <span class="ml-4">Nhật ký và Cảnh báo</span>
                </a>
            </li>
            <?php endif; ?>

            <?php if (hasPermission('tudong.view')): ?>
            <li class="relative px-6 py-3">
                <?php if ($currentPage == 'tudong'): ?>
                    <span class="absolute inset-y-0 left-0 w-1 bg-blue-600 rounded-tr-lg rounded-br-lg" aria-hidden="true"></span>
                <?php endif; ?>
                <a class="inline-flex items-center w-full text-sm font-semibold transition-colors duration-150 hover:text-gray-800 dark:hover:text-gray-200 <?php echo $currentPage == 'tudong' ? 'text-gray-800 dark:text-gray-100' : '' ?>" href="index.php?page=tudong">
                    <i class="fas fa-magic w-5 h-5 flex items-center justify-center"></i>
                    <span class="ml-4">Cấu hình và Kịch bản</span>
                </a>
            </li>
            <?php endif; ?>

            <?php if (hasPermission('thietbi.view')): ?>
            <li class="relative px-6 py-3">
                <?php if ($currentPage == 'thietbi'): ?>
                    <span class="absolute inset-y-0 left-0 w-1 bg-blue-600 rounded-tr-lg rounded-br-lg" aria-hidden="true"></span>
                <?php endif; ?>
                <a class="inline-flex items-center w-full text-sm font-semibold transition-colors duration-150 hover:text-gray-800 dark:hover:text-gray-200 <?php echo $currentPage == 'thietbi' ? 'text-gray-800 dark:text-gray-100' : '' ?>" href="index.php?page=thietbi">
                    <i class="fas fa-door-open w-5 h-5 flex items-center justify-center"></i>
                    <span class="ml-4">Phòng và Thiết bị</span>
                </a>
            </li>
            <?php endif; ?>

            <?php if (hasPermission('nguoidung.view')): ?>
            <li class="relative px-6 py-3 border-t dark:border-gray-700 mt-2">
                <?php if ($currentPage == 'users'): ?>
                    <span class="absolute inset-y-0 left-0 w-1 bg-blue-600 rounded-tr-lg rounded-br-lg" aria-hidden="true"></span>
                <?php endif; ?>
                <a class="inline-flex items-center w-full text-sm font-semibold transition-colors duration-150 hover:text-gray-800 dark:hover:text-gray-200 <?php echo $currentPage == 'users' ? 'text-gray-800 dark:text-gray-100' : '' ?>" href="index.php?page=users">
                    <i class="fas fa-user-shield w-5 h-5 flex items-center justify-center"></i>
                    <span class="ml-4">Người dùng và Phân quyền</span>
                </a>
            </li>
            <?php endif; ?>
        </ul>
    </div>
</aside>
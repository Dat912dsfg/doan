<!DOCTYPE html>
<html :class="{ 'dark': dark }" x-data="data()" lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'IoT Warehouse' ?></title>
    
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" />

    <link rel="stylesheet" href="css/windmill.css">
    
    <script src="https://cdn.tailwindcss.com"></script>
    
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                    },
                    colors: {
                        blue: {
                            50: '#eff6ff',
                            100: '#dbeafe',
                            200: '#bfdbfe',
                            300: '#93c5fd',
                            400: '#60a5fa',
                            500: '#3b82f6',
                            600: '#2563eb',
                            700: '#1d4ed8',
                            800: '#1e40af',
                            900: '#1e3a8a',
                        },
                        gray: {
                            50: '#f9fafb', 100: '#f4f5f7', 200: '#e5e7eb', 300: '#d1d5db',
                            400: '#9ca3af', 500: '#6b7280', 600: '#4b5563', 700: '#374151',
                            800: '#1f2937', 900: '#111827',
                        }
                    },
                    boxShadow: {
                        'outline-blue': '0 0 0 3px rgba(59, 130, 246, 0.45)', 
                        'outline-gray': '0 0 0 3px rgba(156, 163, 175, 0.5)',
                    }
                }
            }
        }
    </script>

    <style type="text/tailwindcss">
        @layer components {
            .form-input, .form-select, .form-textarea {
                @apply block w-full text-sm rounded-md border border-gray-300 focus:border-blue-400 focus:outline-none focus:ring focus:ring-blue-200 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 dark:focus:ring-gray-500 p-2;
            }
        }
        
        [x-cloak] { 
            display: none !important; 
        }
    </style>

    <script src="js/init-alpine.js"></script>
    <script src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.x.x/dist/alpine.min.js" defer></script>
</head>
<body class="bg-gray-50 text-gray-900 dark:bg-gray-900 dark:text-gray-200">
    
    <div class="flex h-screen bg-gray-50 dark:bg-gray-900" 
          :class="{ 'overflow-hidden': isSideMenuOpen }">
        
        <?php include __DIR__ . '/../dungchung/sidebar.php'; ?>

        <div class="flex flex-col flex-1 w-full">
            
            <?php include __DIR__ . '/../dungchung/header.php'; ?>

            <main class="h-full overflow-y-auto">
                <div class="container px-6 mx-auto grid">
                    <?php 
                        if (isset($content)) {
                            echo $content; 
                        } else {
                            echo "<div class='p-4 text-blue-600 font-bold dark:text-blue-400'>Không có dữ liệu hiển thị</div>"; 
                        }
                    ?>
                </div>
            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <?php if (isset($alert)): ?>
    <script>
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer)
                toast.addEventListener('mouseleave', Swal.resumeTimer)
            }
        });

        Toast.fire({
            icon: '<?= $alert['type'] ?>',
            title: '<?= $alert['title'] ?>',
            text: '<?= $alert['text'] ?>'
        });
    </script>
    <?php endif; ?>
    <?php include $viewDir . '/dungchung/modal_confirm.php'; ?>
    <script src="js/main.js"></script>
</body>
</html>
<div class="flex-1 h-full max-w-4xl mx-auto overflow-hidden bg-white rounded-lg shadow-xl dark:bg-gray-800">
    <div class="flex flex-col overflow-y-auto md:flex-row">
        <div class="h-32 md:h-auto md:w-1/2">
            <img aria-hidden="true" class="object-cover w-full h-full dark:hidden" 
                 src="img/login-office.jpeg" alt="Office" />
            <img aria-hidden="true" class="hidden object-cover w-full h-full dark:block" 
                 src="img/login-office.jpeg" alt="Office" />
        </div>
        
        <div class="flex items-center justify-center p-6 sm:p-12 md:w-1/2">
            <div class="w-full">
                <h1 class="mb-4 text-xl font-semibold text-gray-700 dark:text-gray-200">
                    Đăng nhập hệ thống
                </h1>

                <?php if (isset($_SESSION['error'])): ?>
                    <div class="flex items-center justify-between p-4 mb-4 text-sm font-semibold text-blue-700 bg-blue-100 rounded-lg dark:bg-blue-200 dark:text-blue-800" role="alert">
                        <div class="flex items-center">
                            <i class="fas fa-exclamation-circle mr-2"></i>
                            <span><?= $_SESSION['error']; ?></span>
                        </div>
                    </div>
                    <?php unset($_SESSION['error']); ?>
                <?php endif; ?>
                
                <form action="index.php?page=auth_xuly_dangnhap" method="POST">
                    <label class="block text-sm">
                        <span class="text-gray-700 dark:text-gray-400">Tên đăng nhập (Email)</span>
                        <input name="username" 
                               class="block w-full mt-1 text-sm border border-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-blue-500 focus:ring focus:ring-blue-200 focus:outline-none dark:text-gray-300 form-input rounded-md p-2" 
                               placeholder="admin@gmail.com" requiblue />
                    </label>
                    
                    <label class="block mt-4 text-sm">
                        <span class="text-gray-700 dark:text-gray-400">Mật khẩu</span>
                        <input name="password" 
                               class="block w-full mt-1 text-sm border border-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-blue-500 focus:ring focus:ring-blue-200 focus:outline-none dark:text-gray-300 form-input rounded-md p-2" 
                               placeholder="***************" type="password" requiblue />
                    </label>

                    <button type="submit" 
                            class="block w-full px-4 py-2 mt-4 text-sm font-medium leading-5 text-center text-white transition-colors duration-150 bg-blue-800 border border-transparent rounded-lg active:bg-blue-900 hover:bg-blue-900 focus:outline-none focus:ring focus:ring-blue-300">
                        Đăng nhập
                    </button>
                </form>

                <hr class="my-8" />

                <button
                    id="btn-google-login"
                    type="button"
                    class="flex items-center justify-center w-full px-4 py-2 text-sm font-medium leading-5 text-gray-700 transition-colors duration-150 border border-gray-300 rounded-lg dark:text-gray-400 active:bg-transparent hover:border-blue-800 focus:border-blue-800 active:text-blue-800 focus:outline-none focus:ring focus:ring-gray-200"
                >
                    <svg class="w-4 h-4 mr-2" aria-hidden="true" viewBox="0 0 48 48" fill="currentColor">
                        <path fill="#EA4335" d="M24 9.5c3.54 0 6.71 1.22 9.21 3.6l6.85-6.85C35.9 2.38 30.47 0 24 0 14.62 0 6.51 5.38 2.56 13.22l7.98 6.19C12.43 13.72 17.74 9.5 24 9.5z"></path>
                        <path fill="#4285F4" d="M46.98 24.55c0-1.57-.15-3.09-.38-4.55H24v9.02h12.94c-.58 2.96-2.26 5.48-4.78 7.18l7.73 6c4.51-4.18 7.09-10.36 7.09-17.65z"></path>
                        <path fill="#FBBC05" d="M10.53 28.59c-.48-1.45-.76-2.99-.76-4.59s.27-3.14.76-4.59l-7.98-6.19C.92 16.46 0 20.12 0 24c0 3.88.92 7.54 2.56 10.78l7.97-6.19z"></path>
                        <path fill="#34A853" d="M24 48c6.48 0 11.93-2.13 15.89-5.81l-7.73-6c-2.15 1.45-4.92 2.3-8.16 2.3-6.26 0-11.57-4.22-13.47-9.91l-7.98 6.19C6.51 42.62 14.62 48 24 48z"></path>
                    </svg>
                    Google
                </button>

                <p class="mt-4">
                    <a class="text-sm font-medium text-blue-800 dark:text-blue-400 hover:underline" href="index.php?page=quen_mk">
                        Quên mật khẩu?
                    </a>
                </p>
            </div>
        </div>
    </div>
</div>

<script type="module">
        import { initializeApp } from 'https://www.gstatic.com/firebasejs/10.8.0/firebase-app.js';
        import { getAuth, signInWithPopup, GoogleAuthProvider } from 'https://www.gstatic.com/firebasejs/10.8.0/firebase-auth.js';

        const firebaseConfig = {
            apiKey: "AIzaSyAj6ByNQyVEN4gfCe7lYFre1B56DSvWdt0",
            authDomain: "webquantri-51411.firebaseapp.com",
            projectId: "webquantri-51411"
        };

        const app = initializeApp(firebaseConfig);
        const auth = getAuth(app);
        const provider = new GoogleAuthProvider();

        document.getElementById('btn-google-login').addEventListener('click', async () => {
            try {
                const result = await signInWithPopup(auth, provider);
                const idToken = await result.user.getIdToken();
                
                const response = await fetch('./index.php?page=google_login', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ idToken: idToken })
                });
                
                const data = await response.json();
                
                if (data.success) {
                    window.location.href = './index.php?url=dashboard';
                } else {
                    alert(data.message);
                }
            } catch (error) {
                alert('Lỗi: ' + error.message);
            }
        });
    </script>
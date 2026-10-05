<!-- ไฟล์: views/login.php -->
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#2563eb">
    <title>เข้าสู่ระบบ | Campus Care</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Prompt:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        body { font-family: 'Prompt', sans-serif; }
        @keyframes fadeUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
        .animate-fade-up { animation: fadeUp 0.6s ease-out forwards; }
        .delay-100 { animation-delay: 0.1s; opacity: 0; }
        .delay-200 { animation-delay: 0.2s; opacity: 0; }
        .delay-300 { animation-delay: 0.3s; opacity: 0; }
        @keyframes float { 0%, 100% { transform: translateY(0); } 50% { transform: translateY(-20px); } }
        .animate-float { animation: float 6s ease-in-out infinite; }
        .input-group:focus-within {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px -5px rgba(37, 99, 235, 0.2);
            border-color: #2563eb;
        }
    </style>
</head>
<body class="bg-slate-50 min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 relative overflow-x-hidden">

    <!-- พื้นหลังลอยไปมา -->
    <div class="hidden md:block absolute top-10 right-10 w-80 h-80 bg-blue-400 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-float"></div>
    <div class="hidden md:block absolute bottom-10 left-10 w-80 h-80 bg-purple-400 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-float" style="animation-delay: 2s;"></div>

    <div class="animate-fade-up bg-white p-6 sm:p-8 rounded-2xl shadow-xl w-full max-w-md relative z-10 border border-gray-100">
        
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-blue-50 text-blue-600 mb-4 shadow-sm hover:scale-110 hover:bg-blue-100 transition-all duration-300">
                <i class="fa-solid fa-graduation-cap text-3xl"></i>
            </div>
            <h1 class="text-2xl sm:text-3xl font-bold text-blue-900 tracking-tight">ยินดีต้อนรับกลับมา!</h1>
            <p class="text-gray-500 mt-2 text-sm">เข้าสู่ระบบเพื่อดำเนินการแจ้งเหตุหรือช่วยเหลือเพื่อนๆ</p>
        </div>

        <form id="loginForm" class="space-y-5">
            
            <!-- อีเมล -->
            <div class="animate-fade-up delay-100">
                <label class="block text-sm font-medium text-gray-700 mb-1">อีเมลมหาวิทยาลัย</label>
                <div class="input-group group relative flex items-center border border-gray-300 rounded-lg bg-white transition-all duration-300 hover:border-blue-400">
                    <span class="pl-4 text-gray-400 group-focus-within:text-blue-600 transition-colors duration-300"><i class="fa-solid fa-envelope"></i></span>
                    <input type="email" name="email" required 
                           class="w-full px-3 py-3 outline-none bg-transparent text-gray-700 placeholder-gray-400"
                           placeholder="student@webmail.npru.ac.th">
                </div>
            </div>

            <!-- รหัสผ่าน -->
            <div class="animate-fade-up delay-200">
                <div class="flex justify-between items-center mb-1">
                    <label class="block text-sm font-medium text-gray-700">รหัสผ่าน</label>
                    <a href="#" class="text-xs text-blue-600 hover:underline">ลืมรหัสผ่าน?</a>
                </div>
                <div class="input-group group relative flex items-center border border-gray-300 rounded-lg bg-white transition-all duration-300 hover:border-blue-400">
                    <span class="pl-4 text-gray-400 group-focus-within:text-blue-600 transition-colors duration-300"><i class="fa-solid fa-lock"></i></span>
                    <input type="password" name="password" id="loginPassword" required 
                           class="w-full px-3 py-3 outline-none bg-transparent text-gray-700 placeholder-gray-400"
                           placeholder="กรอกรหัสผ่านของคุณ">
                    <button type="button" id="toggleLoginPassword" class="pr-4 text-gray-400 hover:text-blue-600 focus:outline-none transition-colors duration-300">
                        <i class="fa-regular fa-eye" id="loginEyeIcon"></i>
                    </button>
                </div>
            </div>

            <!-- ปุ่ม Submit -->
            <button type="submit" id="loginBtn"
                    class="w-full bg-blue-600 text-white font-semibold py-3 px-4 rounded-lg shadow-md hover:bg-blue-700 hover:shadow-lg focus:ring-4 focus:ring-blue-300 transform active:scale-95 transition-all duration-200 animate-fade-up delay-300">
                <i class="fa-solid fa-right-to-bracket mr-2"></i> เข้าสู่ระบบ
            </button>

        </form>

        <div class="mt-6 animate-fade-up delay-300">
            <div class="relative">
                <div class="absolute inset-0 flex items-center"><div class="w-full border-t border-gray-200"></div></div>
                <div class="relative flex justify-center text-sm"><span class="px-3 bg-white text-gray-500 text-xs font-medium">หรือเข้าสู่ระบบด้วย</span></div>
            </div>
            
            <!-- ปุ่ม Google -->
            <div class="mt-5">
                <button type="button" class="w-full flex justify-center items-center py-2.5 px-4 border border-gray-300 rounded-lg shadow-sm bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 hover:-translate-y-1 transition-all duration-300">
                    <svg class="h-5 w-5 mr-2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"/><path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/><path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" fill="#FBBC05"/><path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/></svg>
                    Google (@webmail.npru.ac.th)
                </button>
            </div>
        </div>

        <div class="text-center mt-8 animate-fade-up delay-300">
            <p class="text-sm text-gray-600">
                ยังไม่มีบัญชีใช่ไหม? <a href="register.php" class="text-blue-600 font-semibold hover:text-blue-800 hover:underline transition-colors">สมัครสมาชิกฟรี</a>
            </p>
        </div>
    </div>

    <!-- Script เปิด/ปิดตาดูรหัสผ่าน -->
    <script>
        document.getElementById('toggleLoginPassword').addEventListener('click', function () {
            const input = document.getElementById('loginPassword');
            const icon = document.getElementById('loginEyeIcon');
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.replace('fa-eye', 'fa-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.replace('fa-eye-slash', 'fa-eye');
            }
        });
    </script>
    <!-- โหลด JS สำหรับจัดการฟอร์มล็อกอิน -->
    <script src="../assets/js/auth_login.js"></script>
</body>
</html>
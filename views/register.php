<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#2563eb">
    <title>สมัครสมาชิก | Campus Care</title>
    
    <!-- Tailwind CSS, SweetAlert2, FontAwesome, Google Fonts -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Prompt:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        body { font-family: 'Prompt', sans-serif; }
        
        /* แอนิเมชันตอนโหลดหน้าเว็บ (ค่อยๆ เลื่อนขึ้น) */
        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-fade-up { animation: fadeUp 0.6s ease-out forwards; }
        
        /* ทำให้แอนิเมชันแต่ละช่องเหลื่อมเวลากัน (Stagger) */
        .delay-100 { animation-delay: 0.1s; opacity: 0; }
        .delay-200 { animation-delay: 0.2s; opacity: 0; }
        .delay-300 { animation-delay: 0.3s; opacity: 0; }
        .delay-400 { animation-delay: 0.4s; opacity: 0; }
        .delay-500 { animation-delay: 0.5s; opacity: 0; }
        
        /* แอนิเมชันพื้นหลังลอยไปมา */
        @keyframes float {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-20px); }
        }
        .animate-float { animation: float 6s ease-in-out infinite; }

        /* เอฟเฟกต์เวลากดที่ช่องกรอกข้อมูล */
        .input-group:focus-within {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px -5px rgba(37, 99, 235, 0.2);
            border-color: #2563eb;
        }
        input[type="number"]::-webkit-outer-spin-button,
        input[type="number"]::-webkit-inner-spin-button {
            -webkit-appearance: none; margin: 0;
        }
    </style>
</head>
<!-- แก้ไข: เปลี่ยน overflow-hidden เป็น overflow-x-hidden และเพิ่ม py-12 เพื่อให้เลื่อนหน้าจอแนวตั้งได้ -->
<body class="bg-slate-50 min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8 relative overflow-x-hidden">

    <!-- พื้นหลังตกแต่ง (เพิ่ม animate-float ให้ลอยได้) -->
    <div class="hidden md:block absolute top-10 left-10 w-96 h-96 bg-blue-400 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-float" style="animation-delay: 0s;"></div>
    <div class="hidden md:block absolute bottom-10 right-10 w-96 h-96 bg-purple-400 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-float" style="animation-delay: 2s;"></div>

    <div class="animate-fade-up bg-white p-6 sm:p-8 rounded-2xl shadow-xl w-full max-w-md relative z-10 border border-gray-100">
        
        <div class="text-center mb-8">
            <!-- เพิ่มเอฟเฟกต์เด้งๆ (Pulse) ตอนเอาเมาส์ชี้โลโก้ -->
            <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-blue-50 text-blue-600 mb-4 shadow-sm hover:scale-110 hover:bg-blue-100 transition-all duration-300">
                <i class="fa-solid fa-graduation-cap text-3xl"></i>
            </div>
            <h1 class="text-2xl sm:text-3xl font-bold text-blue-900 tracking-tight">Campus Care</h1>
            <p class="text-gray-500 mt-2 text-sm sm:text-base">สร้างเครือข่ายฮีโร่ในมหาวิทยาลัยของคุณ</p>
        </div>

        <form id="registerForm" class="space-y-4 sm:space-y-5">
            
            <!-- รหัสนักศึกษา -->
            <div class="animate-fade-up delay-100">
                <label class="block text-sm font-medium text-gray-700 mb-1">รหัสนักศึกษา / บุคลากร</label>
                <!-- เพิ่ม group เพื่อให้ไอคอนเปลี่ยนสีเวลาคลิก -->
                <div class="input-group group relative flex items-center border border-gray-300 rounded-lg bg-white transition-all duration-300 hover:border-blue-400">
                    <span class="pl-4 text-gray-400 group-focus-within:text-blue-600 transition-colors duration-300"><i class="fa-solid fa-id-badge"></i></span>
                    <input type="text" name="student_id" required pattern="[0-9]+" title="กรุณากรอกตัวเลขเท่านั้น"
                           class="w-full px-3 py-2.5 outline-none bg-transparent text-gray-700 placeholder-gray-400"
                           placeholder="เช่น 684259042">
                </div>
            </div>

            <!-- ชื่อ-นามสกุล -->
            <div class="animate-fade-up delay-200">
                <label class="block text-sm font-medium text-gray-700 mb-1">ชื่อ - นามสกุล</label>
                <div class="input-group group relative flex items-center border border-gray-300 rounded-lg bg-white transition-all duration-300 hover:border-blue-400">
                    <span class="pl-4 text-gray-400 group-focus-within:text-blue-600 transition-colors duration-300"><i class="fa-solid fa-user"></i></span>
                    <input type="text" name="full_name" required 
                           class="w-full px-3 py-2.5 outline-none bg-transparent text-gray-700 placeholder-gray-400"
                           placeholder="ไม่ต้องใส่คำนำหน้า">
                </div>
            </div>

            <!-- อีเมล -->
            <div class="animate-fade-up delay-300">
                <label class="block text-sm font-medium text-gray-700 mb-1">อีเมลมหาวิทยาลัย</label>
                <div class="input-group group relative flex items-center border border-gray-300 rounded-lg bg-white transition-all duration-300 hover:border-blue-400">
                    <span class="pl-4 text-gray-400 group-focus-within:text-blue-600 transition-colors duration-300"><i class="fa-solid fa-envelope"></i></span>
                    <input type="email" name="email" id="email" required 
                           class="w-full px-3 py-2.5 outline-none bg-transparent text-gray-700 placeholder-gray-400"
                           placeholder="student@webmail.npru.ac.th">
                </div>
                <p class="text-[11px] sm:text-xs text-red-500 mt-1.5 flex items-center">
                    <i class="fa-solid fa-circle-exclamation mr-1"></i> สงวนสิทธิ์เฉพาะโดเมนของมหาวิทยาลัยเท่านั้น
                </p>
            </div>

            <!-- รหัสผ่าน พร้อมแถบความปลอดภัย -->
            <div class="animate-fade-up delay-400">
                <label class="block text-sm font-medium text-gray-700 mb-1">รหัสผ่าน</label>
                <div class="input-group group relative flex items-center border border-gray-300 rounded-lg bg-white transition-all duration-300 hover:border-blue-400">
                    <span class="pl-4 text-gray-400 group-focus-within:text-blue-600 transition-colors duration-300"><i class="fa-solid fa-lock"></i></span>
                    <input type="password" name="password" id="passwordInput" required minlength="6"
                           class="w-full px-3 py-2.5 outline-none bg-transparent text-gray-700 placeholder-gray-400"
                           placeholder="ตั้งรหัสผ่าน 6 ตัวอักษรขึ้นไป">
                    <button type="button" id="togglePassword" class="pr-4 text-gray-400 hover:text-blue-600 focus:outline-none transition-colors duration-300">
                        <i class="fa-regular fa-eye" id="eyeIcon"></i>
                    </button>
                </div>
                <!-- แถบสีวัดระดับรหัสผ่าน -->
                <div class="mt-2 flex gap-1 h-1.5 w-full rounded-full overflow-hidden bg-gray-200">
                    <div id="str-1" class="h-full w-1/3 transition-all duration-300 bg-gray-200"></div>
                    <div id="str-2" class="h-full w-1/3 transition-all duration-300 bg-gray-200"></div>
                    <div id="str-3" class="h-full w-1/3 transition-all duration-300 bg-gray-200"></div>
                </div>
                <p id="str-text" class="text-[11px] text-gray-500 mt-1 text-right h-4"></p>
            </div>

            <!-- ยืนยันรหัสผ่าน -->
            <div class="animate-fade-up delay-500">
                <label class="block text-sm font-medium text-gray-700 mb-1">ยืนยันรหัสผ่านอีกครั้ง</label>
                <div class="input-group group relative flex items-center border border-gray-300 rounded-lg bg-white transition-all duration-300 hover:border-blue-400">
                    <span class="pl-4 text-gray-400 group-focus-within:text-blue-600 transition-colors duration-300"><i class="fa-solid fa-shield-halved"></i></span>
                    <input type="password" id="confirmPasswordInput" required minlength="6"
                           class="w-full px-3 py-2.5 outline-none bg-transparent text-gray-700 placeholder-gray-400"
                           placeholder="พิมพ์รหัสผ่านให้ตรงกัน">
                    <button type="button" id="toggleConfirmPassword" class="pr-4 text-gray-400 hover:text-blue-600 focus:outline-none transition-colors duration-300">
                        <i class="fa-regular fa-eye" id="eyeIconConfirm"></i>
                    </button>
                </div>
                <p id="match-text" class="text-[11px] mt-1.5 hidden"></p>
            </div>

            <!-- Checkbox ยอมรับเงื่อนไข -->
            <div class="flex items-start pt-2 animate-fade-up delay-500">
                <div class="flex items-center h-5">
                    <input id="terms" type="checkbox" required class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 cursor-pointer transition-colors">
                </div>
                <div class="ml-2 text-sm">
                    <label for="terms" class="font-medium text-gray-700 cursor-pointer select-none">ฉันยอมรับ <a href="#" class="text-blue-600 hover:underline">เงื่อนไขการใช้งาน</a> และ <a href="#" class="text-blue-600 hover:underline">นโยบายความเป็นส่วนตัว</a></label>
                </div>
            </div>

            <!-- ปุ่ม Submit -->
            <button type="submit" id="submitBtn"
                    class="w-full bg-blue-600 text-white font-semibold py-3 px-4 rounded-lg shadow-md hover:bg-blue-700 hover:shadow-lg focus:ring-4 focus:ring-blue-300 transform active:scale-95 transition-all duration-200 animate-fade-up delay-500">
                <i class="fa-solid fa-user-plus mr-2"></i> สมัครสมาชิก
            </button>

        </form>

        <!-- เส้นแบ่ง -->
        <div class="mt-6 animate-fade-up delay-500">
            <div class="relative">
                <div class="absolute inset-0 flex items-center">
                    <div class="w-full border-t border-gray-200"></div>
                </div>
                <div class="relative flex justify-center text-sm">
                    <span class="px-3 bg-white text-gray-500 text-xs font-medium">หรือดำเนินการด้วย</span>
                </div>
            </div>
            
            <!-- ปุ่ม Google -->
            <div class="mt-5">
                <button type="button" class="w-full flex justify-center items-center py-2.5 px-4 border border-gray-300 rounded-lg shadow-sm bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 hover:-translate-y-1 transition-all duration-300">
                    <svg class="h-5 w-5 mr-2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"/><path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/><path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" fill="#FBBC05"/><path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/></svg>
                    Google (@webmail.npru.ac.th)
                </button>
            </div>
        </div>

        <div class="text-center mt-8 animate-fade-up delay-500">
            <p class="text-sm text-gray-600">
                มีบัญชีอยู่แล้ว? <a href="login.php" class="text-blue-600 font-semibold hover:text-blue-800 hover:underline transition-colors">เข้าสู่ระบบ</a>
            </p>
        </div>
    </div>

    <!-- Script สำหรับลูกเล่น UI หน้าบ้าน -->
    <script>
        // 1. ฟังก์ชันเปิด/ปิดตา รหัสผ่าน
        function setupPasswordToggle(toggleBtnId, inputId, iconId) {
            document.getElementById(toggleBtnId).addEventListener('click', function () {
                const input = document.getElementById(inputId);
                const icon = document.getElementById(iconId);
                if (input.type === 'password') {
                    input.type = 'text';
                    icon.classList.remove('fa-eye');
                    icon.classList.add('fa-eye-slash');
                } else {
                    input.type = 'password';
                    icon.classList.remove('fa-eye-slash');
                    icon.classList.add('fa-eye');
                }
            });
        }
        setupPasswordToggle('togglePassword', 'passwordInput', 'eyeIcon');
        setupPasswordToggle('toggleConfirmPassword', 'confirmPasswordInput', 'eyeIconConfirm');

        // 2. ฟังก์ชันตรวจสอบความแข็งแกร่งรหัสผ่าน (Password Strength)
        const passwordInput = document.getElementById('passwordInput');
        const str1 = document.getElementById('str-1');
        const str2 = document.getElementById('str-2');
        const str3 = document.getElementById('str-3');
        const strText = document.getElementById('str-text');

        passwordInput.addEventListener('input', function() {
            const val = passwordInput.value;
            let score = 0;
            
            // รีเซ็ตสีทั้งหมดก่อน
            str1.className = 'h-full w-1/3 transition-all duration-300 bg-gray-200';
            str2.className = 'h-full w-1/3 transition-all duration-300 bg-gray-200';
            str3.className = 'h-full w-1/3 transition-all duration-300 bg-gray-200';
            strText.textContent = '';

            if(val.length === 0) {
                checkPasswordMatch();
                return; // ถ้าช่องว่างเปล่า ให้ออกเลย
            }

            if(val.length > 0) score++;
            if(val.length >= 6) score++;
            if(val.length >= 8 && /[A-Z]/.test(val) && /[0-9]/.test(val)) score++;

            if(score === 1) {
                str1.classList.replace('bg-gray-200', 'bg-red-500');
                strText.textContent = 'คาดเดาง่าย';
                strText.className = 'text-[11px] text-red-500 mt-1 text-right h-4';
            } else if(score === 2) {
                str1.classList.replace('bg-gray-200', 'bg-yellow-400');
                str2.classList.replace('bg-gray-200', 'bg-yellow-400');
                strText.textContent = 'ปานกลาง';
                strText.className = 'text-[11px] text-yellow-500 mt-1 text-right h-4';
            } else if(score >= 3) {
                str1.classList.replace('bg-gray-200', 'bg-green-500');
                str2.classList.replace('bg-gray-200', 'bg-green-500');
                str3.classList.replace('bg-gray-200', 'bg-green-500');
                strText.textContent = 'รัดกุมมาก';
                strText.className = 'text-[11px] text-green-500 mt-1 text-right h-4';
            }
            checkPasswordMatch(); 
        });

        // 3. ตรวจสอบว่ารหัสผ่าน 2 ช่องตรงกันหรือไม่
        const confirmPasswordInput = document.getElementById('confirmPasswordInput');
        const matchText = document.getElementById('match-text');

        function checkPasswordMatch() {
            if(confirmPasswordInput.value.length === 0) {
                matchText.classList.add('hidden');
                return;
            }
            matchText.classList.remove('hidden');
            if(passwordInput.value === confirmPasswordInput.value) {
                matchText.textContent = 'รหัสผ่านตรงกัน';
                matchText.className = 'text-[11px] mt-1.5 text-green-500 flex items-center before:content-["✓"] before:mr-1';
            } else {
                matchText.textContent = 'รหัสผ่านไม่ตรงกัน';
                matchText.className = 'text-[11px] mt-1.5 text-red-500 flex items-center before:content-["✕"] before:mr-1';
            }
        }
        confirmPasswordInput.addEventListener('input', checkPasswordMatch);

        // 4. ดักจับตอนกดปุ่ม Submit เพื่อเช็คความถูกต้อง
        const formEl = document.getElementById('registerForm');
        formEl.addEventListener('submit', function(e) {
            if(passwordInput.value !== confirmPasswordInput.value) {
                e.preventDefault(); 
                Swal.fire({
                    icon: 'error',
                    title: 'รหัสผ่านไม่ตรงกัน',
                    text: 'กรุณายืนยันรหัสผ่านทั้งสองช่องให้เหมือนกันครับ',
                    confirmButtonColor: '#2563eb'
                });
            }
        });

        // 5. แทรกแซงคำสั่ง Reset ของฟอร์ม เพื่อล้างแถบสีด้วย
        const originalReset = formEl.reset;
        formEl.reset = function() {
            // สั่งล้างช่องกรอกข้อความตามปกติ
            originalReset.call(this);
            
            // ล้างสีของหลอดรหัสผ่านและข้อความ
            str1.className = 'h-full w-1/3 transition-all duration-300 bg-gray-200';
            str2.className = 'h-full w-1/3 transition-all duration-300 bg-gray-200';
            str3.className = 'h-full w-1/3 transition-all duration-300 bg-gray-200';
            strText.textContent = '';
            
            // ล้างข้อความรหัสผ่านตรงกัน
            matchText.classList.add('hidden');
            matchText.textContent = '';

            // รีเซ็ตดวงตาให้ปิดเหมือนเดิม
            document.getElementById('passwordInput').type = 'password';
            document.getElementById('eyeIcon').className = 'fa-regular fa-eye';
            document.getElementById('confirmPasswordInput').type = 'password';
            document.getElementById('eyeIconConfirm').className = 'fa-regular fa-eye';
        };
    </script>

    <!-- เรียกใช้ JavaScript หลัก (AJAX สมัครสมาชิก) ของเราตามเดิม -->
    <script src="../assets/js/auth_register.js"></script>

</body>
</html>
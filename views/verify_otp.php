<!-- ไฟล์: views/verify_otp.php -->
<?php
$email = isset($_GET['email']) ? htmlspecialchars($_GET['email']) : '';
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#2563eb">
    <title>ยืนยันรหัส OTP | Campus Care</title>
    
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
        @keyframes float { 0%, 100% { transform: translateY(0); } 50% { transform: translateY(-20px); } }
        .animate-float { animation: float 6s ease-in-out infinite; }
        input[type="number"]::-webkit-outer-spin-button, input[type="number"]::-webkit-inner-spin-button { -webkit-appearance: none; margin: 0; }
        .otp-input:focus { transform: translateY(-2px); box-shadow: 0 10px 25px -5px rgba(37, 99, 235, 0.2); border-color: #2563eb; }
    </style>
</head>
<body class="bg-slate-50 min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 relative overflow-x-hidden">

    <div class="hidden md:block absolute top-10 left-10 w-96 h-96 bg-blue-400 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-float"></div>
    <div class="hidden md:block absolute bottom-10 right-10 w-96 h-96 bg-purple-400 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-float" style="animation-delay: 2s;"></div>

    <div class="animate-fade-up bg-white p-6 sm:p-8 rounded-2xl shadow-xl w-full max-w-md relative z-10 border border-gray-100 text-center">
        
        <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-blue-50 text-blue-600 mb-6 shadow-sm">
            <i class="fa-solid fa-envelope-open-text text-4xl"></i>
        </div>
        
        <h1 class="text-2xl sm:text-3xl font-bold text-blue-900 tracking-tight">ยืนยันอีเมลของคุณ</h1>
        <p class="text-gray-500 mt-3 text-sm sm:text-base leading-relaxed">
            เราได้ส่งรหัส OTP 6 หลักไปที่ <br>
            <span class="font-semibold text-blue-600 bg-blue-50 px-3 py-1 rounded-full mt-2 inline-block"><?php echo $email ? $email : 'อีเมลของคุณ'; ?></span>
        </p>

        <form id="otpForm" class="mt-8 space-y-6">
            <input type="hidden" id="userEmail" value="<?php echo $email; ?>">

            <div class="flex justify-center gap-2 sm:gap-3 animate-fade-up delay-100">
                <input type="number" class="otp-input w-10 sm:w-12 h-12 sm:h-14 text-center text-xl font-bold text-gray-800 bg-gray-50 border border-gray-300 rounded-lg outline-none transition-all duration-300" maxlength="1" required>
                <input type="number" class="otp-input w-10 sm:w-12 h-12 sm:h-14 text-center text-xl font-bold text-gray-800 bg-gray-50 border border-gray-300 rounded-lg outline-none transition-all duration-300" maxlength="1" required>
                <input type="number" class="otp-input w-10 sm:w-12 h-12 sm:h-14 text-center text-xl font-bold text-gray-800 bg-gray-50 border border-gray-300 rounded-lg outline-none transition-all duration-300" maxlength="1" required>
                <input type="number" class="otp-input w-10 sm:w-12 h-12 sm:h-14 text-center text-xl font-bold text-gray-800 bg-gray-50 border border-gray-300 rounded-lg outline-none transition-all duration-300" maxlength="1" required>
                <input type="number" class="otp-input w-10 sm:w-12 h-12 sm:h-14 text-center text-xl font-bold text-gray-800 bg-gray-50 border border-gray-300 rounded-lg outline-none transition-all duration-300" maxlength="1" required>
                <input type="number" class="otp-input w-10 sm:w-12 h-12 sm:h-14 text-center text-xl font-bold text-gray-800 bg-gray-50 border border-gray-300 rounded-lg outline-none transition-all duration-300" maxlength="1" required>
            </div>

            <button type="submit" id="verifyBtn" class="w-full bg-blue-600 text-white font-semibold py-3 px-4 rounded-lg shadow-md hover:bg-blue-700 hover:shadow-lg focus:ring-4 focus:ring-blue-300 transform active:scale-95 transition-all duration-200 animate-fade-up delay-200">
                <i class="fa-solid fa-check-circle mr-2"></i> ยืนยันรหัส OTP
            </button>
        </form>

        <div class="mt-8 text-sm text-gray-500 animate-fade-up delay-200">
            ยังไม่ได้รับรหัส? 
            <!-- ปุ่มส่งรหัสใหม่และตัวเลขนับถอยหลัง -->
            <button type="button" id="resendBtn" class="text-blue-600 font-medium hover:underline hover:text-blue-800 transition-colors disabled:text-gray-400 disabled:no-underline disabled:cursor-not-allowed">
                ส่งรหัสอีกครั้ง
            </button>
            <span id="resendTimer" class="text-gray-400 font-medium ml-1 hidden"></span>
        </div>
    </div>

    <script src="../assets/js/auth_otp.js"></script>
    <script>
        document.querySelectorAll('.otp-input').forEach(input => {
            input.addEventListener('input', function() { if(this.value.length > 1) this.value = this.value.slice(0, 1); });
        });
    </script>
</body>
</html>
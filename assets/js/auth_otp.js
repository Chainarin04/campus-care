// ไฟล์: assets/js/auth_otp.js

document.addEventListener('DOMContentLoaded', function() {
    const inputs = document.querySelectorAll('.otp-input');
    const form = document.getElementById('otpForm');
    const userEmail = document.getElementById('userEmail').value;

    // 1. ควบคุม UX กล่อง OTP
    inputs.forEach((input, index) => {
        input.addEventListener('keyup', function(e) {
            const val = e.target.value;
            if (val !== "") {
                if (index < inputs.length - 1) inputs[index + 1].focus();
            } else if (e.key === "Backspace") {
                if (index > 0) inputs[index - 1].focus();
            }
        });
        input.addEventListener('focus', function(e) { e.target.select(); });
    });

    // 2. ฟังก์ชันกดยืนยัน OTP
    if (form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            let otpCode = '';
            inputs.forEach(input => { otpCode += input.value; });

            if (otpCode.length !== 6) {
                Swal.fire({ icon: 'warning', title: 'ข้อมูลไม่ครบ', text: 'กรุณากรอกรหัส OTP ให้ครบ 6 หลัก' });
                return;
            }

            const submitBtn = document.getElementById('verifyBtn');
            const originalBtnText = submitBtn.innerHTML;
            submitBtn.innerHTML = `<i class="fa-solid fa-circle-notch fa-spin mr-2"></i> กำลังตรวจสอบ...`;
            submitBtn.disabled = true;

            const formData = new FormData();
            formData.append('email', userEmail);
            formData.append('otp_code', otpCode);

            fetch('../api/auth/verify_otp.php', { method: 'POST', body: formData })
                .then(response => response.json())
                .then(data => {
                    submitBtn.innerHTML = originalBtnText;
                    submitBtn.disabled = false;

                    if (data.status === 'success' || data.status === 'info') {
                        Swal.fire({
                            icon: 'success',
                            title: 'สำเร็จ!',
                            text: data.message,
                            confirmButtonColor: '#2563eb'
                        }).then(() => {
                            window.location.href = 'login.php';
                        });
                    } else {
                        Swal.fire({ icon: 'error', title: 'ผิดพลาด', text: data.message, confirmButtonColor: '#2563eb' });
                        inputs.forEach(input => input.value = '');
                        inputs[0].focus();
                    }
                })
                .catch(error => {
                    submitBtn.innerHTML = originalBtnText;
                    submitBtn.disabled = false;
                    Swal.fire({ icon: 'error', title: 'ระบบขัดข้อง', text: 'ไม่สามารถเชื่อมต่อเซิร์ฟเวอร์ได้' });
                });
        });
    }

    // 3. ฟังก์ชันปุ่ม "ส่งรหัสอีกครั้ง" (Resend OTP)
    const resendBtn = document.getElementById('resendBtn');
    const resendTimer = document.getElementById('resendTimer');

    if (resendBtn) {
        resendBtn.addEventListener('click', function() {
            resendBtn.disabled = true;
            resendBtn.innerHTML = `<i class="fa-solid fa-circle-notch fa-spin"></i> กำลังส่ง...`;

            const formData = new FormData();
            formData.append('email', userEmail);

            fetch('../api/auth/resend_otp.php', { method: 'POST', body: formData })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        // ใช้ html แทน text เพื่อให้แท็ก <span> สีน้ำเงินที่ส่งมาจาก PHP แสดงผลได้
                        Swal.fire({
                            icon: 'success',
                            title: 'ส่งรหัสใหม่แล้ว!',
                            html: data.message,
                            confirmButtonColor: '#2563eb'
                        });
                        startCooldown(60); // นับ 60 วิ
                    } else {
                        Swal.fire({ icon: 'error', title: 'ผิดพลาด', text: data.message });
                        resendBtn.disabled = false;
                        resendBtn.innerText = 'ส่งรหัสอีกครั้ง';
                    }
                })
                .catch(error => {
                    Swal.fire({ icon: 'error', title: 'ระบบขัดข้อง', text: 'ไม่สามารถเชื่อมต่อเซิร์ฟเวอร์ได้' });
                    resendBtn.disabled = false;
                    resendBtn.innerText = 'ส่งรหัสอีกครั้ง';
                });
        });
    }

    // 4. ตัวนับถอยหลัง
    function startCooldown(seconds) {
        let timeLeft = seconds;
        resendBtn.innerText = 'ส่งรหัสอีกครั้ง';
        resendBtn.disabled = true;
        resendTimer.classList.remove('hidden');

        const timerInterval = setInterval(() => {
            resendTimer.innerText = `(${timeLeft}s)`;
            timeLeft--;

            if (timeLeft < 0) {
                clearInterval(timerInterval);
                resendBtn.disabled = false;
                resendTimer.classList.add('hidden');
                resendTimer.innerText = '';
            }
        }, 1000);
    }
});
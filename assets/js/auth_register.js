// ไฟล์: assets/js/auth_register.js

document.addEventListener('DOMContentLoaded', function() {
    const registerForm = document.getElementById('registerForm');

    if (registerForm) {
        // ใช้ตัวแปรเก็บฟังก์ชัน Submit เดิมไว้กันการเรียกซ้ำ
        registerForm.addEventListener('submit', function(e) {
            e.preventDefault(); // ป้องกันหน้าเว็บรีเฟรช

            const email = document.getElementById('email').value;
            const password = document.getElementById('passwordInput');
            const confirmPassword = document.getElementById('confirmPasswordInput');

            // 1. ตรวจสอบโดเมนอีเมลฝั่งหน้าบ้าน
            if (!email.endsWith('@webmail.npru.ac.th')) {
                Swal.fire({
                    icon: 'warning',
                    title: 'อีเมลไม่ถูกต้อง',
                    text: 'กรุณาใช้อีเมลของมหาวิทยาลัย (@webmail.npru.ac.th) เท่านั้น',
                    confirmButtonColor: '#2563eb'
                });
                return;
            }

            // 2. ตรวจสอบรหัสผ่านว่าตรงกันหรือไม่ (ป้องกันการเจาะระบบผ่าน HTML)
            if (password && confirmPassword && password.value !== confirmPassword.value) {
                Swal.fire({
                    icon: 'error',
                    title: 'รหัสผ่านไม่ตรงกัน',
                    text: 'กรุณายืนยันรหัสผ่านทั้งสองช่องให้เหมือนกันครับ',
                    confirmButtonColor: '#2563eb'
                });
                return;
            }

            // 3. ทำเอฟเฟกต์ปุ่ม Loading
            const submitBtn = document.getElementById('submitBtn');
            const originalBtnText = submitBtn.innerHTML;
            submitBtn.innerHTML = `<i class="fa-solid fa-circle-notch fa-spin mr-2"></i> กำลังส่งข้อมูล...`;
            submitBtn.disabled = true;
            submitBtn.classList.add('opacity-75', 'cursor-not-allowed');

            // 4. ดึงข้อมูลจากฟอร์ม
            const formData = new FormData(this);

            // 5. ส่งข้อมูลไปที่ Backend (API)
            fetch('../api/auth/register.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    // คืนสถานะปุ่มกลับมาเหมือนเดิม
                    submitBtn.innerHTML = originalBtnText;
                    submitBtn.disabled = false;
                    submitBtn.classList.remove('opacity-75', 'cursor-not-allowed');

                    if (data.status === 'success') {
                        // สมัครสำเร็จ! แสดงแจ้งเตือน
                        Swal.fire({
                            icon: 'success',
                            title: 'ลงทะเบียนสำเร็จ!',
                            text: data.message,
                            confirmButtonColor: '#2563eb',
                            confirmButtonText: 'ไปยืนยัน OTP',
                            allowOutsideClick: false // บังคับให้กดปุ่มเพื่อไปต่อ
                        }).then((result) => {
                            if (result.isConfirmed) {
                                // สั่งให้เคลียร์ฟอร์ม
                                registerForm.reset();

                                // 🚀 ไฮไลท์สำคัญ: พาส่งไปหน้า OTP พร้อมแนบอีเมลไปใน URL
                                window.location.href = 'verify_otp.php?email=' + encodeURIComponent(email);
                            }
                        });
                    } else {
                        // กรณี Error จากฐานข้อมูล เช่น อีเมลซ้ำ รหัสซ้ำ
                        Swal.fire({
                            icon: 'error',
                            title: 'ไม่สามารถสมัครได้',
                            text: data.message,
                            confirmButtonColor: '#ef4444' // สีแดง
                        });
                    }
                })
                .catch(error => {
                    // กรณีเน็ตหลุด หรือไฟล์ PHP มีปัญหา
                    submitBtn.innerHTML = originalBtnText;
                    submitBtn.disabled = false;
                    submitBtn.classList.remove('opacity-75', 'cursor-not-allowed');

                    Swal.fire({
                        icon: 'error',
                        title: 'ระบบขัดข้อง',
                        text: 'ไม่สามารถเชื่อมต่อเซิร์ฟเวอร์ได้ โปรดลองอีกครั้ง',
                        confirmButtonColor: '#ef4444'
                    });
                });
        });
    }
});
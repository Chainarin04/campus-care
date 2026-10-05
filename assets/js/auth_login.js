document.addEventListener('DOMContentLoaded', function() {
    const loginForm = document.getElementById('loginForm');

    if (loginForm) {
        loginForm.addEventListener('submit', function(e) {
            e.preventDefault(); // บล็อกไม่ให้เว็บรีเฟรชตัวเอง

            const submitBtn = document.getElementById('loginBtn');
            const originalBtnText = submitBtn.innerHTML;

            // เปลี่ยนปุ่มเป็นสถานะโหลด
            submitBtn.innerHTML = `<i class="fa-solid fa-circle-notch fa-spin mr-2"></i> กำลังตรวจสอบ...`;
            submitBtn.disabled = true;

            const formData = new FormData(this);

            // ส่งข้อมูลไปหลังบ้าน
            fetch('../api/auth/login.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    submitBtn.innerHTML = originalBtnText;
                    submitBtn.disabled = false;

                    if (data.status === 'success') {
                        // ถ้ารหัสถูก ให้เด้งเตือนแล้วพาไปหน้า index.php
                        Swal.fire({
                            icon: 'success',
                            title: 'สำเร็จ!',
                            text: data.message,
                            timer: 1500,
                            showConfirmButton: false
                        }).then(() => {
                            window.location.href = 'index.php'; // พาไปหน้าฟีดหลัก
                        });
                    } else {
                        // ถ้ารหัสผิด ให้แสดงแจ้งเตือน
                        Swal.fire({
                            icon: 'error',
                            title: 'เข้าสู่ระบบล้มเหลว',
                            text: data.message,
                            confirmButtonColor: '#2563eb'
                        });
                    }
                })
                .catch(error => {
                    submitBtn.innerHTML = originalBtnText;
                    submitBtn.disabled = false;
                    Swal.fire({ icon: 'error', title: 'ระบบขัดข้อง', text: 'ไม่สามารถเชื่อมต่อเซิร์ฟเวอร์ได้' });
                });
        });
    }
});
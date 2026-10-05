<?php
// ไฟล์: api/auth/resend_otp.php
require_once '../../config/db.php';
header('Content-Type: application/json; charset=utf-8');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);

    try {
        // 1. เช็คว่ามีผู้ใช้นี้ไหม
        $stmt = $pdo->prepare("SELECT id, is_verified FROM users WHERE university_email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if (!$user) {
            echo json_encode(["status" => "error", "message" => "ไม่พบข้อมูลผู้ใช้งานนี้"]);
            exit;
        }

        // 2. ถ้ายืนยันแล้วไม่ต้องส่งใหม่
        if ($user['is_verified'] == 1) {
            echo json_encode(["status" => "info", "message" => "บัญชีนี้ได้รับการยืนยันไปแล้ว เข้าสู่ระบบได้เลย"]);
            exit;
        }

        // 3. สร้างรหัสใหม่ และบวกเวลาไปอีก 10 นาที
        $new_otp_code = rand(100000, 999999);
        $new_expires_at = date("Y-m-d H:i:s", strtotime('+10 minutes'));

        // 4. อัปเดตลงฐานข้อมูล
        $update_stmt = $pdo->prepare("UPDATE users SET otp_code = ?, otp_expires_at = ? WHERE university_email = ?");
        $update_stmt->execute([$new_otp_code, $new_expires_at, $email]);

        // 5. ส่งข้อความกลับไปหาหน้าจอ (แนบรหัสใหม่ให้เห็นด้วยเพื่อการทดสอบ)
        echo json_encode([
            "status" => "success", 
            "message" => "ส่งรหัส OTP ใหม่เรียบร้อยแล้ว!<br><br><span class='text-2xl font-bold text-blue-600 tracking-widest'>$new_otp_code</span>"
        ]);

    } catch (PDOException $e) {
        echo json_encode(["status" => "error", "message" => "เกิดข้อผิดพลาด: " . $e->getMessage()]);
    }
}
?>
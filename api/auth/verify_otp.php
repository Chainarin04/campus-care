<?php
// ไฟล์: api/auth/verify_otp.php
require_once '../../config/db.php';
header('Content-Type: application/json; charset=utf-8');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $otp_code = trim($_POST['otp_code']);

    try {
        // ดึงข้อมูลผู้ใช้จากอีเมล
        $stmt = $pdo->prepare("SELECT id, otp_code, otp_expires_at, is_verified FROM users WHERE university_email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if (!$user) {
            echo json_encode(["status" => "error", "message" => "ไม่พบข้อมูลผู้ใช้งานนี้"]);
            exit;
        }

        if ($user['is_verified'] == 1) {
            echo json_encode(["status" => "info", "message" => "บัญชีนี้ได้รับการยืนยันไปแล้ว"]);
            exit;
        }

        // ตรวจสอบว่า OTP ตรงกันไหม และหมดเวลาหรือยัง
        $current_time = date("Y-m-d H:i:s");
        
        if ($user['otp_code'] !== $otp_code) {
            echo json_encode(["status" => "error", "message" => "รหัส OTP ไม่ถูกต้อง"]);
        } else if ($current_time > $user['otp_expires_at']) {
            echo json_encode(["status" => "error", "message" => "รหัส OTP หมดอายุแล้ว กรุณาขอรหัสใหม่"]);
        } else {
            // อัปเดตสถานะเป็น ยืนยันแล้ว (1) และเคลียร์รหัส OTP ทิ้ง
            $update_stmt = $pdo->prepare("UPDATE users SET is_verified = 1, otp_code = NULL, otp_expires_at = NULL WHERE university_email = ?");
            $update_stmt->execute([$email]);

            echo json_encode(["status" => "success", "message" => "ยืนยันอีเมลสำเร็จ! คุณสามารถเข้าสู่ระบบได้แล้ว"]);
        }

    } catch (PDOException $e) {
        echo json_encode(["status" => "error", "message" => "เกิดข้อผิดพลาด: " . $e->getMessage()]);
    }
}
?>
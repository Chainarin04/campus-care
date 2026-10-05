<?php
require_once '../../config/db.php';

header('Content-Type: application/json; charset=utf-8');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // รับค่าจากฟอร์ม
    $student_id = trim($_POST['student_id']);
    $full_name = trim($_POST['full_name']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    // ตรวจสอบโดเมนอีเมล
    $allowed_domain = "@webmail.npru.ac.th";
    if (!str_ends_with(strtolower($email), $allowed_domain)) {
        echo json_encode(["status" => "error", "message" => "ระบบจำกัดเฉพาะอีเมลมหาวิทยาลัยเท่านั้น (@webmail.npru.ac.th)"]);
        exit;
    }

    // สร้าง OTP 6 หลัก และกำหนดเวลาหมดอายุ (10 นาที)
    $otp_code = rand(100000, 999999);
    $otp_expires_at = date("Y-m-d H:i:s", strtotime('+10 minutes'));
    
    // เข้ารหัสผ่าน
    $password_hash = password_hash($password, PASSWORD_DEFAULT);

    try {
        // บันทึกข้อมูลลงฐานข้อมูล (is_verified จะเป็น 0 อัตโนมัติ)
        $stmt = $pdo->prepare("INSERT INTO users (student_id, full_name, university_email, password_hash, otp_code, otp_expires_at) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([$student_id, $full_name, $email, $password_hash, $otp_code, $otp_expires_at]);
        
        echo json_encode([
            "status" => "success", 
            "message" => "สมัครสมาชิกสำเร็จ! รหัส OTP จำลองของคุณคือ: $otp_code"
        ]);
        
    } catch (PDOException $e) {
        // ดัก Error กรณีรหัสนักศึกษาหรืออีเมลซ้ำ (Code 23000)
        if ($e->getCode() == 23000) {
            echo json_encode(["status" => "error", "message" => "รหัสนักศึกษาหรืออีเมลนี้มีในระบบแล้ว"]);
        } else {
            echo json_encode(["status" => "error", "message" => "เกิดข้อผิดพลาด: " . $e->getMessage()]);
        }
    }
} else {
    echo json_encode(["status" => "error", "message" => "Invalid Request Method"]);
}
?>
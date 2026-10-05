<?php
// ไฟล์: api/auth/login.php
session_start();
require_once '../../config/db.php';

// ตั้งค่าให้ส่งออกเป็น JSON
header('Content-Type: application/json; charset=utf-8');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // 🚀 ดักจับข้อมูลทั้งแบบฟอร์มปกติ (FormData) และแบบ JSON (เผื่อหน้าเว็บส่งมาเป็น JSON)
    $json_data = json_decode(file_get_contents('php://input'), true);
    
    // 🚀 รองรับทั้งกรณีที่หน้าเว็บตั้งชื่อช่องว่า 'university_email' หรือ 'email'
    $email = trim($_POST['university_email'] ?? $_POST['email'] ?? $json_data['university_email'] ?? $json_data['email'] ?? '');
    $password = $_POST['password'] ?? $json_data['password'] ?? '';

    // ถ้าค่าว่างให้แจ้งเตือน
    if (empty($email) || empty($password)) {
        echo json_encode(["status" => "error", "message" => "กรุณากรอกอีเมลและรหัสผ่าน"]);
        exit;
    }

    try {
        // ค้นหาผู้ใช้จากอีเมล
        $stmt = $pdo->prepare("SELECT * FROM users WHERE university_email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // ตรวจสอบรหัสผ่าน
        if ($user && password_verify($password, $user['password_hash'])) {
            
            // ตั้งค่า Session เมื่อล็อกอินสำเร็จ
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['full_name'] = $user['full_name'];
            $_SESSION['badge_level'] = $user['badge_level'] ?? 'Beginner';
            
            // จำค่า role เพื่อใช้เช็คสิทธิ์แอดมิน
            $_SESSION['role'] = $user['role'] ?? 'student'; 

            // ส่งสถานะ success
            echo json_encode(["status" => "success", "message" => "เข้าสู่ระบบสำเร็จ"]);
            exit;

        } else {
            echo json_encode(["status" => "error", "message" => "อีเมลหรือรหัสผ่านไม่ถูกต้อง"]);
            exit;
        }

    } catch (PDOException $e) {
        echo json_encode(["status" => "error", "message" => "เกิดข้อผิดพลาด: " . $e->getMessage()]);
        exit;
    }
}
?>
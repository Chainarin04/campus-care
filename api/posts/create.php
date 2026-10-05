<?php
// ไฟล์: api/posts/create.php
session_start();
require_once '../../config/db.php';

header('Content-Type: application/json; charset=utf-8');

// ตรวจสอบว่าล็อกอินอยู่หรือไม่
if (!isset($_SESSION['user_id'])) {
    echo json_encode(["status" => "error", "message" => "กรุณาเข้าสู่ระบบก่อนสร้างโพสต์"]);
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_SESSION['user_id'];
    
    // รับค่าจากฟอร์ม
    $category = $_POST['category'] ?? '';
    $title = trim($_POST['title'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $location = trim($_POST['location'] ?? '');
    $note = trim($_POST['note'] ?? ''); // เพิ่มการรับค่าหมายเหตุ
    
    $image_url = null; 

    try {
        // จัดการอัปโหลดรูปภาพ (ถ้ามี)
        if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
            $allowed = ['jpg', 'jpeg', 'png', 'gif'];
            $filename = $_FILES['image']['name'];
            $file_ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
            $filesize = $_FILES['image']['size'];

            if (!in_array($file_ext, $allowed)) {
                echo json_encode(["status" => "error", "message" => "รองรับเฉพาะไฟล์ JPG, PNG, GIF เท่านั้น"]);
                exit;
            }
            
            if ($filesize > 5 * 1024 * 1024) { 
                echo json_encode(["status" => "error", "message" => "ขนาดไฟล์ต้องไม่เกิน 5MB"]);
                exit;
            }

            $new_filename = uniqid('post_') . '.' . $file_ext;
            $upload_dir = '../../uploads/posts/';
            
            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, 0777, true);
            }

            $destination = $upload_dir . $new_filename;
            
            if (move_uploaded_file($_FILES['image']['tmp_name'], $destination)) {
                $image_url = 'uploads/posts/' . $new_filename; 
            } else {
                echo json_encode(["status" => "error", "message" => "เกิดข้อผิดพลาดในการอัปโหลดรูปภาพ"]);
                exit;
            }
        }

        // บันทึกข้อมูลลงฐานข้อมูล (เพิ่มคอลัมน์ note และตัวแปร $note เข้าไป)
        $stmt = $pdo->prepare("INSERT INTO posts (user_id, category, title, description, location, note, image_url) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$user_id, $category, $title, $description, $location, $note, $image_url]);

        echo json_encode(["status" => "success", "message" => "สร้างโพสต์เรียบร้อยแล้ว!"]);

    } catch (PDOException $e) {
        echo json_encode(["status" => "error", "message" => "เกิดข้อผิดพลาดฐานข้อมูล: " . $e->getMessage()]);
    }
}
?>
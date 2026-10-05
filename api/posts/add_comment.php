<?php
// ไฟล์: api/posts/add_comment.php
session_start();
require_once '../../config/db.php';

header('Content-Type: application/json; charset=utf-8');

// ตรวจสอบว่าล็อกอินอยู่หรือไม่
if (!isset($_SESSION['user_id'])) {
    echo json_encode(["status" => "error", "message" => "กรุณาเข้าสู่ระบบก่อนคอมเมนต์"]);
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_SESSION['user_id'];
    $post_id = $_POST['post_id'] ?? 0;
    $comment_text = trim($_POST['comment_text'] ?? '');

    if (empty($comment_text)) {
        echo json_encode(["status" => "error", "message" => "กรุณาพิมพ์ข้อความก่อนส่ง"]);
        exit;
    }

    try {
        // บันทึกคอมเมนต์ลงฐานข้อมูล
        $stmt = $pdo->prepare("INSERT INTO comments (post_id, user_id, comment_text) VALUES (?, ?, ?)");
        $stmt->execute([$post_id, $user_id, $comment_text]);

        echo json_encode(["status" => "success", "message" => "ส่งคอมเมนต์เรียบร้อยแล้ว!"]);
    } catch (PDOException $e) {
        echo json_encode(["status" => "error", "message" => "เกิดข้อผิดพลาด: " . $e->getMessage()]);
    }
}
?>
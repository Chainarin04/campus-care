<?php
// ไฟล์: api/posts/delete_my_post.php
session_start();
require_once '../../config/db.php';

header('Content-Type: application/json; charset=utf-8');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(["status" => "error", "message" => "กรุณาเข้าสู่ระบบก่อน"]);
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $post_id = $_POST['post_id'] ?? 0;
    $user_id = $_SESSION['user_id'];

    try {
        // ลบโพสต์เฉพาะที่ id ตรงกัน และ user_id ต้องเป็นของคนที่เข้าสู่ระบบอยู่เท่านั้น
        $stmt = $pdo->prepare("DELETE FROM posts WHERE id = ? AND user_id = ?");
        $stmt->execute([$post_id, $user_id]);

        if ($stmt->rowCount() > 0) {
            echo json_encode(["status" => "success", "message" => "ลบโพสต์ของคุณเรียบร้อยแล้ว"]);
        } else {
            echo json_encode(["status" => "error", "message" => "ไม่สามารถลบได้ (คุณอาจไม่ใช่เจ้าของโพสต์)"]);
        }
    } catch (PDOException $e) {
        echo json_encode(["status" => "error", "message" => "เกิดข้อผิดพลาด: " . $e->getMessage()]);
    }
}
?>
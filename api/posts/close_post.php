<?php
// ไฟล์: api/posts/close_post.php
session_start();
require_once '../../config/db.php';

header('Content-Type: application/json; charset=utf-8');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(["status" => "error", "message" => "กรุณาเข้าสู่ระบบก่อน"]);
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $post_id = $_POST['post_id'] ?? 0;
    $user_id = $_SESSION['user_id']; // ไอดีของคนที่ล็อกอินอยู่

    try {
        // อัปเดตสถานะเป็น resolved (และต้องแน่ใจว่า user_id ตรงกับเจ้าของโพสต์)
        $stmt = $pdo->prepare("UPDATE posts SET status = 'resolved' WHERE id = ? AND user_id = ?");
        $stmt->execute([$post_id, $user_id]);

        // ตรวจสอบว่ามีการอัปเดตข้อมูลจริงๆ ไหม (ป้องกันคนเนียนแก้โพสต์คนอื่น)
        if ($stmt->rowCount() > 0) {
            echo json_encode(["status" => "success", "message" => "อัปเดตสถานะโพสต์เรียบร้อยแล้ว!"]);
        } else {
            echo json_encode(["status" => "error", "message" => "ไม่สามารถทำรายการได้ (คุณอาจไม่ใช่เจ้าของโพสต์)"]);
        }

    } catch (PDOException $e) {
        echo json_encode(["status" => "error", "message" => "เกิดข้อผิดพลาดฐานข้อมูล: " . $e->getMessage()]);
    }
}
?>
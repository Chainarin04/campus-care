<?php
// ไฟล์: api/posts/report_post.php
session_start();
require_once '../../config/db.php';

header('Content-Type: application/json; charset=utf-8');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(["status" => "error", "message" => "กรุณาเข้าสู่ระบบก่อน"]);
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $post_id = $_POST['post_id'] ?? 0;
    $reason = trim($_POST['reason'] ?? '');
    $reporter_id = $_SESSION['user_id'];

    if (empty($reason)) {
        echo json_encode(["status" => "error", "message" => "กรุณาระบุเหตุผลที่รายงาน"]);
        exit;
    }

    try {
        // เช็คก่อนว่าคนนี้เคยกดรีพอร์ตโพสต์นี้ไปแล้วหรือยัง (ป้องกันสแปมกดซ้ำ)
        $stmt_check = $pdo->prepare("SELECT id FROM reports WHERE post_id = ? AND reporter_id = ?");
        $stmt_check->execute([$post_id, $reporter_id]);
        if ($stmt_check->rowCount() > 0) {
            echo json_encode(["status" => "error", "message" => "คุณได้รายงานโพสต์นี้ไปแล้ว ระบบกำลังตรวจสอบครับ"]);
            exit;
        }

        // บันทึกการรีพอร์ต
        $stmt = $pdo->prepare("INSERT INTO reports (post_id, reporter_id, reason) VALUES (?, ?, ?)");
        $stmt->execute([$post_id, $reporter_id, $reason]);

        echo json_encode(["status" => "success", "message" => "รายงานโพสต์สำเร็จ แอดมินจะรีบตรวจสอบให้เร็วที่สุด!"]);

    } catch (PDOException $e) {
        echo json_encode(["status" => "error", "message" => "เกิดข้อผิดพลาดฐานข้อมูล: " . $e->getMessage()]);
    }
}
?>
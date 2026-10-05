<?php
// ไฟล์: api/posts/get_posts.php
session_start();
require_once '../../config/db.php';

header('Content-Type: application/json; charset=utf-8');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(["status" => "error", "message" => "Unauthorized"]);
    exit;
}

$category = isset($_GET['category']) ? $_GET['category'] : 'all';

try {
    // 🚀 ระบบ Auto-Close: ปิดโพสต์ที่เปิดค้างไว้เกิน 14 วันโดยอัตโนมัติ
    $stmt_expire = $pdo->prepare("UPDATE posts SET status = 'resolved' WHERE status = 'open' AND created_at < DATE_SUB(NOW(), INTERVAL 14 DAY)");
    $stmt_expire->execute();

    // เตรียมคำสั่ง SQL สำหรับดึงข้อมูลมาแสดงหน้าฟีด
    $sql = "SELECT p.*, u.full_name, u.badge_level 
            FROM posts p 
            JOIN users u ON p.user_id = u.id ";
    
    $params = [];

    if ($category !== 'all') {
        $sql .= "WHERE p.category = ? ";
        $params[] = $category;
    }

    // เรียงให้โพสต์ที่ 'open' ขึ้นก่อน 'resolved' และเรียงตามเวลาล่าสุด
    $sql .= "ORDER BY FIELD(p.status, 'open', 'resolved'), p.created_at DESC";

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $posts = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode(["status" => "success", "data" => $posts]);

} catch (PDOException $e) {
    echo json_encode(["status" => "error", "message" => "เกิดข้อผิดพลาด: " . $e->getMessage()]);
}
?>
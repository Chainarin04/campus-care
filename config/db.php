<?php
$host = 'localhost';
$dbname = 'campus_care';
$username = 'root'; // หากใช้ XAMPP ปกติจะเป็น root
$password = '';     // หากใช้ XAMPP ปกติจะปล่อยว่าง

$dsn = "mysql:host=$host;dbname=$dbname;charset=utf8mb4";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $username, $password, $options);
} catch (PDOException $e) {
    // ส่ง Error กลับเป็น JSON เพื่อให้ Frontend นำไปแสดงผล
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode(["status" => "error", "message" => "Database connection failed"]);
    exit;
}
?>
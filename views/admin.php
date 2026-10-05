<?php
// ไฟล์: views/admin.php
session_start();
require_once '../config/db.php';

// ดึงค่า role และแปลงเป็นตัวพิมพ์เล็กเพื่อป้องกันปัญหา Case Sensitive
$user_role = strtolower($_SESSION['role'] ?? 'student');

// ป้องกันคนที่ไม่ใช่แอดมินแอบเข้าหน้านี้
if (!isset($_SESSION['user_id']) || $user_role !== 'admin') {
    die("<script>alert('คุณไม่มีสิทธิ์เข้าถึงหน้านี้'); window.location.href='index.php';</script>");
}

try {
    // ดึงข้อมูลการรายงานที่รอการตรวจสอบ (pending)
    $sql = "SELECT r.id as report_id, r.reason, r.created_at as reported_at, 
                   p.id as post_id, p.title, p.description, 
                   reporter.full_name as reporter_name, author.full_name as author_name
            FROM reports r
            JOIN posts p ON r.post_id = p.id
            JOIN users reporter ON r.reporter_id = reporter.id
            JOIN users author ON p.user_id = author.id
            WHERE r.status = 'pending'
            ORDER BY r.created_at ASC";
    
    $stmt = $pdo->query($sql);
    $reports = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard | Campus Care</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Prompt:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style> body { font-family: 'Prompt', sans-serif; background-color: #f8fafc; } </style>
</head>
<body class="text-slate-800">

    <nav class="bg-gray-900 text-white sticky top-0 z-40 w-full px-4 py-3 shadow-md">
        <div class="max-w-7xl mx-auto flex justify-between items-center">
            <div class="flex items-center gap-3">
                <i class="fa-solid fa-shield-halved text-2xl text-red-500"></i>
                <span class="font-bold text-xl">Admin Dashboard</span>
            </div>
            <a href="index.php" class="text-gray-300 hover:text-white transition-colors bg-gray-800 px-4 py-1.5 rounded-lg text-sm">
                กลับหน้าฟีดหลัก
            </a>
        </div>
    </nav>

    <main class="max-w-7xl mx-auto px-4 py-8">
        <h2 class="text-2xl font-bold mb-6 text-gray-800"><i class="fa-solid fa-flag text-red-500 mr-2"></i> รายการโพสต์ที่ถูกรายงาน (<?php echo count($reports); ?>)</h2>

        <?php if (count($reports) == 0): ?>
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-10 text-center text-gray-500">
                <i class="fa-solid fa-face-smile-beam text-5xl mb-4 text-green-400"></i>
                <h3 class="text-xl font-medium">ไม่มีรายการแจ้งเตือน</h3>
                <p>ชุมชนของเราเรียบร้อยดีในขณะนี้</p>
            </div>
        <?php else: ?>
            <div class="grid grid-cols-1 gap-4">
                <?php foreach ($reports as $r): ?>
                <div class="bg-white rounded-xl shadow-sm border border-red-200 p-5 flex flex-col md:flex-row gap-4 justify-between items-start md:items-center">
                    
                    <div class="flex-grow">
                        <div class="flex items-center gap-2 mb-2">
                            <span class="bg-red-100 text-red-700 px-2 py-0.5 rounded text-xs font-bold border border-red-200">
                                เหตุผล: <?php echo htmlspecialchars($r['reason']); ?>
                            </span>
                            <span class="text-xs text-gray-400"><i class="fa-regular fa-clock"></i> <?php echo date('d/m/Y H:i', strtotime($r['reported_at'])); ?></span>
                        </div>
                        <h3 class="font-bold text-lg text-gray-900"><a href="post_detail.php?id=<?php echo $r['post_id']; ?>" target="_blank" class="hover:text-blue-600 underline decoration-gray-300 underline-offset-2"><?php echo htmlspecialchars($r['title']); ?></a></h3>
                        <p class="text-sm text-gray-500 mt-1 line-clamp-1">"<?php echo htmlspecialchars($r['description']); ?>"</p>
                        <p class="text-xs text-gray-400 mt-2">ผู้โพสต์: <strong><?php echo htmlspecialchars($r['author_name']); ?></strong> | ผู้รายงาน: <strong><?php echo htmlspecialchars($r['reporter_name']); ?></strong></p>
                    </div>

                    <div class="flex gap-2 w-full md:w-auto shrink-0">
                        <button onclick="dismissReport(<?php echo $r['report_id']; ?>)" class="flex-1 md:flex-none px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg text-sm font-medium transition-colors">
                            <i class="fa-solid fa-eye-slash mr-1"></i> เพิกเฉย
                        </button>
                        <button onclick="deletePost(<?php echo $r['post_id']; ?>)" class="flex-1 md:flex-none px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg text-sm font-medium shadow-sm transition-colors">
                            <i class="fa-solid fa-trash mr-1"></i> ลบโพสต์
                        </button>
                    </div>

                </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </main>

    <script>
        function dismissReport(reportId) {
            Swal.fire({ title: 'เพิกเฉยรายงานนี้?', text: 'หากตรวจสอบแล้วไม่พบความผิดปกติ', icon: 'warning', showCancelButton: true, confirmButtonText: 'ใช่, เพิกเฉย' })
            .then((result) => {
                if (result.isConfirmed) {
                    const fd = new FormData(); fd.append('report_id', reportId);
                    fetch('../api/admin/dismiss_report.php', { method: 'POST', body: fd })
                    .then(res => res.json())
                    .then(data => { if(data.status==='success') location.reload(); else alert(data.message); });
                }
            });
        }

        function deletePost(postId) {
            Swal.fire({ title: 'ลบโพสต์นี้ถาวร?', text: 'เนื้อหาและคอมเมนต์ทั้งหมดจะถูกลบ ไม่สามารถกู้คืนได้!', icon: 'error', showCancelButton: true, confirmButtonColor: '#dc2626', confirmButtonText: 'ใช่, ลบทิ้งเลย' })
            .then((result) => {
                if (result.isConfirmed) {
                    const fd = new FormData(); fd.append('post_id', postId);
                    fetch('../api/admin/delete_post.php', { method: 'POST', body: fd })
                    .then(res => res.json())
                    .then(data => { if(data.status==='success') location.reload(); else alert(data.message); });
                }
            });
        }
    </script>
</body>
</html>
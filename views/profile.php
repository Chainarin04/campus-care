<?php
// ไฟล์: views/profile.php
session_start();
require_once '../config/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

try {
    // 1. ดึงข้อมูลส่วนตัวของผู้ใช้
    $stmt_user = $pdo->prepare("SELECT * FROM users WHERE id = ?");
    $stmt_user->execute([$user_id]);
    $user = $stmt_user->fetch(PDO::FETCH_ASSOC);

    // 2. ดึงประวัติการโพสต์ของตัวเองทั้งหมด
    $stmt_posts = $pdo->prepare("SELECT * FROM posts WHERE user_id = ? ORDER BY created_at DESC");
    $stmt_posts->execute([$user_id]);
    $my_posts = $stmt_posts->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    die("เกิดข้อผิดพลาด: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>โปรไฟล์ของฉัน | Campus Care</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Prompt:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style> body { font-family: 'Prompt', sans-serif; background-color: #f8fafc; } </style>
</head>
<body class="text-slate-800 pb-20 md:pb-0">

    <!-- Navbar -->
    <nav class="bg-white sticky top-0 z-40 w-full px-4 py-3 shadow-sm border-b border-gray-100">
        <div class="max-w-3xl mx-auto flex justify-between items-center">
            <div class="flex items-center">
                <a href="index.php" class="text-gray-500 hover:text-blue-600 transition-colors mr-4 text-xl">
                    <i class="fa-solid fa-arrow-left"></i>
                </a>
                <span class="font-bold text-lg text-blue-900">โปรไฟล์ส่วนตัว</span>
            </div>
            <a href="../api/auth/logout.php" class="text-red-500 hover:bg-red-50 px-3 py-1.5 rounded-lg text-sm font-medium transition-colors">
                <i class="fa-solid fa-right-from-bracket mr-1"></i> ออกจากระบบ
            </a>
        </div>
    </nav>

    <main class="max-w-3xl mx-auto px-4 py-6">
        
        <!-- ส่วนแสดงข้อมูลส่วนตัว -->
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 mb-6 flex flex-col sm:flex-row items-center sm:items-start gap-6 text-center sm:text-left">
            <div class="w-24 h-24 rounded-full bg-blue-100 border-4 border-white shadow-lg overflow-hidden shrink-0">
                <img src="https://ui-avatars.com/api/?name=<?php echo urlencode($user['full_name']); ?>&background=eff6ff&color=2563eb&size=128" alt="Profile" class="w-full h-full object-cover">
            </div>
            <div class="flex-grow">
                <h1 class="text-2xl font-bold text-gray-900 mb-1"><?php echo htmlspecialchars($user['full_name']); ?></h1>
                <p class="text-gray-500 text-sm mb-3"><i class="fa-regular fa-envelope mr-1"></i> <?php echo htmlspecialchars($user['university_email']); ?></p>
                
                <div class="flex flex-wrap justify-center sm:justify-start gap-3">
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-blue-50 text-blue-700 border border-blue-200">
                        <i class="fa-solid fa-medal mr-1.5 text-yellow-500"></i> ระดับ: <?php echo htmlspecialchars($user['badge_level'] ?? 'Beginner'); ?>
                    </span>
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-green-50 text-green-700 border border-green-200">
                        <i class="fa-solid fa-star mr-1.5 text-green-500"></i> ฮีโร่พอยต์: <?php echo htmlspecialchars($user['hero_points'] ?? 0); ?> แต้ม
                    </span>
                </div>
            </div>
        </div>

        <!-- ส่วนประวัติการโพสต์ -->
        <h2 class="text-lg font-bold text-gray-800 mb-4"><i class="fa-solid fa-clock-rotate-left text-blue-500 mr-2"></i> ประวัติการโพสต์ของฉัน (<?php echo count($my_posts); ?>)</h2>
        
        <div class="space-y-4">
            <?php if (count($my_posts) == 0): ?>
                <div class="text-center py-10 bg-white rounded-2xl border border-gray-100 border-dashed text-gray-400">
                    <i class="fa-regular fa-folder-open text-5xl mb-3 text-gray-300"></i>
                    <p>คุณยังไม่เคยสร้างโพสต์ใดๆ</p>
                </div>
            <?php else: ?>
                <?php foreach ($my_posts as $post): ?>
                <div class="bg-white rounded-xl p-4 shadow-sm border border-gray-100 flex flex-col sm:flex-row gap-4 relative">
                    
                    <?php if ($post['status'] == 'resolved'): ?>
                        <div class="absolute top-3 right-3 text-green-500 text-xs font-bold"><i class="fa-solid fa-check-circle"></i> ปิดโพสต์แล้ว</div>
                    <?php endif; ?>

                    <div class="flex-grow <?php echo ($post['status'] == 'resolved') ? 'opacity-60 grayscale' : ''; ?>">
                        <h3 class="font-bold text-gray-900 mb-1 line-clamp-1 pr-20"><?php echo htmlspecialchars($post['title']); ?></h3>
                        <p class="text-xs text-gray-500 mb-2"><i class="fa-regular fa-clock"></i> <?php echo date('d/m/Y H:i', strtotime($post['created_at'])); ?> &bull; <i class="fa-solid fa-location-dot"></i> <?php echo htmlspecialchars($post['location']); ?></p>
                        
                        <div class="flex gap-2 mt-3">
                            <a href="post_detail.php?id=<?php echo $post['id']; ?>" class="text-xs bg-blue-50 text-blue-600 hover:bg-blue-100 px-3 py-1.5 rounded-lg font-medium transition-colors">
                                ดูรายละเอียด
                            </a>
                        </div>
                    </div>
                    
                    <!-- ปุ่มลบโพสต์ (อยู่ขวาสุด) -->
                    <div class="flex items-center justify-end sm:border-l sm:border-gray-100 sm:pl-4">
                        <button onclick="deleteMyPost(<?php echo $post['id']; ?>)" class="text-gray-400 hover:text-red-500 hover:bg-red-50 w-10 h-10 rounded-full flex items-center justify-center transition-colors focus:outline-none" title="ลบโพสต์นี้">
                            <i class="fa-solid fa-trash"></i>
                        </button>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

    </main>

    <script>
        function deleteMyPost(postId) {
            Swal.fire({
                title: 'ยืนยันการลบโพสต์?',
                text: "หากลบแล้วจะไม่สามารถกู้คืนได้ (คอมเมนต์ต่างๆ จะหายไปด้วย)",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc2626',
                cancelButtonColor: '#d1d5db',
                confirmButtonText: '<i class="fa-solid fa-trash"></i> ใช่, ลบเลย',
                cancelButtonText: 'ยกเลิก',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    const formData = new FormData();
                    formData.append('post_id', postId);

                    fetch('../api/posts/delete_my_post.php', {
                        method: 'POST',
                        body: formData
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.status === 'success') {
                            Swal.fire({ icon: 'success', title: 'ลบสำเร็จ', text: data.message, confirmButtonColor: '#2563eb' })
                            .then(() => { window.location.reload(); });
                        } else {
                            Swal.fire({ icon: 'error', title: 'ผิดพลาด', text: data.message });
                        }
                    })
                    .catch(error => {
                        Swal.fire({ icon: 'error', title: 'ระบบขัดข้อง', text: 'ไม่สามารถเชื่อมต่อเซิร์ฟเวอร์ได้' });
                    });
                }
            });
        }
    </script>
</body>
</html>
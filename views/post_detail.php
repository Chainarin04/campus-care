<?php
// ไฟล์: views/post_detail.php
session_start();
require_once '../config/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_name =$_SESSION['full_name'];
$post_id =$_GET['id'] ?? 0;

try {
    // ดึงข้อมูลโพสต์และผู้โพสต์
    $stmt =$pdo->prepare("SELECT p.*, u.full_name FROM posts p JOIN users u ON p.user_id = u.id WHERE p.id = ?");
    $stmt->execute([$post_id]);
    $post =$stmt->fetch(PDO::FETCH_ASSOC);

    if (!$post) {
        die("ไม่พบข้อมูลโพสต์นี้ หรือโพสต์ถูกลบไปแล้ว");
    }

    // ดึงคอมเมนต์
    $stmt_comments =$pdo->prepare("SELECT c.*, u.full_name FROM comments c JOIN users u ON c.user_id = u.id WHERE c.post_id = ? ORDER BY c.created_at ASC");
    $stmt_comments->execute([$post_id]);
    $comments =$stmt_comments->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    die("เกิดข้อผิดพลาด: " . $e->getMessage());
}

$cat_bg = 'bg-gray-100';$cat_text = 'text-gray-800'; $cat_icon = 'fa-tag';$cat_name = 'ทั่วไป';
if ($post['category'] == 'emergency') { $cat_bg = 'bg-red-100';$cat_text = 'text-red-800'; $cat_icon = 'fa-car-burst';$cat_name = 'ฉุกเฉิน'; }
elseif ($post['category'] == 'lost') { $cat_bg = 'bg-orange-100';$cat_text = 'text-orange-800'; $cat_icon = 'fa-magnifying-glass';$cat_name = 'ของหาย'; }
elseif ($post['category'] == 'found') { $cat_bg = 'bg-green-100';$cat_text = 'text-green-800'; $cat_icon = 'fa-box-open';$cat_name = 'เก็บของได้'; }
elseif ($post['category'] == 'general') { $cat_bg = 'bg-blue-100';$cat_text = 'text-blue-800'; $cat_icon = 'fa-handshake-angle';$cat_name = 'ช่วยเหลือ'; }
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($post['title']); ?> | Campus Care</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Prompt:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style> body { font-family: 'Prompt', sans-serif; background-color: #f8fafc; } </style>
</head>
<body class="text-slate-800 pb-10">

    <nav class="bg-white sticky top-0 z-40 w-full px-4 py-3 shadow-sm border-b border-gray-100">
        <div class="max-w-3xl mx-auto flex items-center">
            <a href="index.php" class="text-gray-500 hover:text-blue-600 transition-colors mr-4 text-xl">
                <i class="fa-solid fa-arrow-left"></i>
            </a>
            <span class="font-bold text-lg text-blue-900 truncate">รายละเอียดโพสต์</span>
        </div>
    </nav>

    <main class="max-w-3xl mx-auto px-4 py-6">
        
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden mb-6 relative">
            <?php if ($post['status'] == 'resolved'): ?>
                <div class="absolute top-4 right-4 z-10 bg-green-500 text-white px-4 py-1.5 rounded-full text-sm font-bold shadow-lg">
                    <i class="fa-solid fa-check-circle mr-1"></i> ปิดโพสต์แล้ว
                </div>
            <?php endif; ?>

            <?php if (!empty($post['image_url'])): ?>
            <div class="w-full bg-gray-100 flex justify-center border-b border-gray-100 relative">
                <img src="../<?php echo htmlspecialchars($post['image_url']); ?>" class="max-h-96 object-contain <?php echo ($post['status'] == 'resolved') ? 'opacity-60 grayscale' : ''; ?>" alt="Post Image">
            </div>
            <?php endif; ?>
            
            <div class="p-6">
                <!-- ส่วนหมวดหมู่ เวลา และปุ่มรายงาน -->
                <div class="flex justify-between items-start mb-4">
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium <?php echo $cat_bg . ' ' .$cat_text; ?>">
                        <i class="fa-solid <?php echo $cat_icon; ?> mr-1.5"></i> <?php echo $cat_name; ?>
                    </span>
                    
                    <div class="flex items-center gap-3">
                        <span class="text-xs text-gray-400 font-medium"><i class="fa-regular fa-clock mr-1"></i> <?php echo date('d/m/Y H:i', strtotime($post['created_at'])); ?></span>
                        
                        <!-- 🚀 ปุ่มรายงานโพสต์ (ซ่อนถ้าเป็นโพสต์ของตัวเอง) -->
                        <?php if ($post['user_id'] !=$_SESSION['user_id']): ?>
                        <button onclick="reportPost(<?php echo $post['id']; ?>)" class="text-gray-300 hover:text-red-500 transition-colors focus:outline-none" title="รายงานโพสต์นี้">
                            <i class="fa-solid fa-flag"></i>
                        </button>
                        <?php endif; ?>
                    </div>
                </div>
                
                <h1 class="text-2xl font-bold text-gray-900 mb-4 <?php echo ($post['status'] == 'resolved') ? 'text-gray-500 line-through' : ''; ?>"><?php echo htmlspecialchars($post['title']); ?></h1>
                <p class="text-gray-600 mb-6 whitespace-pre-line leading-relaxed"><?php echo htmlspecialchars($post['description']); ?></p>
                
                <div class="bg-gray-50 p-4 rounded-xl space-y-3 mb-6">
                    <div class="flex items-start text-sm">
                        <i class="fa-solid fa-location-dot text-red-500 mt-1 w-5 text-center mr-2"></i>
                        <div>
                            <span class="font-semibold text-gray-700 block">สถานที่</span>
                            <span class="text-gray-600"><?php echo htmlspecialchars($post['location']); ?></span>
                        </div>
                    </div>
                    <?php if (!empty($post['note'])): ?>
                    <div class="flex items-start text-sm">
                        <i class="fa-solid fa-circle-info text-blue-500 mt-1 w-5 text-center mr-2"></i>
                        <div>
                            <span class="font-semibold text-gray-700 block">หมายเหตุเพิ่มเติม</span>
                            <span class="text-gray-600"><?php echo htmlspecialchars($post['note']); ?></span>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>

                <div class="flex flex-wrap items-center justify-between gap-4 pt-4 border-t border-gray-100">
                    <div class="flex items-center gap-3">
                        <img src="https://ui-avatars.com/api/?name=<?php echo urlencode($post['full_name']); ?>&background=random" class="w-10 h-10 rounded-full shadow-sm" alt="User">
                        <div>
                            <p class="text-sm font-semibold text-gray-800"><?php echo htmlspecialchars($post['full_name']); ?></p>
                            <p class="text-xs text-gray-500">ผู้โพสต์</p>
                        </div>
                    </div>
                    
                    <?php if ($post['status'] == 'resolved'): ?>
                        <span class="inline-flex items-center px-4 py-2 rounded-lg text-sm font-bold bg-green-100 text-green-700 border border-green-200">
                            <i class="fa-solid fa-circle-check mr-2"></i> สำเร็จแล้ว
                        </span>
                    <?php elseif ($post['user_id'] ==$_SESSION['user_id']): ?>
                        <button onclick="closePost(<?php echo $post['id']; ?>)" class="inline-flex items-center px-4 py-2 rounded-lg text-sm font-medium bg-gray-800 text-white hover:bg-gray-700 transition-colors shadow-sm focus:outline-none">
                            <i class="fa-solid fa-check-double mr-2"></i> ปิดโพสต์
                        </button>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <h3 class="text-lg font-bold text-gray-800 mb-4"><i class="fa-regular fa-comments mr-2 text-blue-500"></i>ความคิดเห็น (<?php echo count($comments); ?>)</h3>
        <div class="space-y-4 mb-8">
            <?php if (count($comments) == 0): ?>
                <div class="text-center py-8 text-gray-400 bg-white rounded-xl border border-gray-100">
                    <i class="fa-regular fa-comment-dots text-4xl mb-2"></i>
                    <p class="text-sm">ยังไม่มีความคิดเห็น เริ่มต้นให้ความช่วยเหลือเลย!</p>
                </div>
            <?php else: ?>
                <?php foreach ($comments as$c): ?>
                <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100 flex gap-3">
                    <img src="https://ui-avatars.com/api/?name=<?php echo urlencode($c['full_name']); ?>&background=random" class="w-8 h-8 rounded-full shrink-0" alt="User">
                    <div class="flex-grow">
                        <div class="flex justify-between items-baseline mb-1">
                            <span class="text-sm font-semibold text-gray-800"><?php echo htmlspecialchars($c['full_name']); ?></span>
                            <span class="text-[10px] text-gray-400"><?php echo date('d/m/Y H:i', strtotime($c['created_at'])); ?></span>
                        </div>
                        <p class="text-sm text-gray-600 whitespace-pre-line"><?php echo htmlspecialchars($c['comment_text']); ?></p>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <?php if ($post['status'] != 'resolved'): ?>
        <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100 sticky bottom-4 z-10">
            <form id="commentForm" class="flex gap-2">
                <input type="hidden" name="post_id" value="<?php echo $post_id; ?>">
                <textarea name="comment_text" rows="1" class="w-full bg-gray-50 border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 resize-none" placeholder="แสดงความคิดเห็น หรือเสนอความช่วยเหลือ..."></textarea>
                <button type="submit" id="submitCommentBtn" class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-lg font-medium transition-colors shrink-0 flex items-center justify-center">
                    <i class="fa-regular fa-paper-plane"></i>
                </button>
            </form>
        </div>
        <?php else: ?>
        <div class="bg-gray-100 p-4 rounded-xl text-center text-gray-500 text-sm border border-gray-200">
            <i class="fa-solid fa-lock mr-2"></i> โพสต์นี้ถูกปิดแล้ว ไม่สามารถแสดงความคิดเห็นเพิ่มได้
        </div>
        <?php endif; ?>

    </main>

    <script>
        // ระบบส่งคอมเมนต์
        const commentForm = document.getElementById('commentForm');
        if(commentForm) {
            commentForm.addEventListener('submit', function(e) {
                e.preventDefault();
                const submitBtn = document.getElementById('submitCommentBtn');
                const originalIcon = submitBtn.innerHTML;
                submitBtn.innerHTML = `<i class="fa-solid fa-circle-notch fa-spin"></i>`;
                submitBtn.disabled = true;

                const formData = new FormData(this);
                fetch('../api/posts/add_comment.php', { method: 'POST', body: formData })
                .then(response => response.json())
                .then(data => {
                    submitBtn.innerHTML = originalIcon;
                    submitBtn.disabled = false;
                    if (data.status === 'success') window.location.reload();
                    else Swal.fire({ icon: 'error', title: 'ผิดพลาด', text: data.message });
                });
            });
        }

        // ระบบปิดโพสต์
        function closePost(postId) {
            Swal.fire({
                title: 'ยืนยันการปิดโพสต์?',
                text: "หากได้รับความช่วยเหลือ หรือหาของเจอแล้ว กดยืนยันเพื่อปิดโพสต์นี้ได้เลยครับ",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#1f2937',
                cancelButtonColor: '#d1d5db',
                confirmButtonText: 'ใช่, ปิดโพสต์เลย',
                cancelButtonText: 'ยกเลิก',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    const formData = new FormData();
                    formData.append('post_id', postId);
                    fetch('../api/posts/close_post.php', { method: 'POST', body: formData })
                    .then(response => response.json())
                    .then(data => {
                        if (data.status === 'success') {
                            Swal.fire({ icon: 'success', title: 'ปิดโพสต์สำเร็จ!', text: 'ขอบคุณที่อัปเดตสถานะให้เพื่อนๆ ทราบครับ', confirmButtonColor: '#2563eb' })
                            .then(() => { window.location.reload(); });
                        } else {
                            Swal.fire({ icon: 'error', title: 'ผิดพลาด', text: data.message });
                        }
                    });
                }
            });
        }

        // 🚀 ระบบรายงานโพสต์ (Report Post)
        function reportPost(postId) {
            Swal.fire({
                title: 'รายงานโพสต์นี้',
                input: 'select',
                inputOptions: {
                    'เนื้อหาไม่เหมาะสม / หยาบคาย': 'เนื้อหาไม่เหมาะสม / หยาบคาย',
                    'สแปม / โฆษณา': 'สแปม / โฆษณา',
                    'ผิดหมวดหมู่ชัดเจน': 'ผิดหมวดหมู่ชัดเจน',
                    'โพสต์นี้คลี่คลายแล้ว (แต่เจ้าของไม่ยอมปิด)': 'โพสต์นี้คลี่คลายแล้ว (แต่เจ้าของไม่ยอมปิด)',
                    'อื่นๆ': 'อื่นๆ'
                },
                inputPlaceholder: 'เลือกเหตุผล...',
                showCancelButton: true,
                confirmButtonColor: '#dc2626',
                cancelButtonColor: '#9ca3af',
                confirmButtonText: '<i class="fa-solid fa-flag"></i> ยืนยันการรายงาน',
                cancelButtonText: 'ยกเลิก',
                inputValidator: (value) => {
                    return new Promise((resolve) => {
                        if (value !== '') { resolve(); } 
                        else { resolve('กรุณาเลือกเหตุผลก่อนส่งรายงานครับ'); }
                    });
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    const formData = new FormData();
                    formData.append('post_id', postId);
                    formData.append('reason', result.value);

                    fetch('../api/posts/report_post.php', {
                        method: 'POST',
                        body: formData
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.status === 'success') {
                            Swal.fire({ icon: 'success', title: 'รับเรื่องแล้ว', text: data.message, confirmButtonColor: '#2563eb' });
                        } else {
                            Swal.fire({ icon: 'error', title: 'ไม่สามารถรายงานได้', text: data.message });
                        }
                    });
                }
            });
        }
    </script>
</body>
</html>
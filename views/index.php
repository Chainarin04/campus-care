<?php
// ไฟล์: views/index.php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_name =$_SESSION['full_name'];
// ดึงค่า Role จาก Session (ถ้าไม่มีให้เป็น student)
$user_role =$_SESSION['role'] ?? 'student';
$badge_level =$_SESSION['badge_level'] ?? 'Beginner';
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#2563eb">
    <title>หน้าหลัก | Campus Care</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Prompt:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        body { font-family: 'Prompt', sans-serif; background-color: #f8fafc; }
        .glass-nav { background: rgba(255, 255, 255, 0.9); backdrop-filter: blur(10px); border-bottom: 1px solid rgba(226, 232, 240, 0.8); }
        .card-hover { transition: all 0.3s ease; }
        .card-hover:hover { transform: translateY(-5px); box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04); }
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
        @keyframes fadeUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
        .animate-fade-up { animation: fadeUp 0.5s ease-out forwards; }
        
        #btn-cat-all.active { background-color: #2563eb !important; color: white !important; border-color: #2563eb !important; }
        #btn-cat-emergency.active { background-color: #dc2626 !important; color: white !important; border-color: #dc2626 !important; }
        #btn-cat-emergency.active i { color: white !important; }
        #btn-cat-lost.active { background-color: #ea580c !important; color: white !important; border-color: #ea580c !important; }
        #btn-cat-lost.active i { color: white !important; }
        #btn-cat-found.active { background-color: #16a34a !important; color: white !important; border-color: #16a34a !important; }
        #btn-cat-found.active i { color: white !important; }
        #btn-cat-general.active { background-color: #2563eb !important; color: white !important; border-color: #2563eb !important; }
        #btn-cat-general.active i { color: white !important; }
    </style>
</head>
<body class="text-slate-800 pb-20 md:pb-0">

    <!-- Navbar -->
    <nav class="glass-nav sticky top-0 z-40 w-full px-4 py-3 sm:px-6 lg:px-8 shadow-sm">
        <div class="max-w-7xl mx-auto flex justify-between items-center">
            <div class="flex items-center gap-2">
                <div class="w-10 h-10 bg-blue-600 text-white rounded-xl flex items-center justify-center shadow-lg">
                    <i class="fa-solid fa-graduation-cap text-xl"></i>
                </div>
                <span class="font-bold text-xl text-blue-900 hidden sm:block">Campus Care</span>
            </div>
            <div class="flex items-center gap-4">
                
                <!-- ปุ่มพิเศษ: แสดงเฉพาะแอดมิน -->
                <?php if (strtolower($user_role) === 'admin'): ?>
                <a href="admin.php" class="hidden sm:flex items-center bg-red-50 text-red-600 border border-red-200 hover:bg-red-600 hover:text-white px-3 py-1.5 rounded-lg text-sm font-semibold transition-colors">
                    <i class="fa-solid fa-shield-halved mr-1.5"></i> หลังบ้าน
                </a>
                <?php endif; ?>

                <div class="text-right hidden md:block">
                    <p class="text-sm font-semibold text-gray-800"><?php echo htmlspecialchars($user_name); ?></p>
                    <p class="text-xs text-blue-600 font-medium"><i class="fa-solid fa-medal mr-1"></i> ระดับ: <?php echo htmlspecialchars($badge_level); ?></p>
                </div>
                <div class="w-10 h-10 rounded-full bg-blue-100 border-2 border-blue-200 overflow-hidden">
                    <img src="https://ui-avatars.com/api/?name=<?php echo urlencode($user_name); ?>&background=eff6ff&color=2563eb" alt="Profile" class="w-full h-full object-cover">
                </div>
                <a href="../api/auth/logout.php" class="text-gray-400 hover:text-red-500 transition-colors ml-2" title="ออกจากระบบ">
                    <i class="fa-solid fa-right-from-bracket text-xl"></i>
                </a>
            </div>
        </div>
    </nav>

    <!-- เนื้อหาหลัก -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        
        <!-- แบนเนอร์ต้อนรับ -->
        <div class="bg-gradient-to-r from-blue-600 to-indigo-700 rounded-2xl p-6 sm:p-10 text-white shadow-xl mb-8 relative overflow-hidden">
            <div class="relative z-10">
                <h2 class="text-2xl sm:text-3xl font-bold mb-2">สวัสดีฮีโร่, <?php echo explode(' ', trim($user_name))[0]; ?>! 👋</h2>
                <p class="text-blue-100 text-sm sm:text-base max-w-xl">วันนี้มีเพื่อนๆ ในมหาวิทยาลัยต้องการความช่วยเหลือ หรือคุณพบเจอของหายที่อยากส่งคืนไหม?</p>
                <div class="mt-6 flex flex-wrap gap-3">
                    <button onclick="openPostModal()" class="bg-white text-blue-700 px-5 py-2.5 rounded-lg font-semibold shadow-md hover:bg-blue-50 transition-colors active:scale-95 flex items-center">
                        <i class="fa-solid fa-bullhorn mr-2"></i> แจ้งเหตุ / ของหาย
                    </button>
                    <button class="bg-blue-800/50 hover:bg-blue-800/70 text-white px-5 py-2.5 rounded-lg font-medium backdrop-blur-sm transition-colors active:scale-95 flex items-center border border-blue-500/30">
                        <i class="fa-solid fa-ranking-star mr-2"></i> กระดานผู้นำ
                    </button>
                </div>
            </div>
            <i class="fa-solid fa-hands-holding-circle absolute -bottom-10 -right-10 text-[150px] opacity-10 transform -rotate-12"></i>
        </div>

        <!-- ตัวกรองหมวดหมู่ -->
        <div class="flex items-center gap-3 overflow-x-auto no-scrollbar pb-2 mb-6">
            <button id="btn-cat-all" onclick="loadPosts('all')" class="btn-category active shrink-0 px-4 py-2 bg-white text-gray-600 border border-gray-200 hover:bg-gray-50 rounded-full font-medium text-sm transition-colors">ทั้งหมด</button>
            <button id="btn-cat-emergency" onclick="loadPosts('emergency')" class="btn-category shrink-0 px-4 py-2 bg-white text-gray-600 border border-gray-200 hover:bg-gray-50 rounded-full font-medium text-sm transition-colors"><i class="fa-solid fa-car-burst text-red-500 mr-1.5"></i> ฉุกเฉิน</button>
            <button id="btn-cat-lost" onclick="loadPosts('lost')" class="btn-category shrink-0 px-4 py-2 bg-white text-gray-600 border border-gray-200 hover:bg-gray-50 rounded-full font-medium text-sm transition-colors"><i class="fa-solid fa-magnifying-glass text-orange-500 mr-1.5"></i> ตามหาของ</button>
            <button id="btn-cat-found" onclick="loadPosts('found')" class="btn-category shrink-0 px-4 py-2 bg-white text-gray-600 border border-gray-200 hover:bg-gray-50 rounded-full font-medium text-sm transition-colors"><i class="fa-solid fa-box-open text-green-500 mr-1.5"></i> เก็บของได้</button>
            <button id="btn-cat-general" onclick="loadPosts('general')" class="btn-category shrink-0 px-4 py-2 bg-white text-gray-600 border border-gray-200 hover:bg-gray-50 rounded-full font-medium text-sm transition-colors"><i class="fa-solid fa-handshake-angle text-blue-500 mr-1.5"></i> ขอความช่วยเหลือ</button>
        </div>

        <!-- Grid แสดงโพสต์ -->
        <div id="postsGrid" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            <div onclick="openPostModal()" class="bg-blue-50/50 rounded-2xl border-2 border-dashed border-blue-200 hover:border-blue-400 hover:bg-blue-50 transition-colors flex flex-col items-center justify-center p-8 text-center cursor-pointer min-h-[300px] group order-first">
                <div class="w-16 h-16 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center mb-4 group-hover:scale-110 transition-transform duration-300">
                    <i class="fa-solid fa-plus text-2xl"></i>
                </div>
                <h3 class="text-lg font-bold text-blue-900 mb-1">สร้างโพสต์ใหม่</h3>
                <p class="text-sm text-blue-600/70">แจ้งเหตุฉุกเฉิน หรือประกาศตามหาของหายได้ที่นี่</p>
            </div>
        </div>
    </main>

    <!-- ปุ่ม FAB ลอยตัวขวาล่าง -->
    <button onclick="openPostModal()" class="fixed bottom-20 right-4 md:bottom-10 md:right-10 z-50 w-14 h-14 bg-blue-600 text-white rounded-full shadow-[0_4px_20px_rgba(37,99,235,0.4)] flex items-center justify-center hover:bg-blue-700 hover:scale-110 hover:-translate-y-1 transition-all duration-300 focus:outline-none group">
        <i class="fa-solid fa-plus text-2xl group-hover:rotate-90 transition-transform duration-300"></i>
    </button>

    <!-- Mobile Bottom Navigation -->
    <div class="md:hidden fixed bottom-0 left-0 w-full bg-white border-t border-gray-200 flex justify-around items-center py-3 px-2 z-40 shadow-[0_-5px_15px_-5px_rgba(0,0,0,0.1)]">
        <a href="index.php" class="flex flex-col items-center text-blue-600">
            <i class="fa-solid fa-house text-lg mb-1"></i><span class="text-[10px] font-medium">หน้าหลัก</span>
        </a>
        <a href="#" class="flex flex-col items-center text-gray-400 hover:text-blue-600 transition-colors">
            <i class="fa-solid fa-bell text-lg mb-1"></i><span class="text-[10px] font-medium">แจ้งเตือน</span>
        </a>
        <a href="#" class="relative -top-5 flex items-center justify-center w-12 h-12 bg-white text-gray-400 rounded-full shadow-lg border-4 border-slate-50 hover:text-blue-600 transition-colors focus:outline-none">
            <i class="fa-solid fa-search text-xl"></i>
        </a>
        <a href="#" class="flex flex-col items-center text-gray-400 hover:text-blue-600 transition-colors">
            <i class="fa-solid fa-ranking-star text-lg mb-1"></i><span class="text-[10px] font-medium">จัดอันดับ</span>
        </a>
        <!-- 🚀 ปุ่มโปรไฟล์ มือถือ (ลิงก์ไปหน้า profile.php แล้ว) -->
        <a href="profile.php" class="flex flex-col items-center text-gray-400 hover:text-blue-600 transition-colors">
            <i class="fa-solid fa-user text-lg mb-1"></i>
            <span class="text-[10px] font-medium">โปรไฟล์</span>
        </a>
    </div>

    <!-- Modal สร้างโพสต์ใหม่ -->
    <div id="createPostModal" class="fixed inset-0 z-[100] hidden items-center justify-center">
        <div class="absolute inset-0 bg-slate-900/40 backdrop-blur-sm transition-opacity" onclick="closePostModal()"></div>
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg mx-4 relative z-10 transform scale-95 opacity-0 transition-all duration-300 flex flex-col max-h-[90vh]" id="modalContent">
            
            <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-gray-50/50 rounded-t-2xl">
                <h3 class="text-xl font-bold text-gray-800"><i class="fa-solid fa-pen-to-square text-blue-600 mr-2"></i>สร้างโพสต์ใหม่</h3>
                <button onclick="closePostModal()" class="text-gray-400 hover:text-red-500 transition-colors w-8 h-8 flex items-center justify-center rounded-full hover:bg-red-50 focus:outline-none">
                    <i class="fa-solid fa-xmark text-lg"></i>
                </button>
            </div>

            <div class="p-6 overflow-y-auto no-scrollbar">
                <form id="createPostForm" class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">หมวดหมู่</label>
                        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                            <label class="cursor-pointer">
                                <input type="radio" name="category" value="emergency" class="peer sr-only" required checked>
                                <div class="text-center py-2 px-2 border border-gray-200 rounded-lg peer-checked:bg-red-50 peer-checked:border-red-500 peer-checked:text-red-600 transition-all text-sm font-medium text-gray-600 hover:bg-gray-50">
                                    <i class="fa-solid fa-car-burst mb-1 block text-lg"></i> ฉุกเฉิน
                                </div>
                            </label>
                            <label class="cursor-pointer">
                                <input type="radio" name="category" value="lost" class="peer sr-only">
                                <div class="text-center py-2 px-2 border border-gray-200 rounded-lg peer-checked:bg-orange-50 peer-checked:border-orange-500 peer-checked:text-orange-600 transition-all text-sm font-medium text-gray-600 hover:bg-gray-50">
                                    <i class="fa-solid fa-magnifying-glass mb-1 block text-lg"></i> ของหาย
                                </div>
                            </label>
                            <label class="cursor-pointer">
                                <input type="radio" name="category" value="found" class="peer sr-only">
                                <div class="text-center py-2 px-2 border border-gray-200 rounded-lg peer-checked:bg-green-50 peer-checked:border-green-500 peer-checked:text-green-600 transition-all text-sm font-medium text-gray-600 hover:bg-gray-50">
                                    <i class="fa-solid fa-box-open mb-1 block text-lg"></i> เก็บของได้
                                </div>
                            </label>
                            <label class="cursor-pointer">
                                <input type="radio" name="category" value="general" class="peer sr-only">
                                <div class="text-center py-2 px-2 border border-gray-200 rounded-lg peer-checked:bg-blue-50 peer-checked:border-blue-500 peer-checked:text-blue-600 transition-all text-sm font-medium text-gray-600 hover:bg-gray-50">
                                    <i class="fa-solid fa-handshake-angle mb-1 block text-lg"></i> ช่วยเหลือ
                                </div>
                            </label>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">หัวข้อ</label>
                        <input type="text" name="title" required placeholder="เช่น รถสตาร์ทไม่ติด, ทำกระเป๋าตังค์หล่น" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">รายละเอียดเพิ่มเติม</label>
                        <textarea name="description" rows="3" required placeholder="อธิบายสิ่งที่ต้องการความช่วยเหลือ หรือลักษณะสิ่งของ..." class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all resize-none"></textarea>
                    </div>

                    <div class="relative">
                        <label class="block text-sm font-medium text-gray-700 mb-1">สถานที่ (พิกัดในมหาวิทยาลัย)</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400"><i class="fa-solid fa-location-dot"></i></span>
                            <input type="text" id="locationInput" name="location" required autocomplete="off" placeholder="พิมพ์ค้นหาอาคาร..." class="w-full pl-9 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all">
                        </div>
                        <div id="locationSuggestions" class="absolute z-50 left-0 right-0 mt-1 bg-white border border-gray-200 rounded-lg shadow-lg max-h-48 overflow-y-auto hidden"></div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">หมายเหตุเพิ่มเติม <span class="text-gray-400 text-xs">(ไม่บังคับใส่)</span></label>
                        <input type="text" name="note" placeholder="เช่น ชั้น 2 หน้าห้องแลป, ใกล้ตู้ ATM" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">รูปภาพประกอบ (ถ้ามี)</label>
                        <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg hover:border-blue-400 hover:bg-blue-50/50 transition-colors cursor-pointer" onclick="document.getElementById('file-upload').click()">
                            <div class="space-y-1 text-center">
                                <i class="fa-regular fa-image text-3xl text-gray-400"></i>
                                <div class="flex text-sm text-gray-600 justify-center">
                                    <span class="relative cursor-pointer bg-transparent rounded-md font-medium text-blue-600 hover:text-blue-500 focus-within:outline-none">
                                        <span>อัปโหลดรูปภาพ</span>
                                        <input id="file-upload" name="image" type="file" accept="image/*" class="sr-only">
                                    </span>
                                </div>
                                <p class="text-xs text-gray-500">PNG, JPG ไม่เกิน 5MB</p>
                            </div>
                        </div>
                        <p id="fileNameDisplay" class="text-xs text-green-600 mt-2 font-medium hidden text-center"></p>
                    </div>
                </form>
            </div>

            <div class="px-6 py-4 border-t border-gray-100 bg-gray-50 rounded-b-2xl flex justify-end gap-3">
                <button type="button" onclick="closePostModal()" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors focus:outline-none">ยกเลิก</button>
                <button type="submit" form="createPostForm" id="submitPostBtn" class="px-4 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-lg hover:bg-blue-700 shadow-sm transition-colors flex items-center focus:outline-none">
                    <i class="fa-solid fa-paper-plane mr-2"></i> โพสต์เลย
                </button>
            </div>
        </div>
    </div>

    <script>
        const campusLocations = [
            "อาคารศูนย์ศึกษาและพัฒนา (CCS)", "อาคารหอประชุมสิริวรปัญญา", "อาคารกิจการนักศึกษา",
            "อาคาร 15 ชั้น", "อาคารศูนย์ภาษาและศูนย์คอมพิวเตอร์ (LI)", "สำนักวิทยบริการ",
            "คณะครุศาสตร์ (ใหม่)", "คณะวิทยาศาสตร์และเทคโนโลยี (SC)", "คณะมนุษยศาสตร์และสังคมศาสตร์ (ใหม่) (HS)",
            "คณะวิทยาการจัดการ (โลจิสติกส์) (LO)", "คณะพยาบาลศาสตร์ (NU)", "สำนักคอมพิวเตอร์",
            "อาคาร A7", "อาคารศูนย์ปฏิบัติการคอมพิวเตอร์ (C)", "อาคาร A3", "คณะครุศาสตร์ (เก่า) (A5)",
            "อาคาร A6", "ETB Building", "ตึกทราวดี (ท)", "อาคารร้อยปีฝึกหัดครูไทย (อภน)",
            "อาคาร A1", "โรงอาหาร", "โรงเรียนสาธิต (ม.)", "อาคารหอพักนานาชาติ", "สระมรกต",
            "Sport Complex", "หอพักพยาบาล", "อาคาร A4", "อาคาร A2", "โรงอาหารใหม่",
            "วิศวกรรมโยธา", "ศูนย์สาธิตการศึกษาปฐมวัย", "สระว่ายน้ำ", "สมาคมศิษย์เก่า",
            "ศาลาเฟื่องฟ้า", "อาคาร 7-11 / เซเว่น", "ลานจอดรถตึกวิศวะ", "โรงอาหารกลาง"
        ];

        const locationInput = document.getElementById('locationInput');
        const locationSuggestions = document.getElementById('locationSuggestions');

        locationInput.addEventListener('input', function() {
            const query = this.value.trim().toLowerCase();
            locationSuggestions.innerHTML = '';
            if (query.length === 0) { locationSuggestions.classList.add('hidden'); return; }
            const filtered = campusLocations.filter(loc => loc.toLowerCase().includes(query));
            if (filtered.length > 0) {
                locationSuggestions.classList.remove('hidden');
                filtered.forEach(loc => {
                    const div = document.createElement('div');
                    div.className = 'px-4 py-2 hover:bg-blue-50 cursor-pointer text-sm text-gray-700 border-b border-gray-100 last:border-none';
                    div.innerHTML = `<i class="fa-solid fa-location-dot text-blue-500 mr-2"></i> ${loc}`;
                    div.addEventListener('click', function() {
                        locationInput.value = loc;
                        locationSuggestions.classList.add('hidden');
                    });
                    locationSuggestions.appendChild(div);
                });
            } else { locationSuggestions.classList.add('hidden'); }
        });

        document.addEventListener('click', function(e) {
            if (!locationInput.contains(e.target) && !locationSuggestions.contains(e.target)) {
                locationSuggestions.classList.add('hidden');
            }
        });

        const modal = document.getElementById('createPostModal');
        const modalContent = document.getElementById('modalContent');
        const fileUpload = document.getElementById('file-upload');
        const fileNameDisplay = document.getElementById('fileNameDisplay');
        const createPostForm = document.getElementById('createPostForm');

        function openPostModal() {
            modal.classList.remove('hidden'); modal.classList.add('flex');
            setTimeout(() => { modalContent.classList.remove('scale-95', 'opacity-0'); modalContent.classList.add('scale-100', 'opacity-100'); }, 10);
        }
        function closePostModal() {
            modalContent.classList.remove('scale-100', 'opacity-100'); modalContent.classList.add('scale-95', 'opacity-0');
            setTimeout(() => { modal.classList.remove('flex'); modal.classList.add('hidden'); createPostForm.reset(); fileNameDisplay.classList.add('hidden'); locationSuggestions.classList.add('hidden'); }, 300);
        }
        fileUpload.addEventListener('change', function() {
            if (this.files && this.files.length > 0) {
                fileNameDisplay.textContent = 'ไฟล์ที่เลือก: ' + this.files[0].name;
                fileNameDisplay.classList.remove('hidden');
            } else { fileNameDisplay.classList.add('hidden'); }
        });

        createPostForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const submitBtn = document.getElementById('submitPostBtn');
            const originalText = submitBtn.innerHTML;
            submitBtn.innerHTML = `<i class="fa-solid fa-circle-notch fa-spin mr-2"></i> กำลังโพสต์...`;
            submitBtn.disabled = true;

            const formData = new FormData(this);
            fetch('../api/posts/create.php', { method: 'POST', body: formData })
            .then(res => res.json())
            .then(data => {
                submitBtn.innerHTML = originalText; submitBtn.disabled = false;
                if (data.status === 'success') {
                    closePostModal();
                    Swal.fire({ icon: 'success', title: 'สำเร็จ!', text: data.message, confirmButtonColor: '#2563eb' })
                    .then(() => { loadPosts('all'); });
                } else { Swal.fire({ icon: 'error', title: 'ผิดพลาด', text: data.message }); }
            });
        });

        function loadPosts(category = 'all') {
            document.querySelectorAll('.btn-category').forEach(btn => btn.classList.remove('active'));
            const activeBtn = document.getElementById('btn-cat-' + category);
            if(activeBtn) activeBtn.classList.add('active');

            const postsGrid = document.getElementById('postsGrid');
            const createPostBox = postsGrid.firstElementChild.outerHTML; 
            postsGrid.innerHTML = createPostBox + `<div class="col-span-full text-center py-10 text-gray-500"><i class="fa-solid fa-circle-notch fa-spin text-2xl mb-2 text-blue-500"></i><br>กำลังโหลด...</div>`;

            fetch(`../api/posts/get_posts.php?category=${category}`)
            .then(res => res.json())
            .then(data => {
                if (data.status === 'success') {
                    postsGrid.innerHTML = createPostBox; 
                    const posts = data.data;
                    if (posts.length === 0) {
                        postsGrid.innerHTML += `<div class="col-span-full text-center py-10 text-gray-400"><i class="fa-regular fa-folder-open text-4xl mb-3"></i><p>ยังไม่มีโพสต์ในหมวดหมู่นี้</p></div>`; return;
                    }
                    posts.forEach(post => {
                        let catBadge = '';
                        if (post.category === 'emergency') catBadge = `<span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800"><i class="fa-solid fa-car-burst mr-1.5"></i> ฉุกเฉิน</span>`;
                        else if (post.category === 'lost') catBadge = `<span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-orange-100 text-orange-800"><i class="fa-solid fa-magnifying-glass mr-1.5"></i> ของหาย</span>`;
                        else if (post.category === 'found') catBadge = `<span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800"><i class="fa-solid fa-box-open mr-1.5"></i> เก็บของได้</span>`;
                        else catBadge = `<span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800"><i class="fa-solid fa-handshake-angle mr-1.5"></i> ช่วยเหลือ</span>`;

                        const isResolved = post.status === 'resolved';
                        const resolvedOpacity = isResolved ? 'opacity-60 grayscale' : '';
                        const resolvedBadge = isResolved ? `<div class="absolute top-2 right-2 z-10 bg-green-500 text-white px-3 py-1 rounded-full text-[10px] font-bold shadow-md"><i class="fa-solid fa-check"></i> สำเร็จแล้ว</div>` : '';

                        let imageHTML = '';
                        if (post.image_url) {
                            imageHTML = `<div class="h-48 bg-gray-200 overflow-hidden relative border-b border-gray-100">${resolvedBadge}<img src="../${post.image_url}" class="w-full h-full object-cover transition-transform duration-500 hover:scale-110 ${resolvedOpacity}" alt="Post Image"></div>`;
                        }

                        const postDate = new Date(post.created_at).toLocaleDateString('th-TH');

                        const cardHTML = `
                        <div class="bg-white rounded-2xl border ${isResolved ? 'border-green-200 bg-green-50/30' : 'border-gray-100'} shadow-sm card-hover overflow-hidden flex flex-col animate-fade-up relative">
                            ${!post.image_url && isResolved ? resolvedBadge : ''}
                            ${imageHTML}
                            <div class="p-5 flex-grow ${isResolved ? 'opacity-70' : ''}">
                                <div class="flex justify-between items-start mb-3">${catBadge}<span class="text-[10px] text-gray-400 font-medium">${postDate}</span></div>
                                <h3 class="text-lg font-bold ${isResolved ? 'text-gray-500 line-through' : 'text-gray-900'} mb-2 line-clamp-2">${post.title}</h3>
                                <p class="text-gray-500 text-sm mb-4 line-clamp-2">${post.description}</p>
                                <div class="flex items-center text-sm text-gray-500 mb-2"><i class="fa-solid fa-location-dot text-red-400 mr-2 w-4 text-center"></i><span class="truncate">${post.location}</span></div>
                            </div>
                            <div class="px-5 py-4 ${isResolved ? 'bg-green-50/50 border-t border-green-100' : 'bg-gray-50 border-t border-gray-100'} flex justify-between items-center mt-auto">
                                <div class="flex items-center gap-2"><img src="https://ui-avatars.com/api/?name=${encodeURIComponent(post.full_name)}&background=random" class="w-6 h-6 rounded-full ${resolvedOpacity}" alt="User"><span class="text-xs font-medium text-gray-600">${post.full_name}</span></div>
                                <a href="post_detail.php?id=${post.id}" class="text-sm font-semibold ${isResolved ? 'text-green-600 hover:text-green-800' : 'text-blue-600 hover:text-blue-800'} flex items-center transition-colors">ดูรายละเอียด <i class="fa-solid fa-arrow-right ml-1 text-xs"></i></a>
                            </div>
                        </div>`;
                        postsGrid.innerHTML += cardHTML;
                    });
                }
            });
        }

        document.addEventListener('DOMContentLoaded', () => { loadPosts('all'); });
    </script>
</body>
</html>
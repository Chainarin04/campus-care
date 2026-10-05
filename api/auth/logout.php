<?php
// ไฟล์: api/auth/logout.php
session_start();
session_unset();
session_destroy();
header("Location: ../../views/login.php");
exit;
?>
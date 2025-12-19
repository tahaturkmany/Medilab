<?php
session_start();
require "db.php";

$message = "";

if (!isset($_SESSION['admin'])) {
    // إعادة التوجيه إذا لم يكن الأدمن مسجّل دخول
    header("Location: login.php");
    exit;
}

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    // Prepared Statement للحذف بأمان
    $stmt = $conn->prepare("DELETE FROM bookings WHERE id = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        $message = "تم حذف الطلب بنجاح ✅";
    } else {
        $message = "حدث خطأ أثناء الحذف ❌";
    }

    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
<meta charset="UTF-8">
<title>حذف الطلب</title>
<link href="https://fonts.googleapis.com/css2?family=Cairo&display=swap" rel="stylesheet">
<style>
*{box-sizing:border-box;font-family:'Cairo',sans-serif}
body{
  background:linear-gradient(135deg,#325c6a,#0cadac);
  height:100vh;
  display:flex;
  align-items:center;
  justify-content:center;
}
.message-box{
  background:white;
  width:360px;
  padding:30px;
  border-radius:12px;
  box-shadow:0 10px 25px rgba(0,0,0,.2);
  text-align:center;
}
.message-box h2{color:#325c6a;margin-bottom:20px}
.success{color:green;margin-bottom:15px;font-weight:bold;}
.error{color:red;margin-bottom:15px;font-weight:bold;}
button{
  width:100%;
  padding:12px;
  background:#0cadac;
  color:white;
  border:none;
  border-radius:8px;
  cursor:pointer;
  margin-top:10px;
}
</style>
</head>
<body>

<div class="message-box">
  <h2>حذف الطلب</h2>
  <?php if($message): ?>
    <div class="<?= strpos($message,'نجاح')!==false ? 'success' : 'error' ?>">
      <?= $message ?>
    </div>
  <?php endif; ?>
  <button onclick="window.location.href='admin.php'">العودة للوحة الإدارة</button>
</div>

</body>
</html>

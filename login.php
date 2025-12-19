<?php
session_start();
require "db.php";

/* ===== عند إرسال الفورم ===== */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $username = $_POST['username'];
  $password = md5($_POST['password']);

  $sql = "SELECT * FROM admins WHERE username=? AND password=?";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("ss", $username, $password);
  $stmt->execute();
  $result = $stmt->get_result();

  if ($result->num_rows === 1) {
    $_SESSION['admin'] = $username;
    header("Location: admin.php");
    exit;
  } else {
    $error = "اسم المستخدم أو كلمة المرور غير صحيحة";
  }
}

/* ===== إذا كان مسجّل دخول ===== */
if (isset($_SESSION['admin'])) {
  header("Location: admin.php");
  exit;
}
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
<meta charset="UTF-8">
<title>تسجيل دخول الأدمن</title>
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
.login-box{
  background:white;
  width:360px;
  padding:30px;
  border-radius:12px;
  box-shadow:0 10px 25px rgba(0,0,0,.2);
  text-align:center;
}
.login-box h2{color:#325c6a;margin-bottom:20px}
.login-box input{
  width:100%;
  padding:12px 40px 12px 12px;
  margin:10px 0;
  border-radius:8px;
  border:1px solid #ccc;
}
.password-box{position:relative}
.toggle-password{
  position:absolute;
  left:12px;
  top:50%;
  transform:translateY(-50%);
  cursor:pointer;
  color:#0cadac;
}
button{
  width:100%;
  padding:12px;
  background:#0cadac;
  color:white;
  border:none;
  border-radius:8px;
  cursor:pointer;
}
.error{
  color:red;
  margin-bottom:10px;
}
</style>
</head>

<body>

<div class="login-box">
  <h2>🔐 تسجيل دخول الأدمن</h2>

  <?php if(isset($error)): ?>
    <div class="error"><?= $error ?></div>
  <?php endif; ?>

  <form method="POST">
    <input type="text" name="username" placeholder="اسم المستخدم" required>

    <div class="password-box">
      <input type="password" name="password" id="password" placeholder="كلمة المرور" required>
      <span class="toggle-password" onclick="togglePassword()">👁</span>
    </div>

    <button type="submit">تسجيل الدخول</button>
  </form>

  <p>لوحة تحكم Medilap</p>
</div>

<script>
function togglePassword(){
  const p=document.getElementById("password");
  p.type=p.type==="password"?"text":"password";
}
</script>

</body>
</html>

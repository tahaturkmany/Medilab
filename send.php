<?php
require __DIR__ . "/db.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

  $name    = trim($_POST['name'] ?? '');
  $email   = trim($_POST['email'] ?? '');
  $subject = trim($_POST['subject'] ?? '');
  $message = trim($_POST['message'] ?? '');

  // التحقق من الحقول
  if ($name === '' || $email === '' || $subject === '' || $message === '') {
    die("جميع الحقول مطلوبة");
  }

  // التحقق من الإيميل
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    die("البريد الإلكتروني غير صالح");
  }

  // الاستعلام الآمن
  $stmt = $conn->prepare(
    "INSERT INTO bookings (name, email, subject, message)
     VALUES (?, ?, ?, ?)"
  );

  $stmt->bind_param("ssss", $name, $email, $subject, $message);
  $stmt->execute();

  echo "<script>
    alert('تم إرسال الرسالة بنجاح');
    window.location.href='index.html';
  </script>";
}
?>

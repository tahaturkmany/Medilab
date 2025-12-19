<?php require "db.php"; ?>

<form method="POST">
<input name="name" placeholder="الاسم" required><br><br>
<input name="email" placeholder="الإيميل" required><br><br>
<input name="subject" placeholder="الموضوع" required><br><br>
<textarea name="message" placeholder="الرسالة"></textarea><br><br>
<button name="add">إضافة</button>
</form>

<?php
if(isset($_POST['add'])){
$stmt=$conn->prepare(
"INSERT INTO bookings(name,email,subject,message) VALUES(?,?,?,?)");
$stmt->bind_param("ssss",
$_POST['name'],$_POST['email'],$_POST['subject'],$_POST['message']);
$stmt->execute();
header("Location: admin.php");
}
?>

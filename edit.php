<?php
require "db.php";
$id = $_GET['id'] ?? 0;

$row = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM bookings WHERE id=$id"));

if (!$row) {
    die("الطلب غير موجود!");
}

if (isset($_POST['update'])) {
    $stmt = $conn->prepare(
        "UPDATE bookings SET name=?, email=?, subject=?, message=? WHERE id=?"
    );
    $stmt->bind_param(
        "ssssi",
        $_POST['name'],
        $_POST['email'],
        $_POST['subject'],
        $_POST['message'],
        $id
    );
    $stmt->execute();
    header("Location: admin.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
<meta charset="UTF-8">
<title>تعديل الطلب</title>
<link href="https://fonts.googleapis.com/css2?family=Cairo&display=swap" rel="stylesheet">
<style>
body {
    font-family: 'Cairo', sans-serif;
    background: linear-gradient(135deg, #325c6a, #0cadac);
    height: 100vh;
    display: flex;
    justify-content: center;
    align-items: center;
}
.edit-box {
    background: white;
    padding: 30px 40px;
    border-radius: 12px;
    box-shadow: 0 10px 25px rgba(0,0,0,0.2);
    width: 400px;
}
.edit-box h2 {
    text-align: center;
    margin-bottom: 20px;
    color: #325c6a;
}
.edit-box input, 
.edit-box textarea {
    width: 100%;
    padding: 12px;
    margin: 10px 0;
    border-radius: 8px;
    border: 1px solid #ccc;
    font-size: 15px;
    box-sizing: border-box;
}
.edit-box textarea {
    resize: vertical;
    height: 120px;
}
.edit-box button {
    width: 100%;
    padding: 12px;
    background-color: #0cadac;
    color: white;
    border: none;
    border-radius: 8px;
    font-size: 16px;
    cursor: pointer;
    transition: 0.3s;
}
.edit-box button:hover {
    background-color: #099999;
}
.back-link {
    display: block;
    text-align: center;
    margin-top: 15px;
    color: #325c6a;
    text-decoration: none;
}
.back-link:hover {
    text-decoration: underline;
}
</style>
</head>
<body>

<div class="edit-box">
    <h2>✏️ تعديل الطلب</h2>

    <form method="POST">
        <input name="name" value="<?= htmlspecialchars($row['name']) ?>" placeholder="الاسم" required>
        <input name="email" value="<?= htmlspecialchars($row['email']) ?>" placeholder="الإيميل" required>
        <input name="subject" value="<?= htmlspecialchars($row['subject']) ?>" placeholder="الموضوع" required>
        <textarea name="message" placeholder="الرسالة"><?= htmlspecialchars($row['message']) ?></textarea>
        <button name="update">تحديث</button>
    </form>

    <a class="back-link" href="admin.php">⬅ العودة إلى لوحة التحكم</a>
</div>

</body>
</html>

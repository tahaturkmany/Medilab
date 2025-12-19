<?php
/************* حماية الصفحة *************/
session_start();
if (!isset($_SESSION['admin'])) {
  header("Location: login.php");
  exit;
}

/************* الاتصال بقاعدة البيانات *************/
require "db.php";

/************* إضافة طلب جديد *************/
if (isset($_POST['add'])) {
  $stmt = $conn->prepare(
    "INSERT INTO bookings (name,email,subject,message)
     VALUES (?,?,?,?)"
  );
  $stmt->bind_param(
    "ssss",
    $_POST['name'],
    $_POST['email'],
    $_POST['subject'],
    $_POST['message']
  );
  $stmt->execute();
  header("Location: admin.php");
  exit;
}

/************* البحث (استعلام) *************/
$search = $_GET['search'] ?? '';

$sql = "SELECT * FROM bookings
        WHERE name LIKE ?
        OR email LIKE ?
        OR subject LIKE ?
        ORDER BY created_at DESC";

$stmt = $conn->prepare($sql);
$like = "%$search%";
$stmt->bind_param("sss", $like, $like, $like);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
<meta charset="UTF-8">
<title>لوحة تحكم الأدمن</title>

<style>
body{font-family:Arial;background:#f4f4f4}
h2{text-align:center}
.container{width:95%;margin:auto}
form{margin:15px 0}
input,textarea{
  width:100%;
  padding:8px;
  margin:5px 0;
}
button{
  padding:8px 15px;
  background:#325c6a;
  color:white;
  border:none;
  cursor:pointer;
}
.logout{
  background:red;
  padding:8px 12px;
  color:white;
  text-decoration:none;
}
.top-bar{
  display:flex;
  justify-content:space-between;
  align-items:center;
}
table{
  width:100%;
  border-collapse:collapse;
  background:white;
}
th,td{
  border:1px solid #ccc;
  padding:8px;
  text-align:center;
}
th{background:#325c6a;color:white}
.edit{color:#0cadac;text-decoration:none}
.delete{color:red;text-decoration:none}
.add-box{
  background:white;
  padding:15px;
  margin:20px 0;
}
</style>
</head>

<body>

<h2>📋 لوحة إدارة الطلبات</h2>

<div class="container">

<div class="top-bar">
  <!-- البحث -->
  <form method="GET">
    <input type="text" name="search"
           placeholder="بحث بالاسم أو الإيميل"
           value="<?= htmlspecialchars($search) ?>">
    <button>بحث</button>
  </form>

  <a class="logout" href="logout.php">تسجيل خروج</a>
</div>

<!-- ===== إضافة طلب ===== -->
<div class="add-box">
<h3>➕ إضافة طلب جديد</h3>

<form method="POST">
  <input name="name" placeholder="الاسم" required>
  <input name="email" placeholder="الإيميل" required>
  <input name="subject" placeholder="الموضوع" required>
  <textarea name="message" placeholder="الرسالة"></textarea>
  <button name="add">إضافة</button>
</form>
</div>

<!-- ===== جدول البيانات ===== -->
<table>
<tr>
  <th>ID</th>
  <th>الاسم</th>
  <th>الإيميل</th>
  <th>الموضوع</th>
  <th>الرسالة</th>
  <th>التاريخ</th>
  <th>تعديل</th>
  <th>حذف</th>
</tr>

<?php while($row = $result->fetch_assoc()) { ?>
<tr>
  <td><?= $row['id'] ?></td>
  <td><?= htmlspecialchars($row['name']) ?></td>
  <td><?= htmlspecialchars($row['email']) ?></td>
  <td><?= htmlspecialchars($row['subject']) ?></td>
  <td><?= htmlspecialchars($row['message']) ?></td>
  <td><?= $row['created_at'] ?></td>
  <td><a class="edit" href="edit.php?id=<?= $row['id'] ?>">✏️</a></td>
  <td>
    <a class="delete"
       href="delete.php?id=<?= $row['id'] ?>"
       onclick="return confirm('هل أنت متأكد؟')">🗑️</a>
  </td>
</tr>
<?php } ?>

</table>

</div>
</body>
</html>

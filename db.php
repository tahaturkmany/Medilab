<?php
$conn = mysqli_connect("localhost", "root", "", "medilap");

if (!$conn) {
  die("فشل الاتصال بقاعدة البيانات");
}

mysqli_set_charset($conn, "utf8");
?>

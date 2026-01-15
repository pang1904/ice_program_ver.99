<?php
session_start();
include "../config/db.php";

if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $q = mysqli_query($conn, "SELECT * FROM users WHERE username='$username'");
    $user = mysqli_fetch_assoc($q);

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user'] = $user;

        if ($user['role'] == "customer") {
            header("Location: ../customer/index.php");
        } else {
            header("Location: ../seller/dashboard.php");
        }
    } else {
        echo "ชื่อผู้ใช้หรือรหัสผ่านผิด";
    }
}
?>

<h2>เข้าสู่ระบบ</h2>

<form method="post">
  <input name="username" placeholder="ชื่อผู้ใช้"><br><br>
  <input type="password" name="password" placeholder="รหัสผ่าน"><br><br>
  <button name="login">เข้าสู่ระบบ</button>
</form>

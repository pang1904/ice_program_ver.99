<?php
include "../config/db.php";

if (isset($_POST['submit'])) {
    $role = $_POST['role'];
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $sql = "INSERT INTO users (role, username, password)
            VALUES ('$role', '$username', '$password')";

    mysqli_query($conn, $sql);
    echo "สมัครสมาชิกสำเร็จ <a href='login.php'>เข้าสู่ระบบ</a>";
}
?>

<h2>สมัครสมาชิก</h2>

<form method="post">
  <select name="role">
    <option value="customer">ลูกค้า</option>
    <option value="seller">ผู้ขาย</option>
  </select><br><br>

  <input name="username" placeholder="ชื่อผู้ใช้"><br><br>
  <input type="password" name="password" placeholder="รหัสผ่าน"><br><br>

  <button name="submit">สมัคร</button>
</form>

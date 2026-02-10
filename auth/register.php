<?php
session_start();
include "../config/db.php"; // เชื่อมต่อฐานข้อมูล XAMPP

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $role = $_POST['role'] ?? '';
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if (!$role || !$username || !$password) {
        $message = 'กรุณากรอกข้อมูลให้ครบ';
    } else {
        // ตรวจสอบว่าชื่อผู้ใช้ซ้ำ
        $check = mysqli_query($conn, "SELECT id FROM users WHERE username='".mysqli_real_escape_string($conn,$username)."'");
        if (mysqli_num_rows($check) > 0) {
            $message = 'ชื่อผู้ใช้นี้มีอยู่แล้ว';
        } else {
            $hash = password_hash($password, PASSWORD_BCRYPT);
            $insert = mysqli_query($conn, "INSERT INTO users (username, password, role) VALUES ('$username', '$hash', '$role')");
            if ($insert) {
                $message = 'สมัครสมาชิกเรียบร้อย';
            } else {
                $message = 'เกิดข้อผิดพลาดในการบันทึกข้อมูล';
            }
        }
    }

    // คืนค่า JSON สำหรับ AJAX
    if(isset($_POST['ajax'])){
        echo json_encode(['message'=>$message]);
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="th">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>สมัครสมาชิก</title>
<style>
*{box-sizing:border-box;}
body{
    margin:0;
    height:100vh;
    display:flex;
    justify-content:center;
    align-items:center;
    background:linear-gradient(135deg,#e0f2fe,#f8fafc);
    font-family: Arial, Helvetica, sans-serif;
}
.register-container{
    background:#fff;
    padding:40px 32px;
    border-radius:20px;
    width:360px;
    box-shadow:0 20px 40px rgba(0,0,0,.1);
    text-align:center;
}
.register-container h2{
    color:#0ea5e9;
    margin-bottom:24px;
    font-weight:bold;
    letter-spacing:2px;
}
.register-container input,
.register-container select{
    width:100%;
    padding:14px;
    margin-bottom:16px;
    border-radius:12px;
    border:1px solid #cbd5f5;
    font-size:15px;
}
.register-container input:focus,
.register-container select:focus{
    outline:none;
    border-color:#0ea5e9;
}
.register-container button{
    width:100%;
    padding:14px;
    border:none;
    border-radius:12px;
    background:#0ea5e9;
    color:#fff;
    font-size:16px;
    font-weight:bold;
    cursor:pointer;
    transition:.3s;
}
.register-container button:hover{
    background:#0284c7;
}
.register-container .login-link{
    margin-top:16px;
    display:block;
    font-size:14px;
    color:#0ea5e9;
    text-decoration:none;
    transition:.3s;
}
.register-container .login-link:hover{
    text-decoration:underline;
}
</style>
</head>
<body>

<div class="register-container">
    <h2>สมัครสมาชิก</h2>
    <form id="registerForm" method="post">
        <select name="role" required>
            <option value="customer">ลูกค้า</option>
            <option value="seller">ผู้ขาย</option>
        </select>

        <input name="username" placeholder="ชื่อผู้ใช้" required>
        <input type="password" name="password" placeholder="รหัสผ่าน" required>

        <button type="submit">สมัคร</button>
    </form>
    <a href="login.php" class="login-link">เข้าสู่ระบบ</a>
</div>

<script>
const form = document.getElementById("registerForm");

form.addEventListener("submit", function(e){
    e.preventDefault();

    const formData = new FormData(form);
    formData.append('ajax', 1); // บอก PHP ว่าเป็น AJAX

    fetch("register.php", {
        method: "POST",
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        alert(data.message); // แสดงข้อความ
        if(data.message === "สมัครสมาชิกเรียบร้อย"){
            window.location.href = "login.php"; // ไปหน้า login
        }
    })
    .catch(err => alert("เกิดข้อผิดพลาด: "+err));
});
</script>

</body>
</html>

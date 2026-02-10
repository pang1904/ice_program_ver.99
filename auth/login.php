<?php
session_start();
include "../config/db.php";

$error = "";

if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $q = mysqli_query($conn, "SELECT * FROM users WHERE username='$username'");
    $user = mysqli_fetch_assoc($q);

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user'] = $user;

        if ($user['role'] == "customer") {
            header("Location: ../customer/index.php");
            exit;
        } else if ($user['role'] == "seller") {
            header("Location: ../seller/dashboard.php");
            exit;
        }
    } else {
        $error = "ชื่อผู้ใช้หรือรหัสผ่านไม่ถูกต้อง";
    }
}
?>
<!DOCTYPE html>
<html lang="th">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Login</title>

<style>
*{box-sizing:border-box}
body{
    margin:0;
    height:100vh;
    background:#f8fafc;
    font-family: Arial, Helvetica, sans-serif;
    display:flex;
    justify-content:center;
    align-items:center;
    overflow:hidden;
    color:#020617;
}

/* ===== INTRO ===== */
.intro{
    position:absolute;
    inset:0;
    display:flex;
    justify-content:center;
    align-items:center;
    background:#f8fafc;
    z-index:10;
    transition:opacity 1.2s cubic-bezier(.4,0,.2,1);
}
.intro.hidden{opacity:0; pointer-events:none}

/* LOGO */
.logo{
    font-size:72px;
    font-weight:900;
    letter-spacing:6px;
    color:#0ea5e9;
    opacity:0;
    transform:scale(.95);
    transition:all 1.6s cubic-bezier(.4,0,.2,1);
}
.logo.active{opacity:1; transform:scale(1)}

/* TAGLINE */
.tagline{
    position:absolute;
    font-size:22px;
    color:#334155;
    opacity:0;
    transition:all 1.2s cubic-bezier(.4,0,.2,1);
}
.tagline.show{opacity:1}
.tagline.hide{opacity:0}

/* LOGIN */
.login{
    width:360px;
    background:#ffffff;
    padding:32px;
    border-radius:16px;
    box-shadow:0 20px 40px rgba(0,0,0,.08);
    opacity:0;
    transform:translateY(25px);
    transition:all 1.4s cubic-bezier(.4,0,.2,1);
}
.login.show{opacity:1; transform:translateY(0)}

.login h1{
    text-align:center;
    margin-bottom:24px;
    color:#0ea5e9;
    letter-spacing:3px;
}

input{
    width:100%;
    padding:14px;
    margin-bottom:16px;
    border-radius:10px;
    border:1px solid #cbd5f5;
    font-size:15px;
}

input:focus{
    outline:none;
    border-color:#0ea5e9;
}

/* BUTTONS */
button{
    width:100%;
    padding:14px;
    border:none;
    border-radius:10px;
    font-size:16px;
    cursor:pointer;
}

.login-btn{
    background:#0ea5e9;
    color:#fff;
}
.login-btn:hover{background:#0284c7}

.register-btn{
    margin-top:12px;
    background:#fff;
    border:1px solid #0ea5e9;
    color:#0ea5e9;
}
.register-btn:hover{
    background:#e0f2fe;
}

/* POPUP ERROR */
.modal{
    position:fixed;
    inset:0;
    background:rgba(0,0,0,.4);
    display:flex;
    justify-content:center;
    align-items:center;
}
.modal-box{
    background:#fff;
    padding:24px 28px;
    border-radius:14px;
    width:300px;
    text-align:center;
    animation:popup .3s ease;
}
.modal-box h3{color:#ef4444}
.modal-box button{
    margin-top:16px;
    background:#0ea5e9;
    color:#fff;
}

@keyframes popup{
    from{transform:scale(.9); opacity:0}
    to{transform:scale(1); opacity:1}
}
.popup{
    position:fixed;
    inset:0;
    background:rgba(0,0,0,.4);
    display:flex;
    justify-content:center;
    align-items:center;
    opacity:0;
    pointer-events:none;
    transition:.4s;
    z-index:20;
}

.popup.show{
    opacity:1;
    pointer-events:auto;
}

.popup-box{
    background:#fff;
    color:#020617;
    padding:30px;
    width:320px;
    border-radius:12px;
    text-align:center;
    box-shadow:0 20px 60px rgba(0,0,0,.3);
}

.popup-box h3{
    color:#0ea5e9;
    margin-bottom:10px;
}

.popup-box button{
    margin-top:15px;
    padding:10px 20px;
    border:none;
    background:#0ea5e9;
    color:#fff;
    cursor:pointer;
    border-radius:6px;
}

</style>
</head>

<body>

<!-- INTRO -->
<div class="intro" id="intro">
    <div class="logo" id="logo">ICEMARKET</div>
</div>

<!-- TAGLINE -->
<div class="tagline" id="tagline">Welcome! Check out our amazing products.</div>

<!-- LOGIN -->
<div class="login" id="login">
    <form method="post">
        <h1>ICEMARKET</h1>
        <input name="username" placeholder="ชื่อผู้ใช้" required>
        <input type="password" name="password" placeholder="รหัสผ่าน" required>

        <button class="login-btn" name="login">เข้าสู่ระบบ</button>
        <button type="button" class="register-btn"
            onclick="location.href='register.php'">
            สมัครสมาชิก
        </button>
    </form>
</div>

<?php if ($error): ?>
<!-- ERROR POPUP -->
<div class="popup" id="popup">
    <div class="popup-box">
        <h3>เข้าสู่ระบบไม่สำเร็จ</h3>
        <p><?= $error ?></p>
        <button onclick="closePopup()">ตกลง</button>
    </div>
</div>
<?php endif; ?>

<script>
const logo = document.getElementById("logo");
const intro = document.getElementById("intro");
const tagline = document.getElementById("tagline");
const login = document.getElementById("login");

setTimeout(()=>logo.classList.add("active"),300);
setTimeout(()=>intro.classList.add("hidden"),3000);
setTimeout(()=>tagline.classList.add("show"),3300);
setTimeout(()=>tagline.classList.add("hide"),5000);
setTimeout(()=>login.classList.add("show"),5600);
ผ
const popup = document.getElementById("popup");

function closePopup(){
    popup.classList.remove("show");
}

<?php if(!empty($error)): ?>
    setTimeout(()=>{
        popup.classList.add("show");
    }, 7200); // ให้ขึ้นหลัง animation login โชว์
<?php endif; ?>
</script>

</script>

</body>
</html>


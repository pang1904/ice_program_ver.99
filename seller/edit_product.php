<?php
session_start();
include "../config/db.php";

$id = $_GET['id'];

$p = mysqli_fetch_assoc(
    mysqli_query($conn,"SELECT * FROM products WHERE id=$id")
);

$message = "";

if (isset($_POST['update'])) {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $price = floatval($_POST['price']);
    $stock = intval($_POST['stock']);

    mysqli_query($conn,
        "UPDATE products
         SET name='$name', price=$price, stock=$stock
         WHERE id=$id"
    );
    $message = "✅ แก้ไขสินค้าสำเร็จ!";
}

if (isset($_POST['delete'])) {
    mysqli_query($conn,"DELETE FROM products WHERE id=$id");
    header("Location: dashboard.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="th">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>แก้ไขสินค้า</title>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
<style>
body{
    font-family: 'Inter', sans-serif;
    background: linear-gradient(135deg,#e0f2fe,#f8fafc);
    padding:30px;
    color:#333;
}

h2{
    color:#0ea5e9;
    text-align:center;
    margin-bottom:20px;
    font-weight:700;
    font-size:28px;
}

.container{
    max-width:500px;
    margin:auto;
    background:#fff;
    padding:30px;
    border-radius:16px;
    box-shadow:0 20px 40px rgba(0,0,0,0.1);
}

input, button{
    width:100%;
    padding:12px 14px;
    margin-bottom:20px;
    border-radius:10px;
    border:1px solid #cbd5f5;
    font-size:15px;
    box-sizing:border-box;
}

input:focus{
    outline:none;
    border-color:#0ea5e9;
}

button{
    font-weight:600;
    cursor:pointer;
    border:none;
    transition:all 0.3s;
}

button.update{
    background:#0ea5e9;
    color:#fff;
}

button.update:hover{
    background:#0284c7;
    transform: translateY(-2px);
}

button.delete{
    background:#ef4444;
    color:#fff;
}

button.delete:hover{
    background:#dc2626;
    transform: translateY(-2px);
}

.back-link{
    display:inline-block;
    margin-bottom:20px;
    color:#0ea5e9;
    text-decoration:none;
    font-weight:600;
}

.back-link:hover{
    text-decoration:underline;
}

.message{
    background:#d1fae5;
    color:#065f46;
    padding:10px 15px;
    border-radius:8px;
    margin-bottom:20px;
    font-weight:600;
    text-align:center;
}
</style>
</head>
<body>

<a href="dashboard.php" class="back-link">⬅ กลับไปหน้าหลัก</a>
<h2>แก้ไขสินค้า</h2>

<div class="container">

<?php if($message): ?>
<div class="message"><?= $message ?></div>
<?php endif; ?>

<form method="post">
    ชื่อสินค้า
    <input name="name" value="<?= htmlspecialchars($p['name']) ?>" required>

    ราคา
    <input name="price" type="number" step="0.01" value="<?= $p['price'] ?>" required>

    จำนวน
    <input name="stock" type="number" value="<?= $p['stock'] ?>" required>

    <button name="update" class="update">💾 บันทึก</button>
    <button name="delete" class="delete" onclick="return confirm('ลบจริงไหม?')">🗑 ลบ</button>
</form>

</div>
</body>
</html>

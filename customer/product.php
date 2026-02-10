<?php
session_start();
include "../config/db.php";

if (!isset($_SESSION['user'])) {
    header("Location: ../auth/login.php");
    exit;
}

if (!isset($_GET['id'])) {
    echo "ไม่พบสินค้า";
    exit;
}

$id = (int)$_GET['id'];

$p = mysqli_fetch_assoc(
    mysqli_query($conn,"SELECT * FROM products WHERE id=$id")
);

$imgs = mysqli_query($conn,
    "SELECT * FROM product_images WHERE product_id=$id ORDER BY id ASC"
);
?>
<!DOCTYPE html>
<html lang="th">
<head>
<meta charset="UTF-8">
<title><?= htmlspecialchars($p['name']) ?></title>

<style>
:root{
    --bg:#f4f4f4;
    --card:#fff;
    --text:#222;
    --muted:#777;
    --border:#e0e0e0;
    --accent:#111;
}

body{
    margin:0;
    font-family: system-ui, -apple-system;
    background:var(--bg);
    color:var(--text);
}

a{
    text-decoration:none;
    color:inherit;
}

/* ===== LAYOUT ===== */
.container{
    max-width:1100px;
    margin:auto;
    padding:30px 20px;
}

.product-box{
    background:var(--card);
    border-radius:18px;
    border:1px solid var(--border);
    padding:30px;
    display:grid;
    grid-template-columns:1fr 1fr;
    gap:40px;
}

/* ===== IMAGES ===== */
.main-image{
    width:100%;
    height:360px;
    object-fit:cover;
    border-radius:14px;
    border:1px solid var(--border);
}

.thumb-list{
    margin-top:15px;
    display:flex;
    gap:10px;
}

.thumb-list img{
    width:70px;
    height:70px;
    object-fit:cover;
    border-radius:10px;
    border:1px solid var(--border);
}

/* ===== INFO ===== */
h1{
    margin:0;
    font-size:28px;
}

.price{
    font-size:22px;
    font-weight:700;
    margin:15px 0;
}

.stock{
    font-size:14px;
    color:var(--muted);
}

.desc{
    margin:20px 0;
    line-height:1.7;
}

/* ===== ACTIONS ===== */
.actions{
    margin-top:25px;
    display:flex;
    gap:15px;
}

.btn{
    padding:12px 30px;
    border-radius:30px;
    border:1px solid var(--border);
    background:#fff;
    cursor:pointer;
    font-size:15px;
}

.btn.buy{
    background:var(--accent);
    color:#fff;
    border-color:var(--accent);
}

.btn:hover{
    opacity:.9;
}

.soldout{
    color:#999;
    font-weight:600;
    margin-top:20px;
}
</style>
</head>

<body>

<div class="container">

<a href="index.php">← กลับไปหน้าร้าน</a>

<div class="product-box">

<!-- LEFT : IMAGES -->
<div>
<?php
$firstImg = mysqli_fetch_assoc($imgs);
if ($firstImg):
?>
    <img class="main-image" src="../assets/images/products/<?= $firstImg['image'] ?>">
<?php else: ?>
    <img class="main-image" src="../assets/images/no-image.png">
<?php endif; ?>

<div class="thumb-list">
<?php
if ($firstImg):
    echo '<img src="../assets/images/products/'.$firstImg['image'].'">';
endif;

mysqli_data_seek($imgs, 1);
while($img = mysqli_fetch_assoc($imgs)):
?>
    <img src="../assets/images/products/<?= $img['image'] ?>">
<?php endwhile; ?>
</div>
</div>

<!-- RIGHT : INFO -->
<div>
    <h1><?= htmlspecialchars($p['name']) ?></h1>

    <div class="price">฿<?= number_format($p['price']) ?></div>
    <div class="stock">คงเหลือ <?= $p['stock'] ?> ชิ้น</div>

    <div class="desc">
        <?= nl2br(htmlspecialchars($p['description'])) ?>
    </div>

    <?php if ($p['stock'] > 0): ?>
        <div class="actions">
            <a class="btn buy" href="add_to_cart.php?id=<?= $p['id'] ?>">
                ใส่ตะกร้า
            </a>
        </div>
    <?php else: ?>
        <div class="soldout">❌ สินค้าหมด</div>
    <?php endif; ?>

</div>

</div>

</div>

</body>
</html>

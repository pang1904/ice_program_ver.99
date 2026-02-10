<?php
session_start();
include "../config/db.php";

if (!isset($_SESSION['user'])) {
    header("Location: ../auth/login.php");
    exit;
}

$uid = $_SESSION['user']['id'];

$sql = "
SELECT c.*, p.name, p.price,
(
    SELECT pi.image 
    FROM product_images pi 
    WHERE pi.product_id = p.id 
    ORDER BY pi.id ASC 
    LIMIT 1
) AS image
FROM carts c
JOIN products p ON c.product_id = p.id
WHERE c.customer_id = $uid
";

$q = mysqli_query($conn, $sql);
?>
<!DOCTYPE html>
<html lang="th">
<head>
<meta charset="UTF-8">
<title>ตะกร้าสินค้า</title>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<style>
:root{
    --bg:#f4f6f8;
    --card:#ffffff;
    --text:#222;
    --muted:#555;
    --border:#e0e0e0;
    --accent:#4dabf7;
    --accent-dark:#1c7ed6;
    --shadow:0 8px 20px rgba(0,0,0,0.08);
}

body{
    margin:0;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    background:var(--bg);
    color:var(--text);
}

a{ text-decoration:none; color:inherit; }

.container{
    max-width:900px;
    margin:auto;
    padding:30px 20px;
}

h1{
    margin-bottom:20px;
    font-size:28px;
    color:var(--accent-dark);
    display:flex;
    align-items:center;
    gap:10px;
}

.cart-box{
    background:var(--card);
    border-radius:20px;
    border:1px solid var(--border);
    padding:20px;
    box-shadow: var(--shadow);
}

.cart-item{
    display:grid;
    grid-template-columns:90px 1fr 120px;
    gap:20px;
    padding:15px 0;
    border-bottom:1px solid var(--border);
    align-items:center;
    transition: transform 0.3s, box-shadow 0.3s;
}

.cart-item:hover{
    transform: translateY(-2px);
    box-shadow:0 8px 20px rgba(0,0,0,0.06);
}

.cart-item:last-child{
    border-bottom:none;
}

.cart-item img{
    width:90px;
    height:90px;
    object-fit:cover;
    border-radius:12px;
    border:1px solid var(--border);
    transition: transform 0.3s;
}

.cart-item img:hover{
    transform: scale(1.05);
}

.item-name{
    font-weight:600;
    margin-bottom:6px;
    font-size:16px;
}

.item-meta{
    font-size:14px;
    color:var(--muted);
    line-height:1.4;
}

.item-price{
    text-align:right;
    font-weight:700;
    font-size:16px;
    color:var(--accent-dark);
}

.total-box{
    margin-top:20px;
    display:flex;
    justify-content:space-between;
    align-items:center;
    flex-wrap:wrap;
    gap:10px;
}

.total{
    font-size:22px;
    font-weight:700;
    color:var(--accent-dark);
}

.btn{
    padding:12px 35px;
    border-radius:30px;
    border:1px solid var(--border);
    background:#fff;
    cursor:pointer;
    font-weight:600;
    transition: all 0.3s;
}

.btn:hover{
    transform: translateY(-2px);
    box-shadow:0 6px 15px rgba(0,0,0,0.1);
}

.btn.checkout{
    background:var(--accent-dark);
    color:#fff;
    border-color:var(--accent-dark);
}

.btn.checkout:hover{
    background:var(--accent);
}

.empty{
    text-align:center;
    color:var(--muted);
    padding:60px 0;
    font-size:18px;
}

@media(max-width:600px){
    .cart-item{
        grid-template-columns: 80px 1fr 100px;
    }
    .total-box{
        flex-direction:column;
        align-items:flex-start;
    }
}
</style>
</head>

<body>

<div class="container">

<a href="index.php" style="display:inline-block; margin-bottom:20px; color:var(--accent-dark);"><i class="fa-solid fa-arrow-left"></i> กลับไปหน้าร้าน</a>

<h1>🛒 ตะกร้าสินค้า</h1>

<div class="cart-box">

<?php
$total = 0;
if (mysqli_num_rows($q) == 0):
?>
    <div class="empty">
        ตะกร้าของคุณยังว่างอยู่
    </div>
<?php
else:
while ($row = mysqli_fetch_assoc($q)):
    $sum = $row['price'] * $row['quantity'];
    $total += $sum;
?>
<div class="cart-item">

    <!-- IMAGE -->
    <div>
        <?php if ($row['image']): ?>
            <img src="../assets/images/products/<?= $row['image'] ?>">
        <?php else: ?>
            <img src="../assets/images/no-image.png">
        <?php endif; ?>
    </div>

    <!-- INFO -->
    <div>
        <div class="item-name"><?= htmlspecialchars($row['name']) ?></div>
        <div class="item-meta">
            ราคา <?= number_format($row['price']) ?> บาท <br>
            จำนวน <?= $row['quantity'] ?> ชิ้น
        </div>
    </div>

    <!-- PRICE -->
    <div class="item-price">
        <?= number_format($sum) ?> บาท
    </div>

</div>
<?php endwhile; ?>

<div class="total-box">
    <div class="total">
        รวมทั้งหมด <?= number_format($total) ?> บาท
    </div>
    <a class="btn checkout" href="checkout.php">
        ชำระเงิน
    </a>
</div>


<?php endif; ?>

</div>
</div>

</body>
</html>

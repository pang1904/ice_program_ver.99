<?php
session_start();
include "../config/db.php";

/* ========================
   รับค่าจากฟอร์ม
======================== */
$search   = $_GET['search'] ?? '';
$category = $_GET['category'] ?? '';

$today = date('Y-m-d');

/* ========================
   SQL เงื่อนไข
======================== */
$where = "
WHERE
    p.sale_price IS NOT NULL
    AND p.sale_price < p.price
    AND p.sale_start <= '$today'
    AND p.sale_end >= '$today'
";

if ($search != '') {
    $safe = mysqli_real_escape_string($conn, $search);
    $where .= " AND p.name LIKE '%$safe%'";
}


/* ========================
   ดึงสินค้า
======================== */
$sql = "
SELECT p.*,
(
    SELECT pi.image
    FROM product_images pi
    WHERE pi.product_id = p.id
    ORDER BY pi.id ASC
    LIMIT 1
) AS image
FROM products p
$where
ORDER BY p.sale_price ASC
";

$q = mysqli_query($conn, $sql);

/* ========================
   หมวดหมู่
======================== */

?>
<!DOCTYPE html>
<html lang="th">
<head>
<meta charset="UTF-8">
<title>สินค้าลดราคา | ICEMARKET</title>

<style>
body{
    font-family:system-ui;
    background:#f9fafe;
    margin:0;
}

.container{
    max-width:1200px;
    margin:auto;
    padding:40px 20px;
}

h1{
    margin-bottom:20px;
    color:#1c7ed6;
}

/* ===== FILTER ===== */
.filter-box{
    background:#fff;
    padding:20px;
    border-radius:16px;
    display:flex;
    gap:15px;
    margin-bottom:30px;
    box-shadow:0 6px 20px rgba(0,0,0,.05);
}

.filter-box input,
.filter-box select{
    padding:12px 15px;
    border-radius:10px;
    border:1px solid #ddd;
    font-size:15px;
}

.filter-box button{
    padding:12px 30px;
    border:none;
    border-radius:30px;
    background:#1c7ed6;
    color:#fff;
    cursor:pointer;
}

/* ===== PRODUCTS ===== */
.products{
    display:grid;
    grid-template-columns:repeat(auto-fill,minmax(250px,1fr));
    gap:30px;
}

.card{
    background:#fff;
    border-radius:18px;
    overflow:hidden;
    position:relative;
    box-shadow:0 10px 30px rgba(0,0,0,.06);
    transition:.3s;
}

.card:hover{
    transform:translateY(-6px);
}

.card img{
    width:100%;
    height:200px;
    object-fit:cover;
}

.badge{
    position:absolute;
    top:15px;
    left:15px;
    background:#e03131;
    color:#fff;
    padding:6px 14px;
    border-radius:20px;
    font-size:13px;
    font-weight:700;
}

.card-body{
    padding:20px;
}

.card h4{
    margin:0;
    font-size:16px;
}

.price{
    margin-top:10px;
    display:flex;
    gap:10px;
    align-items:center;
}

.price .old{
    text-decoration:line-through;
    color:#999;
    font-size:14px;
}

.price .sale{
    color:#e03131;
    font-size:18px;
    font-weight:700;
}

.empty{
    text-align:center;
    color:#777;
    margin-top:50px;
}
</style>
</head>

<body>

<div class="container">
<a href="index.php" class="back-link"><i class="fa-solid fa-arrow-left"></i> กลับไปหน้าหลัก</a>
<h1>สินค้าลดราคา</h1>

<form class="filter-box" method="get">
    <input type="text" name="search" placeholder="ค้นหาสินค้า"
           value="<?= htmlspecialchars($search) ?>">

    <select name="category">
        <option value="">ทุกหมวดหมู่</option>
        <?php while($c = mysqli_fetch_assoc($catQ)): ?>
            <option value="<?= $c['category'] ?>"
                <?= ($category == $c['category'])?'selected':'' ?>>
                <?= $c['category'] ?>
            </option>
        <?php endwhile; ?>
    </select>

    <button>ค้นหา</button>
</form>

<div class="products">
<?php if(mysqli_num_rows($q)==0): ?>
    <div class="empty">ไม่พบสินค้าลดราคา</div>
<?php endif; ?>

<?php while($p = mysqli_fetch_assoc($q)): ?>
<div class="card">

    <div class="badge">SALE</div>

    <img src="../assets/images/products/<?= $p['image'] ?: 'no-image.png' ?>">

    <div class="card-body">
        <h4><?= htmlspecialchars($p['name']) ?></h4>

        <div class="price">
            <span class="old">฿<?= number_format($p['price']) ?></span>
            <span class="sale">฿<?= number_format($p['sale_price']) ?></span>
        </div>
    </div>

</div>
<?php endwhile; ?>
</div>

</div>

</body>
</html>

<?php
session_start();
include "../config/db.php";

$sid = $_SESSION['user']['id'];

$q = mysqli_query($conn,
    "SELECT * FROM products WHERE seller_id=$sid"
);
?>

<h2>แดชบอร์ดผู้ขาย</h2>

<nav class="seller-nav">
  <a href="add_product.php" class="btn-nav">เพิ่มสินค้า</a>
  <a href="orders.php" class="btn-nav">คำสั่งซื้อ</a>
  <a href="../auth/logout.php" class="btn-nav logout">ออกจากระบบ</a>
</nav>

<hr>

<h3>สินค้าของคุณ</h3>

<div class="product-list">
<?php while($p = mysqli_fetch_assoc($q)): ?>
  <div class="product-card">
    <div class="product-info">
      <strong><?= htmlspecialchars($p['name']) ?></strong>
      <span><?= number_format($p['price']) ?> บาท</span>
    </div>
    <div class="product-actions">
      <a href="edit_product.php?id=<?= $p['id'] ?>" class="btn-edit">แก้ไข</a>
    </div>
  </div>
<?php endwhile; ?>
</div>

<a href="../customer/index.php" class="btn-back">ไปยังหน้าลูกค้า</a>

<style>
body{
    font-family: Arial, Helvetica, sans-serif;
    background:#f4f6f8;
    color:#222;
    padding:20px;
}

h2{
    color:#0ea5e9;
    margin-bottom:10px;
}

nav.seller-nav{
    margin-bottom:20px;
}

nav.seller-nav .btn-nav{
    text-decoration:none;
    color:#fff;
    background:#0ea5e9;
    padding:10px 16px;
    border-radius:8px;
    margin-right:10px;
    transition:.3s;
}

nav.seller-nav .btn-nav:hover{
    background:#0284c7;
}

nav.seller-nav .logout{
    background:#ef4444;
}

nav.seller-nav .logout:hover{
    background:#dc2626;
}

hr{
    margin:20px 0;
    border:none;
    border-top:1px solid #ccc;
}

.product-list{
    display:grid;
    grid-template-columns:repeat(auto-fit, minmax(240px, 1fr));
    gap:15px;
}

.product-card{
    background:#fff;
    padding:15px 20px;
    border-radius:12px;
    box-shadow:0 4px 12px rgba(0,0,0,0.08);
    display:flex;
    justify-content:space-between;
    align-items:center;
    transition:.3s;
}

.product-card:hover{
    transform:translateY(-2px);
    box-shadow:0 6px 18px rgba(0,0,0,0.12);
}

.product-info strong{
    display:block;
    font-size:16px;
    margin-bottom:4px;
}

.product-info span{
    color:#555;
    font-size:14px;
}

.product-actions .btn-edit{
    text-decoration:none;
    padding:6px 12px;
    background:#4dabf7;
    color:#fff;
    border-radius:8px;
    font-size:14px;
    transition:.3s;
}

.product-actions .btn-edit:hover{
    background:#1c7ed6;
}

.btn-back{
    display:inline-block;
    margin-top:20px;
    padding:10px 16px;
    background:#fbbf24;
    color:#fff;
    border-radius:8px;
    text-decoration:none;
    transition:.3s;
}

.btn-back:hover{
    background:#f59e0b;
}
</style>


    
<?php
session_start();
include "../config/db.php";

if (!isset($_SESSION['user'])) {
    header("Location: ../auth/login.php");
    exit;
}
?>

<h2>ยินดีต้อนรับ <?= $_SESSION['user']['username']; ?></h2>

<nav>
  <a href="#">หน้าแรก</a> |
  <a href="#">สินค้าทั้งหมด</a> |
  <a href="#">มาใหม่</a> |
  <a href="#">ลดราคา</a> |
  <a href="cart.php">ตะกร้าสินค้า</a> |
  <a href="orders.php">คำสั่งซื้อของฉัน</a> |
  <a href="../auth/logout.php">ออกจากระบบ</a>
</nav>

<hr>

<h3>🔥 ป้ายโฆษณา</h3>
<p>ลดสูงสุด 50% วันนี้เท่านั้น</p>
<button>ช้อปเลย</button>

<hr>

<h3>🆕 สินค้ามาใหม่</h3>

<?php
$q = mysqli_query($conn,"
    SELECT 
        p.*,
        (
            SELECT pi.image 
            FROM product_images pi 
            WHERE pi.product_id = p.id 
            ORDER BY pi.id ASC 
            LIMIT 1
        ) AS image
    FROM products p
    ORDER BY p.id DESC
    LIMIT 6
");

while ($p = mysqli_fetch_assoc($q)):
?>

<div style="border:1px solid #ccc; padding:10px; margin:10px; width:220px; display:inline-block;">

    <?php if ($p['image']): ?>
        <img src="../assets/images/products/<?= $p['image'] ?>" width="200" height="150" style="object-fit:cover;">
    <?php else: ?>
        <img src="../assets/images/no-image.png" width="200" height="150">
    <?php endif; ?>

    <h4><?= $p['name'] ?></h4>
    <p>ราคา <?= number_format($p['price']) ?> บาท</p>

    <?php if ($p['stock'] > 0): ?>
        <p style="color:green;">คงเหลือ <?= $p['stock'] ?> ชิ้น</p>
        <a href="product.php?id=<?= $p['id'] ?>">ดูรายละเอียด</a> |
        <a href="add_to_cart.php?id=<?= $p['id'] ?>">ใส่ตะกร้า</a>
    <?php else: ?>
        <p style="color:red; font-weight:bold;">❌ สินค้าหมดแล้ว</p>
        <span style="color:#999;">ไม่สามารถสั่งซื้อได้</span>
    <?php endif; ?>

</div>

<?php endwhile; ?>
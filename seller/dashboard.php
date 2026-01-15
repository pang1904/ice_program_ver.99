<?php
session_start();
include "../config/db.php";

$sid = $_SESSION['user']['id'];

$q = mysqli_query($conn,
    "SELECT * FROM products WHERE seller_id=$sid"
);
?>

<h2>แดชบอร์ดผู้ขาย</h2>

<nav>
  <a href="add_product.php">เพิ่มสินค้า</a> |
  <a href="orders.php">คำสั่งซื้อ</a> |
  <a href="../auth/logout.php">ออกจากระบบ</a>
</nav>

<hr>

<h3>สินค้าของคุณ</h3>

<?php while($p = mysqli_fetch_assoc($q)): ?>
  <div>
    <?= $p['name'] ?> | <?= $p['price'] ?> บาท
    <a href="edit_product.php?id=<?= $p['id'] ?>">แก้ไข</a>
  </div>
<?php endwhile; ?>
<a href="../customer/index.php">ไปยังหน้าลูกค้า</a>

    
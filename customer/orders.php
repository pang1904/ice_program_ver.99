<?php
session_start();
include "../config/db.php";

$uid = $_SESSION['user']['id'];

$q = mysqli_query($conn, "
    SELECT * FROM orders
    WHERE customer_id = $uid
    ORDER BY id DESC
");
?>

<h2>ออเดอร์ของฉัน</h2>

<?php while ($o = mysqli_fetch_assoc($q)): ?>
<div style="border:1px solid #ccc; padding:10px; margin:10px;">
    <p>ออเดอร์ #<?= $o['id'] ?></p>
    <p>ยอดรวม: <?= $o['total_price'] ?> บาท</p>
    <p>สถานะ: <?= $o['status'] ?></p>

    <?php if ($o['tracking_number']): ?>
        <p>เลขพัสดุ: <?= $o['tracking_number'] ?></p>
    <?php endif; ?>

    <?php if ($o['status'] == 'จัดส่งแล้ว'): ?>
    <a href="confirm.php?id=<?= $o['id'] ?>">ยืนยันรับสินค้า</a>
<?php endif; ?>
</div>
<?php endwhile; ?>
<a href="index.php">กลับไปยังหน้าหลัก</a>
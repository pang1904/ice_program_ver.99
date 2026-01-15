<?php
session_start();
include "../config/db.php";

/* =========================
   บันทึกการจัดส่ง + เลขพัสดุ
========================= */
if (isset($_POST['ship'])) {
    $order_id = $_POST['order_id'];
    $tracking = mysqli_real_escape_string($conn, $_POST['tracking']);

    mysqli_query($conn, "
        UPDATE orders 
        SET 
            tracking_number = '$tracking',
            status = 'จัดส่งแล้ว'
        WHERE id = $order_id
    ");

    echo "<script>alert('บันทึกการจัดส่งเรียบร้อย');</script>";
}

/* =========================
   ดึงข้อมูลออเดอร์
========================= */
$q = mysqli_query($conn, "
    SELECT 
        o.id AS order_id,
        o.total_price,
        o.status,
        o.created_at,
        o.tracking_number,
        u.username,
        u.address,
        p.name AS product_name,
        oi.quantity
    FROM orders o
    JOIN users u ON o.customer_id = u.id
    JOIN order_items oi ON o.id = oi.order_id
    JOIN products p ON oi.product_id = p.id
    ORDER BY o.id DESC
");

$orders = [];

while ($row = mysqli_fetch_assoc($q)) {
    $orders[$row['order_id']]['info'] = [
        'username' => $row['username'],
        'address' => $row['address'],
        'total_price' => $row['total_price'],
        'status' => $row['status'],
        'created_at' => $row['created_at'],
        'tracking_number' => $row['tracking_number']
    ];

    $orders[$row['order_id']]['items'][] = [
        'name' => $row['product_name'],
        'quantity' => $row['quantity']
    ];
}
?>

<h2>📦 รายการสั่งซื้อจากลูกค้า</h2>

<?php foreach ($orders as $order_id => $order): ?>
<div style="border:1px solid #ccc; padding:15px; margin:15px;">

    <p><b>ออเดอร์ #<?= $order_id ?></b></p>
    <p>👤 ลูกค้า: <?= $order['info']['username'] ?></p>
    <p>🏠 ที่อยู่จัดส่ง: <?= $order['info']['address'] ?></p>

    <p><b>🛒 รายการสินค้า:</b></p>
    <ul>
        <?php foreach ($order['items'] as $item): ?>
            <li><?= $item['name'] ?> × <?= $item['quantity'] ?></li>
        <?php endforeach; ?>
    </ul>

    <p>💰 ยอดรวม: <?= $order['info']['total_price'] ?> บาท</p>
    <p>📌 สถานะ: <b><?= $order['info']['status'] ?></b></p>
    <p>🕒 วันที่สั่งซื้อ: <?= $order['info']['created_at'] ?></p>

    <!-- ถ้ายังไม่ส่ง -->
    <?php if ($order['info']['status'] == 'ชำระแล้ว'): ?>
        <form method="post">
            <input type="hidden" name="order_id" value="<?= $order_id ?>">
            <input 
                type="text" 
                name="tracking" 
                placeholder="กรอกเลขพัสดุ"
                required
            >
            <button name="ship">ยืนยันการจัดส่ง</button>
        </form>
    <?php endif; ?>

    <!-- ถ้าส่งแล้ว -->
    <?php if ($order['info']['status'] == 'จัดส่งแล้ว'): ?>
        <p>🚚 เลขพัสดุ: <b><?= $order['info']['tracking_number'] ?></b></p>
    <?php endif; ?>

</div>
<?php endforeach; ?>

<a href="dashboard.php">⬅ กลับหน้าหลักผู้ขาย</a>

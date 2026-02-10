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

<h2 style="text-align:center; color:#0ea5e9; margin-bottom:30px;">📦 รายการสั่งซื้อจากลูกค้า</h2>
<a href="dashboard.php" class="back-link"><i class="fa-solid fa-arrow-left"></i> กลับไปหน้าหลัก</a>
<div style="max-width:900px; margin:auto;">
<?php foreach ($orders as $order_id => $order): ?>
<div style="
    border:1px solid #ddd; 
    padding:20px; 
    margin-bottom:20px; 
    border-radius:12px; 
    box-shadow:0 8px 20px rgba(0,0,0,0.05);
    background:#fff;
">

    <p style="font-weight:700; font-size:16px; color:#0ea5e9;">ออเดอร์ #<?= $order_id ?></p>
    <p>👤 <b>ลูกค้า:</b> <?= $order['info']['username'] ?></p>
    <p>🏠 <b>ที่อยู่จัดส่ง:</b> <?= $order['info']['address'] ?></p>

    <p style="margin-top:10px; font-weight:600;">🛒 รายการสินค้า:</p>
    <ul style="margin-left:20px;">
        <?php foreach ($order['items'] as $item): ?>
            <li><?= $item['name'] ?> × <?= $item['quantity'] ?></li>
        <?php endforeach; ?>
    </ul>

    <p>💰 <b>ยอดรวม:</b> <?= number_format($order['info']['total_price'],2) ?> บาท</p>

    <?php 
    $statusColor = "#facc15"; // default: สีเหลือง
    if($order['info']['status']=="ชำระแล้ว") $statusColor="#22c55e"; // สีเขียว
    if($order['info']['status']=="จัดส่งแล้ว") $statusColor="#3b82f6"; // สีน้ำเงิน
    ?>
    <p>📌 <b>สถานะ:</b> 
        <span style="color:#fff; background:<?= $statusColor ?>; padding:4px 10px; border-radius:6px; font-weight:600;">
            <?= $order['info']['status'] ?>
        </span>
    </p>

    <p>🕒 <b>วันที่สั่งซื้อ:</b> <?= date("d/m/Y H:i", strtotime($order['info']['created_at'])) ?></p>

    <!-- ถ้ายังไม่ส่ง -->
    <?php if ($order['info']['status'] == 'ชำระแล้ว'): ?>
        <form method="post" style="margin-top:15px; display:flex; gap:10px; flex-wrap:wrap;">
            <input 
                type="hidden" 
                name="order_id" 
                value="<?= $order_id ?>"
            >
            <input 
                type="text" 
                name="tracking" 
                placeholder="กรอกเลขพัสดุ"
                required
                style="flex:1; padding:10px; border-radius:8px; border:1px solid #cbd5e1;"
            >
            <button 
                name="ship" 
                style="
                    background:#0ea5e9; 
                    color:#fff; 
                    border:none; 
                    border-radius:8px; 
                    padding:10px 20px;
                    cursor:pointer;
                    font-weight:600;
                    transition:0.3s;
                "
                onmouseover="this.style.background='#0284c7'"
                onmouseout="this.style.background='#0ea5e9'"
            >
                ยืนยันการจัดส่ง
            </button>
        </form>
    <?php endif; ?>

    <!-- ถ้าส่งแล้ว -->
    <?php if ($order['info']['status'] == 'จัดส่งแล้ว'): ?>
        <p style="margin-top:10px;">🚚 <b>เลขพัสดุ:</b> <?= $order['info']['tracking_number'] ?></p>
    <?php endif; ?>

</div>
<?php endforeach; ?>
</div>



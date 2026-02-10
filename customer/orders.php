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
<!DOCTYPE html>
<html lang="th">
<head>
<meta charset="UTF-8">
<title>ออเดอร์ของฉัน</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<style>
:root{
    --bg:#f4f6f8;
    --card:#fff;
    --text:#222;
    --muted:#555;
    --border:#e0e0e0;
    --accent:#4dabf7;
    --accent-dark:#1c7ed6;
    --shadow:0 8px 20px rgba(0,0,0,0.08);
}

body{
    margin:0;
    font-family:'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    background:var(--bg);
    color:var(--text);
}

.container{
    max-width:900px;
    margin:auto;
    padding:30px 20px;
}

h2{
    font-size:28px;
    color:var(--accent-dark);
    margin-bottom:20px;
}

.order-card{
    background:var(--card);
    border-radius:18px;
    padding:20px;
    margin-bottom:20px;
    border:1px solid var(--border);
    box-shadow:var(--shadow);
    transition: transform 0.3s, box-shadow 0.3s;
}

.order-card:hover{
    transform: translateY(-3px);
    box-shadow:0 12px 25px rgba(0,0,0,0.1);
}

.order-header{
    display:flex;
    justify-content:space-between;
    align-items:center;
    margin-bottom:10px;
}

.order-id{
    font-weight:700;
    font-size:16px;
}

.order-total{
    font-weight:600;
    color:var(--accent-dark);
}

.order-status{
    display:inline-block;
    padding:4px 12px;
    border-radius:20px;
    font-size:14px;
    color:#fff;
}

.status-pending{ background:#f59f00; }       /* รอดำเนินการ */
.status-processing{ background:#339af0; }    /* กำลังดำเนินการ */
.status-shipped{ background:#1c7ed6; }       /* จัดส่งแล้ว */
.status-delivered{ background:#37b24d; }     /* รับสินค้าแล้ว */
.status-cancelled{ background:#f03e3e; }     /* ยกเลิก */

.order-tracking{
    font-size:14px;
    color:var(--muted);
    margin-top:5px;
}

.btn{
    display:inline-block;
    margin-top:12px;
    padding:10px 25px;
    border-radius:30px;
    text-decoration:none;
    font-weight:600;
    transition: all 0.3s;
}

.btn.confirm{
    background:var(--accent-dark);
    color:#fff;
    border:none;
}

.btn.confirm:hover{
    background:var(--accent);
    transform:translateY(-2px);
    box-shadow:0 6px 15px rgba(0,0,0,0.1);
}

.back-link{
    display:inline-block;
    margin-top:20px;
    color:var(--accent-dark);
    text-decoration:none;
}

.back-link i{ margin-right:5px; }

@media(max-width:600px){
    .order-header{
        flex-direction:column;
        align-items:flex-start;
        gap:5px;
    }
}
</style>
</head>

<body>

<div class="container">
    <a href="index.php" class="back-link"><i class="fa-solid fa-arrow-left"></i> กลับไปหน้าหลัก</a>
    <h2><i class="fa-solid fa-box"></i> ออเดอร์ของฉัน</h2>

    <?php while ($o = mysqli_fetch_assoc($q)): ?>
    <div class="order-card">
        <div class="order-header">
            <div class="order-id">ออเดอร์ #<?= $o['id'] ?></div>
            <div class="order-total">ยอดรวม: <?= number_format($o['total_price']) ?> บาท</div>
        </div>

        <div class="order-status
            <?php 
                switch($o['status']){
                    case 'รอดำเนินการ': echo 'status-pending'; break;
                    case 'กำลังดำเนินการ': echo 'status-processing'; break;
                    case 'จัดส่งแล้ว': echo 'status-shipped'; break;
                    case 'รับสินค้าแล้ว': echo 'status-delivered'; break;
                    case 'ยกเลิก': echo 'status-cancelled'; break;
                    default: echo 'status-pending';
                }
            ?>
        "><?= $o['status'] ?></div>

        <?php if ($o['tracking_number']): ?>
            <div class="order-tracking">เลขพัสดุ: <?= $o['tracking_number'] ?></div>
        <?php endif; ?>

        <?php if ($o['status'] == 'จัดส่งแล้ว'): ?>
            <a href="confirm.php?id=<?= $o['id'] ?>" class="btn confirm">ยืนยันรับสินค้า</a>
        <?php endif; ?>
    </div>
    <?php endwhile; ?>

    <a href="index.php" class="back-link"><i class="fa-solid fa-arrow-left"></i> กลับไปหน้าหลัก</a>
</div>

</body>
</html>

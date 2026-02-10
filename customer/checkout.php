<?php
session_start();
include "../config/db.php";

if (!isset($_SESSION['user'])) {
    header("Location: ../auth/login.php");
    exit;
}

$uid = $_SESSION['user']['id'];

if (isset($_POST['pay'])) {

    $q = mysqli_query($conn,
        "SELECT c.*, p.price, p.name
         FROM carts c
         JOIN products p ON c.product_id = p.id
         WHERE c.customer_id = $uid"
    );

    $total = 0;
    $items = [];

    while ($row = mysqli_fetch_assoc($q)) {
        $total += $row['price'] * $row['quantity'];
        $items[] = $row;
    }

    if ($total == 0) {
        die("ไม่มีสินค้าในตะกร้า");
    }

    mysqli_query($conn,
        "INSERT INTO orders (customer_id, total_price, status)
         VALUES ($uid, $total, 'ชำระแล้ว')"
    );

    $order_id = mysqli_insert_id($conn);

    foreach ($items as $it) {
        mysqli_query($conn,
            "INSERT INTO order_items (order_id, product_id, quantity, price)
             VALUES (
                $order_id,
                {$it['product_id']},
                {$it['quantity']},
                {$it['price']}
             )"
        );
    }

    mysqli_query($conn,"DELETE FROM carts WHERE customer_id = $uid");

    echo "
    <div class='success-box'>
        <h2>✅ ชำระเงินสำเร็จ</h2>
        <p>ขอบคุณสำหรับการสั่งซื้อ</p>
        <a href='orders.php' class='btn'>ดูคำสั่งซื้อ</a>
        <a href='index.php' class='btn outline'>ซื้อเพิ่ม</a>
    </div>
    ";
    exit;
}
?>

<!DOCTYPE html>
<html lang="th">
<head>
<meta charset="UTF-8">
<title>ชำระเงิน</title>

<style>
body{
    font-family: system-ui, sans-serif;
    background:#f5f6f8;
    margin:0;
    padding:40px;
}

.checkout-box{
    max-width:500px;
    margin:auto;
    background:#fff;
    padding:30px;
    border-radius:12px;
    box-shadow:0 10px 30px rgba(0,0,0,.08);
}

.checkout-box h2{
    margin-top:0;
    text-align:center;
}

label{
    font-weight:600;
}

select{
    width:100%;
    padding:10px;
    margin-top:8px;
    border-radius:8px;
    border:1px solid #ccc;
}

button{
    width:100%;
    padding:12px;
    margin-top:20px;
    background:#222;
    color:#fff;
    border:none;
    border-radius:10px;
    font-size:16px;
    cursor:pointer;
}

button:hover{
    background:#000;
}

.back{
    display:block;
    text-align:center;
    margin-top:15px;
    color:#555;
    text-decoration:none;
}

.success-box{
    max-width:400px;
    margin:80px auto;
    background:#fff;
    padding:40px;
    text-align:center;
    border-radius:12px;
    box-shadow:0 10px 30px rgba(0,0,0,.1);
}

.success-box h2{
    margin-top:0;
}

.btn{
    display:inline-block;
    padding:10px 20px;
    margin:10px;
    background:#222;
    color:#fff;
    text-decoration:none;
    border-radius:8px;
}

.btn.outline{
    background:#fff;
    color:#222;
    border:1px solid #222;
}
</style>
</head>

<body>

<div class="checkout-box">
    <h2>🧾 ชำระเงิน</h2>

    <form method="post">
        <label>วิธีการชำระเงิน</label>
        <select name="payment_method">
            <option value="transfer">โอนเงิน</option>
            <option value="cod">เก็บเงินปลายทาง</option>
        </select>

        <button type="submit" name="pay">ยืนยันการชำระเงิน</button>
    </form>

    <a href="cart.php" class="back">← กลับไปตะกร้า</a>
</div>

</body>
</html>

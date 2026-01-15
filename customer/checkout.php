<?php
session_start();
include "../config/db.php";

if (!isset($_SESSION['user'])) {
    header("Location: ../auth/login.php");
    exit;
}

$uid = $_SESSION['user']['id'];

if (isset($_POST['pay'])) {

    // 1. ดึงของจากตะกร้า
    $q = mysqli_query($conn,
        "SELECT c.*, p.price
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

    // 2. สร้าง order
    mysqli_query($conn,
        "INSERT INTO orders (customer_id, total_price, status)
         VALUES ($uid, $total, 'ชำระแล้ว')"
    );

    $order_id = mysqli_insert_id($conn);

    // 3. ใส่สินค้าเข้า order_items
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

    // 4. ล้างตะกร้า
    mysqli_query($conn,
        "DELETE FROM carts WHERE customer_id = $uid"
    );

    echo "<h2>ชำระเงินสำเร็จ</h2>";
    echo "<a href='index.php'>ซื้อเพิ่ม</a>";
    exit;
}
?>

<h2>ชำระเงิน</h2>

<form method="post">
  <select name="payment_method">
    <option value="transfer">โอนเงิน</option>
    <option value="cod">เก็บปลายทาง</option>
  </select><br><br>

  <button type="submit" name="pay">ยืนยันการชำระ</button>
</form>
<a href="cart.php">กลับไปที่ตะกร้า</a>
<?php
session_start();
include "../config/db.php";

if (!isset($_SESSION['user'])) {
    header("Location: ../auth/login.php");
    exit;
}

$uid = $_SESSION['user']['id'];

$sql = "
SELECT c.*, p.name, p.price
FROM carts c
JOIN products p ON c.product_id = p.id
WHERE c.customer_id = $uid
";

$q = mysqli_query($conn, $sql);
if (!$q) {
    die("SQL Error: " . mysqli_error($conn));
}
?>

<h2>ตะกร้าสินค้า</h2>

<?php
$total = 0;
while ($row = mysqli_fetch_assoc($q)) {
    echo $row['name']." x ".$row['quantity']." = ";
    echo ($row['price'] * $row['quantity'])." บาท<br>";
    $total += $row['price'] * $row['quantity'];
}
?>

<p><b>รวมทั้งหมด:</b> <?= $total ?> บาท</p>
<a href="checkout.php">ชำระเงิน</a>

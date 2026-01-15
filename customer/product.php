<?php
session_start();
include "../config/db.php";

$id = $_GET['id'];
$p = mysqli_fetch_assoc(
    mysqli_query($conn,"SELECT * FROM products WHERE id=$id")
);

$imgs = mysqli_query($conn,
    "SELECT * FROM product_images WHERE product_id=$id"
);
?>

<h2><?= $p['name'] ?></h2>

<?php while($img = mysqli_fetch_assoc($imgs)): ?>
  <img src="../assets/images/products/<?= $img['image'] ?>" width="150">
<?php endwhile; ?>

<p><?= $p['description'] ?></p>
<p>ราคา <?= $p['price'] ?> บาท</p>
<p>คงเหลือ <?= $p['stock'] ?></p>

<form method="post">
  <a href="add_to_cart.php?id=<?= $product['id'] ?>">
  ใส่ตะกร้า
</a>
</form>

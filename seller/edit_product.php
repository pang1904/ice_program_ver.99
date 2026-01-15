<?php
session_start();
include "../config/db.php";

$id = $_GET['id'];

$p = mysqli_fetch_assoc(
    mysqli_query($conn,"SELECT * FROM products WHERE id=$id")
);

if (isset($_POST['update'])) {
    $name = $_POST['name'];
    $price = $_POST['price'];
    $stock = $_POST['stock'];

    mysqli_query($conn,
        "UPDATE products
         SET name='$name', price=$price, stock=$stock
         WHERE id=$id"
    );
    echo "แก้ไขแล้ว";
}

if (isset($_POST['delete'])) {
    mysqli_query($conn,"DELETE FROM products WHERE id=$id");
    header("Location: dashboard.php");
}
?>

<h2>แก้ไขสินค้า</h2>

<form method="post">
  <input name="name" value="<?= $p['name'] ?>"><br><br>
  <input name="price" value="<?= $p['price'] ?>"><br><br>
  <input name="stock" value="<?= $p['stock'] ?>"><br><br>

  <button name="update">บันทึก</button>
  <button name="delete" onclick="return confirm('ลบจริงไหม')">ลบ</button>
</form>

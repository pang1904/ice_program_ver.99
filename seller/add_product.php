<?php
session_start();
include "../config/db.php";

if(isset($_POST['add'])){
    $sid   = $_SESSION['user']['id'];
    $name  = $_POST['name'];
    $price = $_POST['price'];
    $stock = $_POST['stock'];
    $desc  = $_POST['description'];

    mysqli_query($conn,"
        INSERT INTO products (seller_id,name,description,price,stock)
        VALUES ($sid,'$name','$desc',$price,$stock)
    ");

    $pid = mysqli_insert_id($conn); // id สินค้า

    // อัปโหลดรูป (รูปแรก = รูปหลัก)
    foreach($_FILES['images']['name'] as $i => $img){
        if($img == '') continue;

        $tmp = $_FILES['images']['tmp_name'][$i];
        $new = time().'_'.$img;

        move_uploaded_file($tmp,"../assets/images/products/".$new);

        mysqli_query($conn,"
            INSERT INTO product_images (product_id,image)
            VALUES ($pid,'$new')
        ");
    }

    echo "✅ เพิ่มสินค้าสำเร็จ";
}
?>

<h2>เพิ่มสินค้า</h2>

<form method="post" enctype="multipart/form-data">
  ชื่อสินค้า<br>
  <input name="name" required><br><br>

  รายละเอียด<br>
  <textarea name="description"></textarea><br><br>

  ราคา<br>
  <input name="price" required><br><br>

  จำนวน<br>
  <input name="stock" required><br><br>

  รูปสินค้า (รูปแรกจะเป็นรูปหลัก)<br>
  <input type="file" name="images[]" multiple required><br><br>

  <button name="add">เพิ่มสินค้า</button>
</form>

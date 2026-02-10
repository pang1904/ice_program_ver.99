<?php
session_start();
include "../config/db.php";

if(!isset($_SESSION['user'])){
    header("Location: ../auth/login.php");
    exit;
}

$message = "";

if(isset($_POST['add'])){
    $sid   = $_SESSION['user']['id'];
    $name  = mysqli_real_escape_string($conn,$_POST['name']);
    $price = floatval($_POST['price']);
    $stock = intval($_POST['stock']);
    $desc  = mysqli_real_escape_string($conn,$_POST['description']);

    mysqli_query($conn,"
        INSERT INTO products (seller_id,name,description,price,stock)
        VALUES ($sid,'$name','$desc',$price,$stock)
    ");

    $pid = mysqli_insert_id($conn); // id สินค้า

    // อัปโหลดรูป
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

    $message = "✅ เพิ่มสินค้าสำเร็จ!";
}
?>

<!DOCTYPE html>
<html lang="th">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>เพิ่มสินค้า</title>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
<style>
body{
    font-family: 'Inter', sans-serif;
    background: linear-gradient(135deg,#e0f2fe,#f8fafc);
    padding:30px;
    color:#333;
}

h2{
    color:#0ea5e9;
    text-align:center;
    margin-bottom:30px;
    font-weight:700;
    font-size:28px;
}

.container{
    max-width:600px;
    margin:auto;
    background:#fff;
    padding:30px;
    border-radius:16px;
    box-shadow:0 20px 40px rgba(0,0,0,0.1);
}

input, textarea, button, select{
    width:100%;
    padding:12px 14px;
    margin-bottom:20px;
    border-radius:10px;
    border:1px solid #cbd5f5;
    font-size:15px;
    box-sizing:border-box;
}

input:focus, textarea:focus, select:focus{
    outline:none;
    border-color:#0ea5e9;
}

textarea{
    resize: vertical;
    min-height:80px;
}

button{
    background:#0ea5e9;
    color:#fff;
    font-weight:600;
    border:none;
    cursor:pointer;
    transition: all 0.3s ease;
}

button:hover{
    background:#0284c7;
    transform: translateY(-2px);
}

.image-preview{
    display:grid;
    grid-template-columns: repeat(auto-fill,minmax(100px,1fr));
    gap:10px;
    margin-bottom:20px;
}

.image-preview img{
    width:100%;
    height:100px;
    object-fit:cover;
    border-radius:10px;
    border:1px solid #cbd5e0;
    transition: transform 0.3s;
}

.image-preview img:hover{
    transform: scale(1.05);
}

.message{
    background:#d1fae5;
    color:#065f46;
    padding:10px 15px;
    border-radius:8px;
    margin-bottom:20px;
    font-weight:600;
    text-align:center;
}
</style>
</head>
<body>
<a href="dashboard.php" class="back-link"><i class="fa-solid fa-arrow-left"></i> กลับไปหน้าหลัก</a>
<h2>เพิ่มสินค้า</h2>

<div class="container">

<?php if($message): ?>
<div class="message"><?= $message ?></div>
<?php endif; ?>

<form method="post" enctype="multipart/form-data" id="productForm">
  ชื่อสินค้า
  <input name="name" placeholder="ระบุชื่อสินค้า" required>

  รายละเอียด
  <textarea name="description" placeholder="รายละเอียดสินค้า"></textarea>

  ราคา
  <input name="price" type="number" step="0.01" placeholder="0.00" required>

  จำนวน
  <input name="stock" type="number" placeholder="จำนวนสินค้า" required>

  รูปสินค้า (รูปแรกจะเป็นรูปหลัก)
  <input type="file" name="images[]" id="images" multiple required>
  
  <div class="image-preview" id="imagePreview"></div>

  <button name="add">เพิ่มสินค้า</button>
</form>

</div>

<script>
// แสดง preview ของรูปก่อนอัปโหลด
const imagesInput = document.getElementById('images');
const imagePreview = document.getElementById('imagePreview');

imagesInput.addEventListener('change', function(){
    imagePreview.innerHTML = '';
    const files = imagesInput.files;
    Array.from(files).forEach(file=>{
        const reader = new FileReader();
        reader.onload = e => {
            const img = document.createElement('img');
            img.src = e.target.result;
            imagePreview.appendChild(img);
        }
        reader.readAsDataURL(file);
    });
});
</script>

</body>
</html>

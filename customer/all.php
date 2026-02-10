<?php
session_start();
include "../config/db.php";

// รับค่าการค้นหาและหมวดหมู่จาก GET
$search = isset($_GET['search']) ? $_GET['search'] : '';
$category_id = isset($_GET['category']) ? (int)$_GET['category'] : 0;

// ดึงหมวดหมู่ทั้งหมด
$catQuery = mysqli_query($conn, "SELECT * FROM categories ORDER BY name ASC");
$categories = mysqli_fetch_all($catQuery, MYSQLI_ASSOC);

// สร้าง SQL สำหรับดึงสินค้า
$sql = "SELECT p.*, 
        (SELECT pi.image FROM product_images pi WHERE pi.product_id = p.id ORDER BY pi.id ASC LIMIT 1) AS image
        FROM products p
        WHERE 1=1";

if ($category_id > 0) {
    $sql .= " AND p.category_id = $category_id";
}

if (!empty($search)) {
    $search_safe = mysqli_real_escape_string($conn, $search);
    $sql .= " AND p.name LIKE '%$search_safe%'";
}

$sql .= " ORDER BY p.id DESC";

$products = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="th">
<head>
<meta charset="UTF-8">
<title>สินค้าทั้งหมด</title>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<style>
:root{
    --bg:#f9fafe;
    --card:#fff;
    --text:#1a1a1a;
    --muted:#555;
    --border:#e0e6f0;
    --accent:#4dabf7;
    --accent-dark:#1c7ed6;
}

body{ font-family:'Segoe UI', sans-serif; background:var(--bg); color:var(--text); margin:0; padding:0; }

.container{ max-width:1200px; margin:auto; padding:40px 20px; }

h1{ margin-bottom:20px; color:var(--accent-dark); }

.filters{
    display:flex;
    flex-wrap:wrap;
    gap:15px;
    margin-bottom:30px;
}

.filters select, .filters input{
    padding:10px 15px;
    border-radius:12px;
    border:1px solid var(--border);
    font-size:15px;
}

.products{
    display:grid;
    grid-template-columns:repeat(auto-fill,minmax(250px,1fr));
    gap:30px;
}

.card{
    background:var(--card); border-radius:20px; overflow:hidden;
    border:1px solid var(--border); box-shadow:0 8px 20px rgba(0,0,0,0.05);
    transition:.3s;
}

.card:hover{ transform:translateY(-6px); box-shadow:0 20px 35px rgba(0,0,0,0.08); }

.card img{ width:100%; height:200px; object-fit:cover; transition: transform 0.3s; }
.card img:hover{ transform:scale(1.05); }

.card-body{ padding:20px; }
.card h4{ margin:0 0 8px 0; font-size:17px; font-weight:600; }
.price{ font-weight:700; color:var(--accent-dark); margin-bottom:6px; }
.stock{ font-size:14px; color:var(--muted); }

.actions{ display:flex; gap:10px; margin-top:10px; }
.actions a{
    flex:1; text-align:center; padding:10px 0; border-radius:12px;
    text-decoration:none; font-size:15px; border:1px solid var(--border);
    color:var(--text); transition:.3s;
}
.actions a.buy{ background:var(--accent-dark); color:#fff; border-color:var(--accent-dark); }
.actions a.buy:hover{ background:var(--accent); }
.actions a:hover{ color:var(--accent-dark); border-color:var(--accent-dark); }
</style>
</head>
<body>

<div class="container">
<a href="index.php" class="back-link"><i class="fa-solid fa-arrow-left"></i> กลับไปหน้าหลัก</a>
<h1>สินค้าทั้งหมด</h1>

<!-- Filters -->
<form method="GET" class="filters">
    <select name="category" onchange="this.form.submit()">
        <option value="0">ทุกหมวดหมู่</option>
        <?php foreach($categories as $cat): ?>
            <option value="<?= $cat['id'] ?>" <?= ($category_id==$cat['id'])?'selected':'' ?>>
                <?= htmlspecialchars($cat['name']) ?>
            </option>
        <?php endforeach; ?>
    </select>

    <input type="text" name="search" placeholder="ค้นหาสินค้า..." value="<?= htmlspecialchars($search) ?>">
    <button type="submit" style="padding:10px 20px; border-radius:12px; border:1px solid var(--border); background:var(--accent-dark); color:#fff; cursor:pointer;">
        <i class="fa-solid fa-magnifying-glass"></i> ค้นหา
    </button>
</form>

<!-- Products -->
<div class="products">
<?php if(mysqli_num_rows($products)==0): ?>
    <p>ไม่พบสินค้าที่ตรงกับเงื่อนไข</p>
<?php else: ?>
    <?php while($p = mysqli_fetch_assoc($products)): ?>
        <div class="card">
            <img src="../assets/images/products/<?= $p['image'] ? $p['image'] : 'no-image.png' ?>" alt="<?= htmlspecialchars($p['name']) ?>">
            <div class="card-body">
                <h4><?= htmlspecialchars($p['name']) ?></h4>
                <div class="price">฿<?= number_format($p['price']) ?></div>
                <div class="actions">
                    <a href="product.php?id=<?= $p['id'] ?>">รายละเอียด</a>
                    <a class="buy" href="add_to_cart.php?id=<?= $p['id'] ?>">ใส่ตะกร้า</a>
                </div>
            </div>
        </div>
    <?php endwhile; ?>
<?php endif; ?>
</div>

</div>
</body>
</html>

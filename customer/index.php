<?php 
session_start();
include "../config/db.php";

if (!isset($_SESSION['user'])) {
    header("Location: ../auth/login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="th">
<head>
<meta charset="UTF-8">
<title>ICEMARKET | Home</title>

<!-- Font Awesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<style>
:root{
    --bg:#f9fafe;
    --card:#ffffff;
    --text:#1a1a1a;
    --muted:#555;
    --border:#e0e6f0;
    --accent:#4dabf7;
    --accent-dark:#1c7ed6;
    --hero-gradient: linear-gradient(135deg, #74c0fc 0%, #4dabf7 100%);
}

/* Reset */
*{ margin:0; padding:0; box-sizing:border-box; }
body{ font-family:'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background:var(--bg); color:var(--text); }

/* ===== NAVBAR ===== */
.navbar{
    background:#fff;
    border-bottom:1px solid var(--border);
    padding:15px 50px;
    display:flex;
    justify-content:space-between;
    align-items:center;
    box-shadow:0 3px 12px rgba(0,0,0,0.08);
    position: relative;
    z-index:200;
}

.navbar .logo{
    font-size:28px; font-weight:800; color:var(--accent-dark); letter-spacing:1px;
}

.nav-links{
    list-style:none;
    display:flex;
    align-items:center;
    gap:25px;
    transition: all 0.3s;
}

.nav-links li{ position:relative; }

.nav-links a{
    text-decoration:none;
    color:var(--text);
    font-weight:500;
    font-size:15px;
    display:flex;
    align-items:center;
    gap:5px;
    padding:8px 12px;
    border-radius:8px;
    transition: all 0.3s ease;
}

.nav-links a:hover{
    background:rgba(77,171,247,0.1);
    color:var(--accent-dark);
}

/* Dropdown */
.dropdown-menu{
    display:none;
    position:absolute;
    top:42px;
    left:0;
    background:#fff;
    border:1px solid var(--border);
    border-radius:12px;
    padding:8px 0;
    min-width:180px;
    box-shadow:0 15px 35px rgba(0,0,0,0.12);
    opacity:0;
    transform: translateY(-10px);
    pointer-events:none;
    transition: all 0.3s ease;
    z-index:100;
}

.dropdown-menu li a{
    padding:10px 20px;
    display:block;
    font-size:14px;
    color:var(--text);
    transition: all 0.3s;
}

.dropdown-menu li a:hover{
    background:var(--accent);
    color:#fff;
}

.dropdown-menu.show{
    display:block;
    opacity:1;
    transform: translateY(0);
    pointer-events:auto;
}

/* Desktop Hover */
@media(min-width:901px){
    .dropdown:hover .dropdown-menu{
        display:block;
        opacity:1;
        transform: translateY(0);
        pointer-events:auto;
    }
}

/* Hamburger */
.hamburger{
    display:none;
    font-size:24px;
    cursor:pointer;
    color:var(--accent-dark);
}

@media(max-width:900px){
    .nav-links{
        flex-direction:column;
        width:100%;
        display:none;
        background:#fff;
        border-top:1px solid var(--border);
        margin-top:10px;
    }
    .nav-links li{ margin:10px 0; width:100%; }
    .navbar{ flex-wrap:wrap; }
    .hamburger{ display:block; }
}

/* ===== CONTAINER ===== */
.container{ max-width:1200px; margin:auto; padding:50px 20px; }

/* ===== HERO ===== */
.hero{
    background:var(--hero-gradient);
    color:#fff;
    border-radius:25px;
    padding:70px 50px;
    margin-bottom:60px;
    box-shadow:0 15px 50px rgba(0,0,0,0.15);
    text-align:center;
}

.hero h1{ font-size:40px; font-weight:700; }
.hero p{ font-size:18px; margin-top:15px; color:rgba(255,255,255,0.9); }
.hero button{
    margin-top:30px; padding:15px 45px; border-radius:50px; border:none;
    background:var(--accent-dark); color:#fff; font-size:16px; font-weight:600;
    cursor:pointer; transition: all 0.3s; box-shadow:0 6px 25px rgba(0,0,0,0.2);
}
.hero button:hover{
    background:var(--accent); transform:translateY(-3px); box-shadow:0 10px 30px rgba(0,0,0,0.25);
}

/* ===== PRODUCTS ===== */
.section-title{ font-size:28px; margin-bottom:35px; font-weight:700; color:var(--accent-dark); }

.products{
    display:grid;
    grid-template-columns:repeat(auto-fill,minmax(250px,1fr));
    gap:30px;
}

.card{
    background:var(--card);
    border-radius:20px;
    overflow:hidden;
    border:1px solid var(--border);
    box-shadow:0 10px 30px rgba(0,0,0,0.07);
    transition: all 0.3s;
    position:relative;
}

.card:hover{
    transform:translateY(-10px);
    box-shadow:0 30px 60px rgba(0,0,0,0.15);
}

.card img{
    width:100%; height:220px; object-fit:cover;
    transition: transform 0.4s;
}
.card img:hover{ transform:scale(1.08); }

.card-body{ padding:22px; }
.card h4{ font-size:18px; font-weight:600; margin-bottom:8px; }
.price{ font-weight:700; color:var(--accent-dark); margin-bottom:6px; }
.stock{ font-size:14px; color:var(--muted); }

.actions{
    margin-top:15px; display:flex; gap:10px;
}

.actions a{
    flex:1; text-align:center; padding:10px 0; border-radius:12px;
    text-decoration:none; font-size:15px; border:1px solid var(--border);
    color:var(--text); transition: all 0.3s;
}
.actions a.buy{
    background:var(--accent-dark); color:#fff; border-color:var(--accent-dark);
}
.actions a.buy:hover{ background:var(--accent); }
.actions a:hover{ color:var(--accent-dark); border-color:var(--accent-dark); }

.soldout{ margin-top:12px; font-size:15px; color:#999; font-weight:600; text-align:center; }

/* ===== FOOTER ===== */
.footer{
    margin-top:80px; padding:35px; text-align:center;
    font-size:14px; color:#888; border-top:1px solid var(--border);
    background:#fff;
}
</style>

<script>
function toggleMenu(){
    const nav = document.querySelector('.nav-links');
    nav.style.display = (nav.style.display === 'flex') ? 'none' : 'flex';
}

// Toggle dropdown for mobile
function toggleDropdown(element){
    const dropdown = element.nextElementSibling;
    dropdown.classList.toggle('show');
}

// Close dropdown if clicked outside
window.addEventListener('click', function(e){
    document.querySelectorAll('.dropdown-menu').forEach(menu=>{
        if(!menu.parentElement.contains(e.target)){
            menu.classList.remove('show');
        }
    });
});
</script>
</head>

<body>

<!-- NAVBAR -->
<div class="navbar">
    <div class="logo">ICEMARKET</div>
    <i class="fas fa-bars hamburger" onclick="toggleMenu()"></i>
    <ul class="nav-links">
        <li><a href="#">หน้าแรก</a></li>
        <li class="dropdown">
            <a href="all.php" class="dropbtn" onclick="toggleDropdown(this)">
                สินค้าทั้งหมด 
            </a>
        <li><a href="sale.php">ลดราคา</a></li>
        <li><a href="cart.php"><i class="fa-solid fa-cart-shopping"></i> ตะกร้า</a></li>
        <li><a href="orders.php"><i class="fa-solid fa-box"></i> คำสั่งซื้อ</a></li>
        <li><a href="../auth/logout.php"><i class="fa-solid fa-right-from-bracket"></i> ออกจากระบบ</a></li>
    </ul>
</div>

<div class="container">
    <!-- HERO -->
    <div class="hero">
        <h1>ยินดีต้อนรับ, <?= $_SESSION['user']['username']; ?></h1>
        <p>เลือกซื้อสินค้าคุณภาพ ในสไตล์ที่คุณชอบ</p>
        <button>
            <a href="all.php">เริ่มช้อปปิ้ง</a>
        </button>
    </div>

    <h2 class="section-title">สินค้ามาใหม่</h2>

    <div class="products">
    <?php
    $q = mysqli_query($conn,"
        SELECT 
            p.*,
            (
                SELECT pi.image 
                FROM product_images pi 
                WHERE pi.product_id = p.id 
                ORDER BY pi.id ASC 
                LIMIT 1
            ) AS image
        FROM products p
        ORDER BY p.id DESC
        LIMIT 6
    ");

    while ($p = mysqli_fetch_assoc($q)):
    ?>
    <div class="card">
        <?php if ($p['image']): ?>
            <img src="../assets/images/products/<?= $p['image'] ?>">
        <?php else: ?>
            <img src="../assets/images/no-image.png">
        <?php endif; ?>

        <div class="card-body">
            <h4><?= htmlspecialchars($p['name']) ?></h4>
            <div class="price">฿<?= number_format($p['price']) ?></div>

            <?php if ($p['stock'] > 0): ?>
                <div class="stock">คงเหลือ <?= $p['stock'] ?> ชิ้น</div>
                <div class="actions">
                    <a href="product.php?id=<?= $p['id'] ?>">รายละเอียด</a>
                    <a class="buy" href="add_to_cart.php?id=<?= $p['id'] ?>">ใส่ตะกร้า</a>
                </div>
            <?php else: ?>
                <div class="soldout">สินค้าหมด</div>
            <?php endif; ?>
        </div>
    </div>
    <?php endwhile; ?>
    </div>
</div>

<div class="footer">
© <?= date('Y') ?> ICEMARKET • Shop Smart, Shop Cool
</div>

</body>
</html>

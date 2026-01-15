<?php
session_start();
include "../config/db.php";

if (!isset($_SESSION['user'])) {
    header("Location: ../auth/login.php");
    exit;
}

if (!isset($_GET['id'])) {
    echo "ไม่พบออเดอร์";
    exit;
}

$order_id = (int)$_GET['id'];
$customer_id = $_SESSION['user']['id'];

/* =========================
   1) ตรวจสอบออเดอร์ก่อน
========================= */
$check = mysqli_query($conn, "
    SELECT status 
    FROM orders 
    WHERE id = $order_id 
    AND customer_id = $customer_id
");

if (mysqli_num_rows($check) == 0) {
    echo "ไม่พบออเดอร์นี้";
    exit;
}

$order = mysqli_fetch_assoc($check);

/* ถ้ายืนยันไปแล้ว ห้ามทำซ้ำ */
if ($order['status'] == 'สำเร็จ') {
    echo "ออเดอร์นี้ยืนยันไปแล้ว";
    exit;
}

/* =========================
   2) ดึงสินค้าทั้งหมดในออเดอร์
========================= */
$items = mysqli_query($conn, "
    SELECT product_id, quantity 
    FROM order_items 
    WHERE order_id = $order_id
");

/* =========================
   3) ลด stock ทีละสินค้า
========================= */
while ($item = mysqli_fetch_assoc($items)) {
    $pid = $item['product_id'];
    $qty = $item['quantity'];

    mysqli_query($conn, "
        UPDATE products 
        SET stock = stock - $qty
        WHERE id = $pid
    ");
}

/* =========================
   4) เปลี่ยนสถานะเป็น สำเร็จ
========================= */
mysqli_query($conn, "
    UPDATE orders 
    SET status = 'สำเร็จ'
    WHERE id = $order_id
");

/* =========================
   5) กลับไปหน้าออเดอร์
========================= */
header("Location: orders.php");
exit;

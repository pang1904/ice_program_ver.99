<?php
session_start();
include "../config/db.php";

if (!isset($_SESSION['user'])) {
    header("Location: ../auth/login.php");
    exit;
}

$uid = $_SESSION['user']['id'];
$pid = $_GET['id'];

$q = mysqli_query($conn,
    "SELECT * FROM carts
     WHERE customer_id = $uid
     AND product_id = $pid"
);

if (mysqli_num_rows($q) > 0) {
    mysqli_query($conn,
        "UPDATE carts
         SET quantity = quantity + 1
         WHERE customer_id = $uid
         AND product_id = $pid"
    );
} else {
    mysqli_query($conn,
        "INSERT INTO carts (customer_id, product_id, quantity)
         VALUES ($uid, $pid, 1)"
    );
}

header("Location: cart.php");
exit;

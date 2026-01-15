<?php
$conn = mysqli_connect("localhost","root","","shop");
mysqli_set_charset($conn,"utf8");

if(!$conn){
    die("เชื่อมต่อฐานข้อมูลไม่ได้");
}
?>

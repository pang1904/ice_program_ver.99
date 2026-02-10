<?php
session_start();
include "../config/db.php"; // เชื่อมต่อฐานข้อมูล

header('Content-Type: application/json');

$response = ['status'=>'error', 'message'=>'เกิดข้อผิดพลาด'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $role = $_POST['role'] ?? '';
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if (!$role || !$username || !$password) {
        $response['message'] = 'กรุณากรอกข้อมูลให้ครบ';
        echo json_encode($response);
        exit;
    }

    // ตรวจสอบว่าชื่อผู้ใช้ซ้ำ
    $check = mysqli_query($conn, "SELECT id FROM users WHERE username='".mysqli_real_escape_string($conn,$username)."'");
    if (mysqli_num_rows($check) > 0) {
        $response['message'] = 'ชื่อผู้ใช้นี้มีอยู่แล้ว';
        echo json_encode($response);
        exit;
    }

    // hash password
    $hash = password_hash($password, PASSWORD_BCRYPT);

    // เพิ่มผู้ใช้ใหม่
    $insert = mysqli_query($conn, "INSERT INTO users (username, password, role) VALUES ('$username', '$hash', '$role')");

    if ($insert) {
        $response['status'] = 'success';
        $response['message'] = 'สมัครสมาชิกเรียบร้อย';
    } else {
        $response['message'] = 'เกิดข้อผิดพลาดในการบันทึกข้อมูล';
    }

    echo json_encode($response);
}
?>

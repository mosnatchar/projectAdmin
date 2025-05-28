<?php



// CORS headers — แก้ปัญหา Access-Control-Allow-Origin
header("Access-Control-Allow-Origin: *"); // กำหนดให้รับจาก origin นี้เท่านั้น
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Credentials: true");
header('Content-Type: application/json; charset=utf-8');

require_once '../connect_db.php';

//  รับค่าจากฟอร์ม
$member_id = uniqid();
$fullname = $_POST['fullname'];
$username = $_POST['username'];
$password = $_POST['password']; // 👉 คุณสามารถเข้ารหัสด้วย password_hash ได้

$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

// เตรียมคำสั่ง SQL
$sql = "INSERT INTO member (MemberID, FullName, Username, Password)
        VALUES (?, ?, ?, ?)";

//  ใช้ Prepared Statement เพื่อความปลอดภัย (ป้องกัน SQL Injection)
$stmt = $conn->prepare($sql);
$stmt->bind_param("ssss", $member_id, $fullname, $username, $hashedPassword);

//  Execute
if ($stmt->execute()) {
    echo "✅ สมัครสมาชิกสำเร็จ!";
} else {
    echo "❌ เกิดข้อผิดพลาด: " . $stmt->error;
}

$stmt->close();


?>
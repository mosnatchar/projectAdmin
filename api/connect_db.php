<?php
// $host = "localhost";
// $username = "root";
// $password = ""; // ถ้าใช้ XAMPP หรือ Laragon ปกติจะไม่มีรหัสผ่าน
// $dbname = "laragon_db";

// // สร้างการเชื่อมต่อ
// $conn = new mysqli($host, $username, $password, $dbname);
$host = 'yamanote.proxy.rlwy.net';
$port = 13319;
$dbname = 'railway';
$username = 'root';
$password = 'uuajgJCGckFETNWDNjSMBkVIPGWGPQTP';

$conn = new mysqli($host, $username, $password, $dbname, $port);

// ตรวจสอบการเชื่อมต่อ
if ($conn->connect_error) {
    die("เชื่อมต่อฐานข้อมูลล้มเหลว: " . $conn->connect_error);
}

// echo "เชื่อมต่อฐานข้อมูล Railway สำเร็จ!";
// echo "เชื่อมต่อฐานข้อมูลสำเร็จ!";
?>
<?php

// CORS headers — แก้ปัญหา Access-Control-Allow-Origin
header("Access-Control-Allow-Origin: *"); // กำหนดให้รับจาก origin นี้เท่านั้น
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Credentials: true");
header('Content-Type: application/json; charset=utf-8');

require_once '../connect_db.php';
require '../vendor/autoload.php';
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

//  รับค่าจากฟอร์ม
$username = $_POST['username'];
$password = $_POST['password'];

// เตรียมคำสั่ง SQL
$sql = "SELECT * FROM customer WHERE username = '$username'";
$result = mysqli_query($conn, $sql);
if (mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_array($result);
    if (password_verify($password, $row["password"])) {
        // 🟢 รหัสผ่านถูกต้อง → สร้าง JWT
        $payload = [
            'uid' => (int) $row['id'],
            'username' => $username,
            'exp' => time() + 3600 // หมดอายุใน 1 ชม.
        ];

        $secretKey = "jwtsecret";
        $jwt = JWT::encode($payload, $secretKey, 'HS256');

        echo json_encode([
            "message" => "เข้าสู่ระบบสำเร็จ",
            "token" => $jwt
        ], JSON_UNESCAPED_UNICODE);
    } else {
        // 🔴 รหัสผ่านผิด
        http_response_code(401);
        echo json_encode(["message" => "ชื่อผู้ใช้หรือรหัสผ่านไม่ถูกต้อง"], JSON_UNESCAPED_UNICODE);
    }
} else {
    echo json_encode([
        "message" => "ไม่พบข้อมูล",
        "token" => null
    ], JSON_UNESCAPED_UNICODE);
}
mysqli_close($conn);


?>
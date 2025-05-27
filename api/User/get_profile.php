<?php



// CORS headers — แก้ปัญหา Access-Control-Allow-Origin
header("Access-Control-Allow-Origin: *"); // กำหนดให้รับจาก origin นี้เท่านั้น
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Credentials: true");
header('Content-Type: application/json; charset=utf-8');

require_once '../connect_db.php';


// อ่าน raw JSON จาก request body
$json = file_get_contents("php://input");

// แปลง JSON เป็น array (assoc = associative array)
$data = json_decode($json, true);

// ตรวจสอบและใช้งานข้อมูล
$uid = $data['uid'] ?? '';


// เตรียมคำสั่ง SQL
$sql = "SELECT * FROM profile WHERE uid = '$uid'";
$result = mysqli_query($conn, $sql);
if (mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
    echo json_encode([
        "message" => "สำเร็จ",
        "data" => $row
    ], JSON_UNESCAPED_UNICODE);
} else {
    echo json_encode([
        "message" => "ไม่พบข้อมูล",
        "data" => null
    ], JSON_UNESCAPED_UNICODE);
}
mysqli_close($conn);

?>
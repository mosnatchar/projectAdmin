<?php
// CORS headers — แก้ปัญหา Access-Control-Allow-Origin
header("Access-Control-Allow-Origin: *"); // กำหนดให้รับจาก origin นี้เท่านั้น
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Access-Control-Allow-Credentials: true");
header('Content-Type: application/json; charset=utf-8');

require_once '../connect_db.php';


// ตัวอย่างฟังก์ชัน
function getAllTraining($conn)
{
    $data = [];

    // กรณี All → ไม่มี WHERE
    $sql = "SELECT * FROM performance";
    $stmt = $conn->prepare($sql);


    // รัน query
    $stmt->execute();
    $result = $stmt->get_result();

    // เอาข้อมูลมาใส่ array
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }

    // ส่งกลับเป็น JSON
    echo json_encode([
        "message" => "สำเร็จ",
        "data" => $data
    ], JSON_UNESCAPED_UNICODE);

    $stmt->close();
    $conn->close();

}
// ตัวอย่างฟังก์ชัน
function deleteTraining($conn, $portfolio_id)
{
    // เตรียมคำสั่ง SQL
    $sql = "DELETE FROM performance WHERE PortfolioID = ?";

    // ใช้ prepared statement ปลอดภัยกว่า
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $portfolio_id); // "i" หมายถึง integer
    $stmt->execute();

    $affectedRows = $stmt->affected_rows;
    if ($affectedRows >= 0) {
        echo json_encode([
            "message" => "ลบข้อมูลสำเร็จ",
            "deleted_rows" => $affectedRows
        ], JSON_UNESCAPED_UNICODE);
    } else {
        echo json_encode([
            "message" => "ไม่พบข้อมูลที่ต้องการลบ",
            "deleted_rows" => 0
        ], JSON_UNESCAPED_UNICODE);
    }

    $stmt->close();
    mysqli_close($conn);

}


// ตรวจสอบว่า `action` ต้องการเรียกฟังก์ชันไหน
$action = $_GET['action'] ?? '';
$portfolio_id = $_GET['portfolio_id'] ?? '';

switch ($action) {
    case 'getAllTraining':
        getAllTraining($conn);
        break;
    case 'deleteTraining':
        deleteTraining($conn, $portfolio_id);
        break;

    default:
        echo json_encode(["error" => "Invalid action"]);
}
?>
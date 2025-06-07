<?php
// CORS headers — แก้ปัญหา Access-Control-Allow-Origin
header("Access-Control-Allow-Origin: *"); // กำหนดให้รับจาก origin นี้เท่านั้น
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Access-Control-Allow-Credentials: true");
header('Content-Type: application/json; charset=utf-8');

require_once '../connect_db.php';


// ตัวอย่างฟังก์ชัน
function getAllUsers($conn, $member_type)
{
    $data = [];

    if ($member_type == "All") {
        // กรณี All → ไม่มี WHERE
        $sql = "SELECT * FROM member";
        $stmt = $conn->prepare($sql);
    } else {
        // มี WHERE Permission
        $sql = "SELECT * FROM member WHERE Permission = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $member_type);
    }

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
function deleteUser($conn, $member_id)
{
    // เตรียมคำสั่ง SQL
    $sql = "DELETE FROM member WHERE MemberID = ?";

    // ใช้ prepared statement ปลอดภัยกว่า
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $member_id); // "i" หมายถึง integer
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
$member_id = $_GET['member_id'] ?? '';
$member_type = $_GET['member_type'] ?? '';

switch ($action) {
    case 'getAllUsers':
        getAllUsers($conn, $member_type);
        break;
    case 'deleteUser':
        deleteUser($conn, $member_id);
        break;

    default:
        echo json_encode(["error" => "Invalid action"]);
}
?>
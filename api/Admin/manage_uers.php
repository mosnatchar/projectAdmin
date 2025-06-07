<?php
header('Content-Type: application/json');

// ตัวอย่างฟังก์ชัน
function sayHello()
{
    echo json_encode(["message" => "Hello from PHP"]);
}

function getTime()
{
    echo json_encode(["time" => date("H:i:s")]);
}

// ตรวจสอบว่า `action` ต้องการเรียกฟังก์ชันไหน
$action = $_GET['action'] ?? '';

switch ($action) {
    case 'hello':
        sayHello();
        break;
    case 'time':
        getTime();
        break;
    default:
        echo json_encode(["error" => "Invalid action"]);
}
?>
<?php



// CORS headers — แก้ปัญหา Access-Control-Allow-Origin
header("Access-Control-Allow-Origin: *"); // กำหนดให้รับจาก origin นี้เท่านั้น
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Credentials: true");
header('Content-Type: application/json; charset=utf-8');

require_once '../connect_db.php';


//  รับค่าจากฟอร์ม
$member_id = $_POST['member_id'];
$full_name = $_POST['full_name'];
$username = $_POST['username'];
$email = $_POST['email'];
$phone = $_POST['phone'];
$birthdate = $_POST['birthdate'];
$occupation = $_POST['occupation'];
$address = $_POST['address'];
$district = $_POST['district'];
$subdistrict = $_POST['subdistrict'];
$province = $_POST['province'];
$zipcode = $_POST['zipcode'];
$file_name = $_POST['file_name'];

// ตรวจสอบว่ามีการอัปโหลดไฟล์
if ($file_name === "") {
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {

        // $uploadDir = 'C:\\Users\\apich\\OneDrive\\Desktop\\projectAdmin\\api\\User\\uploads\\'; // โฟลเดอร์ปลายทาง
        $uploadDir = __DIR__ . "/uploads/";
        // โฟลเดอร์ปลายทาง
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true); // สร้างโฟลเดอร์ถ้ายังไม่มี
        }

        $tmpName = $_FILES['image']['tmp_name'];
        $originalName = basename($_FILES['image']['name']);
        $extension = pathinfo($originalName, PATHINFO_EXTENSION);

        // ตั้งชื่อใหม่ให้ไม่ซ้ำ (timestamp + random)
        $newName = uniqid() . '.' . strtolower($extension);
        $destination = $uploadDir . $newName;

        // ย้ายไฟล์ไปยังโฟลเดอร์
        if (move_uploaded_file($tmpName, $destination)) {
            $sql = "INSERT INTO member (MemberID, FullName, ProfileImage, Username, Email, Telephone, BirthDate, Occupation, Address, District, Subdistrict, Province, ZipCode)
VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
ON DUPLICATE KEY UPDATE
    MemberID = VALUES(memberID),
    FullName = VALUES(FullName),
    ProfileImage = VALUES(ProfileImage),
    Username = VALUES(Username),
    Email = VALUES(Email),
    Telephone = VALUES(Telephone),
    BirthDate = VALUES(BirthDate),
    Occupation = VALUES(Occupation),
    Address = VALUES(Address),
    District = VALUES(District),
    Subdistrict = VALUES(Subdistrict),
    Province = VALUES(Province),
    ZipCode = VALUES(ZipCode)";

            //  ใช้ Prepared Statement เพื่อความปลอดภัย (ป้องกัน SQL Injection)
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sssssssssssss", $member_id, $full_name, $newName, $username, $email, $phone, $birthdate, $occupation, $address, $district, $subdistrict, $province, $zipcode);

            //  Execute
            if ($stmt->execute()) {
                echo "บันทึกข้อมูลสำเร็จ!";
            } else {
                echo "เกิดข้อผิดพลาด: " . $stmt->error;
            }

            $stmt->close();
        } else {
            echo "❌ อัปโหลดไม่สำเร็จ!";
        }
    } else {
        echo "❌ ไม่มีไฟล์หรือมีข้อผิดพลาดในการอัปโหลด";
    }
} else {

    $sql = "INSERT INTO member (MemberID, FullName, ProfileImage, Username, Email, Telephone, BirthDate, Occupation, Address, District, Subdistrict, Province, ZipCode)
VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
ON DUPLICATE KEY UPDATE
    MemberID = VALUES(memberID),
    FullName = VALUES(FullName),
    ProfileImage = VALUES(ProfileImage),
    Username = VALUES(Username),
    Email = VALUES(Email),
    Telephone = VALUES(Telephone),
    BirthDate = VALUES(BirthDate),
    Occupation = VALUES(Occupation),
    Address = VALUES(Address),
    District = VALUES(District),
    Subdistrict = VALUES(Subdistrict),
    Province = VALUES(Province),
    ZipCode = VALUES(ZipCode)";

    //  ใช้ Prepared Statement เพื่อความปลอดภัย (ป้องกัน SQL Injection)
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssssssssss", $member_id, $full_name, $file_name, $username, $email, $phone, $birthdate, $occupation, $address, $district, $subdistrict, $province, $zipcode);

    //  Execute
    if ($stmt->execute()) {
        echo "บันทึกข้อมูลสำเร็จ!";
    } else {
        echo "เกิดข้อผิดพลาด: " . $stmt->error;
    }

    $stmt->close();
}


?>
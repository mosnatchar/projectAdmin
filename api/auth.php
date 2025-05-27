<?php


// ✅ CORS
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Authorization, Content-Type");
header("Access-Control-Allow-Credentials: true");
header("Content-Type: application/json; charset=utf-8");

// ✅ OPTIONS (Preflight) Check
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204);
    exit;
}

require_once 'vendor/autoload.php'; // JWT autoload

use Firebase\JWT\JWT;
use Firebase\JWT\Key;




$secretKey = 'jwtsecret'; // คีย์ลับเดียวกับตอน encode JWT

// รับ Authorization header
$headers = getallheaders();
$authHeader = $headers['Authorization'] ?? '';

if (!$authHeader || !str_starts_with($authHeader, 'Bearer ')) {
    http_response_code(401);
    echo json_encode(['error' => 'Missing or invalid Authorization header']);
    exit;
}

//  แยกเอา token ออกมา
$token = str_replace('Bearer ', '', $authHeader);

//  ตรวจสอบ JWT
try {
    $decoded = JWT::decode($token, new Key($secretKey, 'HS256'));

    // ตรวจสอบวันหมดอายุ (ถ้ามี)
    if (isset($decoded->exp) && $decoded->exp < time()) {
        echo json_encode([
            'message' => 'Token expired',
            'user' => (array) $decoded
        ]);
    }

    // ถ้า token ถูกต้องและไม่หมดอายุ
    echo json_encode([
        'message' => 'Token valid',
        'user' => (array) $decoded
    ]);

} catch (Exception $e) {
    echo json_encode([
        'message' => 'Invalid token',
        'user' => [],
    ]);
}
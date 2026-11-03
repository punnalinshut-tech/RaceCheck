<?php
/**
 * lib/db.php — สร้าง PDO connection และเก็บใน $pdo
 * Database: race_registration (MySQL / MariaDB via XAMPP)
 */

// ========================================================
//  Database Configuration
//  - Local (XAMPP): host = 'localhost', user = 'root', pass = ''
//  - Production Server (HostAtom / Plesk): 
//    กรุณากรอกชื่อโฮสต์, ฐานข้อมูล, ผู้ใช้ และรหัสผ่าน ที่สร้างบน Server
// ========================================================
$db_host    = 'localhost';
$db_name    = 'race_registration';
$db_user    = 'root';
$db_pass    = '';
$db_charset = 'utf8mb4';

$dsn = "mysql:host={$db_host};dbname={$db_name};charset={$db_charset}";

$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
    PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci",
];

try {
    $pdo = new PDO($dsn, $db_user, $db_pass, $options);
} catch (PDOException $e) {
    // แสดง error แบบปลอดภัย (ไม่เปิดเผย credentials)
    http_response_code(500);
    exit('ไม่สามารถเชื่อมต่อฐานข้อมูลได้ กรุณาติดต่อผู้ดูแลระบบ');
}

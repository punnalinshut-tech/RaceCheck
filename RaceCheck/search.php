<?php
/**
 * search.php — Search API (JSON)
 * รับ POST request และ return JSON
 *
 * Parameters:
 *   mode       : 'participant' | 'employee'
 *   type       : 'id_card' | 'name'
 *   query      : string (ค่าที่ค้นหา)
 *   first_name : string (เฉพาะ mode=participant, type=name)
 *   last_name  : string (เฉพาะ mode=participant, type=name)
 */

header('Content-Type: application/json; charset=utf-8');
header('X-Content-Type-Options: nosniff');

require_once __DIR__ . '/lib/db.php';

// -------------------------------------------------------
//  Helpers
// -------------------------------------------------------
function json_error(string $msg, int $code = 400): void
{
    http_response_code($code);
    echo json_encode(['ok' => false, 'message' => $msg], JSON_UNESCAPED_UNICODE);
    exit;
}

function json_ok(array $data): void
{
    echo json_encode(['ok' => true, 'data' => $data], JSON_UNESCAPED_UNICODE);
    exit;
}

// -------------------------------------------------------
//  รับ input
// -------------------------------------------------------
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    json_error('Method not allowed', 405);
}

$mode       = trim($_POST['mode']       ?? '');
$type       = trim($_POST['type']       ?? '');
$query      = trim($_POST['query']      ?? '');
$first_name = trim($_POST['first_name'] ?? '');
$last_name  = trim($_POST['last_name']  ?? '');

// -------------------------------------------------------
//  Validate mode
// -------------------------------------------------------
if (!in_array($mode, ['participant', 'employee'], true)) {
    json_error('mode ไม่ถูกต้อง');
}

// -------------------------------------------------------
//  MODE: employee — ค้นหาด้วยรหัสพนักงาน (employee_code)
// -------------------------------------------------------
if ($mode === 'employee') {
    if ($query === '') json_error('กรุณากรอกรหัสพนักงาน');
    if (strlen($query) > 50) json_error('รหัสพนักงานยาวเกินไป');
    // อนุญาตเฉพาะ alphanumeric + dash + underscore
    if (!preg_match('/^[A-Za-z0-9\-_]+$/', $query)) {
        json_error('รหัสพนักงานต้องประกอบด้วยตัวอักษรและตัวเลขเท่านั้น');
    }

    $stmt = $pdo->prepare(
        'SELECT * FROM v_participants_public
         WHERE employee_code = ? LIMIT 10'
    );
    $stmt->execute([$query]);
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (empty($rows)) json_error('ไม่พบรายการที่คุณระบุ กรุณาลองใหม่อีกครั้ง', 404);
    json_ok($rows);
}

// -------------------------------------------------------
//  MODE: participant
// -------------------------------------------------------
if (!in_array($type, ['id_card', 'name'], true)) {
    json_error('type ไม่ถูกต้อง');
}

// --- ค้นหาด้วยเลขประจำตัว (citizen_id หรือ passport_no) ---
if ($type === 'id_card') {
    if ($query === '') json_error('กรุณากรอกเลขบัตรประชาชน');

    // ตัดขีดและช่องว่างออก
    $clean = preg_replace('/[\s\-]/', '', $query);

    // รับเฉพาะ:
    //   - ตัวเลข 13 หลัก    → citizen_id
    //   - Alphanumeric 6-20 → passport_no (ขึ้นต้นด้วยตัวอักษร)
    $is_thai_id  = preg_match('/^\d{13}$/', $clean);
    $is_passport = preg_match('/^[A-Za-z][A-Za-z0-9]{5,19}$/', $clean);

    if (!$is_thai_id && !$is_passport) {
        json_error('เลขบัตรประชาชนต้องมี 13 หลัก หรือเลขพาสปอร์ต (ตัวอักษร+ตัวเลข 6–20 อักขระ)');
    }

    if ($is_thai_id) {
        $stmt = $pdo->prepare(
            'SELECT * FROM v_participants_public
             WHERE citizen_id = ?
             LIMIT 10'
        );
        $stmt->execute([$clean]);
    } else {
        $stmt = $pdo->prepare(
            'SELECT * FROM v_participants_public
             WHERE passport_no = ?
             LIMIT 10'
        );
        $stmt->execute([$clean]);
    }
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (empty($rows)) json_error('ไม่พบรายการที่คุณระบุ กรุณาลองใหม่อีกครั้ง', 404);
    json_ok($rows);
}

// --- ค้นหาด้วยชื่อ-นามสกุล ---
if ($type === 'name') {
    // ต้องกรอกทั้งชื่อและนามสกุลเท่านั้น
    if ($first_name === '' || $last_name === '') {
        json_error('กรุณากรอกทั้งชื่อและนามสกุล');
    }

    // ห้ามยาวเกินไป
    if (mb_strlen($first_name) > 100 || mb_strlen($last_name) > 100) {
        json_error('ชื่อหรือนามสกุลยาวเกินไป');
    }

    // ค้นหาโดยต้องตรงทั้ง first_name และ last_name
    $sql = 'SELECT * FROM v_participants_public
            WHERE first_name LIKE ? AND last_name LIKE ?
            ORDER BY first_name, last_name
            LIMIT 20';

    $stmt = $pdo->prepare($sql);
    $stmt->execute(['%' . $first_name . '%', '%' . $last_name . '%']);
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (empty($rows)) json_error('ไม่พบรายการที่คุณระบุ กรุณาลองใหม่อีกครั้ง', 404);
    json_ok($rows);
}

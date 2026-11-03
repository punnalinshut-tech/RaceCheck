<?php
/**
 * result.php — หน้าแสดงผลลัพธ์การค้นหาข้อมูลการลงทะเบียน
 * รับ GET params จากหน้า index.php และดึงข้อมูลผู้สมัครมาแสดงผล
 */

header('X-Content-Type-Options: nosniff');

require_once __DIR__ . '/lib/db.php';

$event_name = 'Super C Chef Run 2026';

// --------------------------------------------------------
//  รับและทำความสะอาด Parameters
// --------------------------------------------------------
$mode       = trim($_GET['mode']       ?? '');
$type       = trim($_GET['type']       ?? '');
$query      = trim($_GET['query']      ?? '');
$first_name = trim($_GET['first_name'] ?? '');
$last_name  = trim($_GET['last_name']  ?? '');

// --------------------------------------------------------
//  ฟังก์ชัน Helpers สำหรับทำความสะอาดและจัดรูปแบบข้อมูล (Data Cleansing)
// --------------------------------------------------------
function safe(?string $s): string {
    if ($s === null) return '—';
    $t = trim($s);
    if ($t === '' || $t === '-' || strtoupper($t) === 'NULL' || strtoupper($t) === '#N/A') {
        return '—';
    }
    return htmlspecialchars($t, ENT_QUOTES, 'UTF-8');
}

function gender_label(?string $v): string {
    if ($v === null) return '—';
    $upper = strtoupper(trim($v));
    if ($upper === 'M' || $upper === 'ชาย') return 'ชาย';
    if ($upper === 'F' || $upper === 'หญิง') return 'หญิง';
    if ($upper === '' || $upper === '-' || $upper === 'NULL' || $upper === '#N/A') return '—';
    return htmlspecialchars(trim($v), ENT_QUOTES, 'UTF-8');
}

function format_sequence($seq): string {
    if ($seq === null || $seq === '') return '—';
    $t = trim((string)$seq);
    if ($t === '' || $t === '-' || strtoupper($t) === 'NULL' || strtoupper($t) === '#N/A') {
        return '—';
    }
    if (is_numeric($t)) {
        return sprintf('#%04d', (int)$t);
    }
    return str_starts_with($t, '#') ? $t : '#' . $t;
}

// --------------------------------------------------------
//  ค้นหาข้อมูลผู้สมัครจากฐานข้อมูล
// --------------------------------------------------------
$results  = [];
$error    = '';
$searched = false;

function do_search(PDO $pdo, string $mode, string $type, string $query, string $first_name, string $last_name): array {
    // ค้นหาด้วยรหัสพนักงาน (employee_code)
    if ($mode === 'employee') {
        if ($query === '') return ['error' => 'กรุณากรอกรหัสพนักงาน'];
        if (!preg_match('/^[A-Za-z0-9\-_]+$/', $query)) {
            return ['error' => 'รหัสพนักงานไม่ถูกต้อง'];
        }
        $stmt = $pdo->prepare(
            'SELECT * FROM v_participants_public WHERE employee_code = ? LIMIT 10'
        );
        $stmt->execute([$query]);
        $rows = $stmt->fetchAll();
        return $rows ? ['rows' => $rows] : ['error' => 'ไม่พบรายการที่คุณระบุ กรุณาลองใหม่อีกครั้ง'];
    }

    // ค้นหาด้วยเลขบัตรประชาชน หรือ พาสปอร์ต (citizen_id หรือ passport_no)
    if ($type === 'id_card') {
        $clean = preg_replace('/[\s\-]/', '', $query);
        if ($clean === '') return ['error' => 'กรุณากรอกเลขบัตรประชาชน / Passport (ที่ลงทะเบียนไว้)'];
        
        $isThaiId   = preg_match('/^\d{13}$/', $clean);
        $isPassport = preg_match('/^[A-Za-z][A-Za-z0-9]{5,19}$/', $clean);
        
        if (!$isThaiId && !$isPassport) {
            return ['error' => 'เลขบัตรประชาชนต้องมี 13 หลัก หรือเลขพาสปอร์ต (ตัวอักษร+ตัวเลข 6–20 อักขระ)'];
        }
        
        if ($isThaiId) {
            $stmt = $pdo->prepare('SELECT * FROM v_participants_public WHERE citizen_id = ? LIMIT 10');
            $stmt->execute([$clean]);
        } else {
            $stmt = $pdo->prepare('SELECT * FROM v_participants_public WHERE passport_no = ? LIMIT 10');
            $stmt->execute([$clean]);
        }
        $rows = $stmt->fetchAll();
        return $rows ? ['rows' => $rows] : ['error' => 'ไม่พบรายการที่คุณระบุ กรุณาลองใหม่อีกครั้ง'];
    }

    // ค้นหาด้วยชื่อ-นามสกุล
    if ($type === 'name') {
        if ($first_name === '' || $last_name === '') {
            return ['error' => 'กรุณากรอกทั้งชื่อและนามสกุล'];
        }

        $sql  = 'SELECT * FROM v_participants_public WHERE first_name LIKE ? AND last_name LIKE ? ORDER BY first_name, last_name LIMIT 20';
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['%' . $first_name . '%', '%' . $last_name . '%']);
        $rows = $stmt->fetchAll();
        return $rows ? ['rows' => $rows] : ['error' => 'ไม่พบรายการที่คุณระบุ กรุณาลองใหม่อีกครั้ง'];
    }

    return ['error' => 'ค้นหาไม่ถูกต้อง'];
}

if ($mode !== '') {
    $searched = true;
    $res = do_search($pdo, $mode, $type, $query, $first_name, $last_name);
    if (isset($res['error'])) {
        $error = $res['error'];
    } else {
        $results = $res['rows'];
    }
}
?>
<!DOCTYPE html>
<html lang="th">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Result — <?= safe($event_name) ?></title>
  <meta name="robots" content="noindex, nofollow">

  <link rel="icon" type="image/png" href="assets/image/Favicon.png">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Anuphan:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="assets/style.css?v=<?= filemtime(__DIR__ . '/assets/style.css') ?>">
  <style>
    body {
      background-color: #df1465 !important;
      background-image: url('assets/image/BG.png') !important;
      background-repeat: no-repeat !important;
      background-position: center center !important;
      background-size: cover !important;
      background-attachment: fixed !important;
    }
    @media (max-width: 768px) {
      body {
        background-image: url('assets/image/Mobile_BG.png') !important;
        background-position: center top !important;
        align-items: flex-start !important;
        padding-top: calc(max(155px, 20vh)) !important;
        padding-bottom: 24px !important;
      }
      .result-card {
        padding: 34px 22px 32px !important;
        border-radius: 18px !important;
      }
      .result-row {
        padding: 8px 0 !important;
      }
      .result-key {
        width: 44% !important;
        font-size: 0.82rem !important;
      }
      .result-val {
        width: 56% !important;
        font-size: 0.82rem !important;
      }
    }
  </style>
</head>
<body>

  <div class="page-wrapper">
    <div class="result-page-wrapper">

      <!-- Back button -->
      <div class="back-wrap">
        <a href="index.php" class="back-btn" id="back-btn">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
            <polyline points="15 18 9 12 15 6"/>
          </svg>
          กลับไปค้นหาใหม่
        </a>
      </div>

      <?php if ($error): ?>
      <!-- ===== ไม่พบข้อมูล ===== -->
      <div class="result-card result-card--error" role="main">
        <div class="result-icon-wrap">
          <div class="result-icon result-icon--error" aria-hidden="true">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#ffffff" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
              <line x1="18" y1="6" x2="6" y2="18"></line>
              <line x1="6" y1="6" x2="18" y2="18"></line>
            </svg>
          </div>
        </div>
        <h1 class="result-heading">ไม่พบข้อมูล</h1>
        <p class="result-subheading">Registration Verification</p>
        <p class="result-error-msg"><?= safe($error) ?></p>
        <a href="index.php" class="btn-back-home">ค้นหาใหม่อีกครั้ง</a>
      </div>

      <?php elseif (!$searched): ?>
      <!-- ===== ไม่มี params ===== -->
      <div class="result-card" role="main" style="text-align:center; padding: 48px 24px;">
        <p style="color:#888;">กรุณากลับไปที่หน้าค้นหา</p>
        <a href="index.php" class="btn-back-home" style="margin-top:20px;">กลับหน้าค้นหา</a>
      </div>

      <?php else: ?>
      <!-- ===== พบข้อมูล ===== -->
      <?php foreach ($results as $idx => $row): ?>
      <?php
        $first = trim($row['first_name'] ?? '');
        $last  = trim($row['last_name'] ?? '');
        $nameParts = [];
        if ($first !== '' && strtoupper($first) !== 'NULL' && $first !== '-') $nameParts[] = $first;
        if ($last !== '' && strtoupper($last) !== 'NULL' && $last !== '-') $nameParts[] = $last;
        $display_name = !empty($nameParts) ? htmlspecialchars(implode(' ', $nameParts), ENT_QUOTES, 'UTF-8') : '—';
        $seq_display  = format_sequence($row['sequence_no'] ?? null);
      ?>
      <div class="result-card" role="<?= $idx === 0 ? 'main' : 'article' ?>">

        <!-- Sequence No Badge -->
        <div class="result-seq-wrap">
          <span class="result-seq-badge" aria-label="ลำดับที่ <?= htmlspecialchars($seq_display, ENT_QUOTES, 'UTF-8') ?>"><?= htmlspecialchars($seq_display, ENT_QUOTES, 'UTF-8') ?></span>
        </div>

        <!-- Title -->
        <h1 class="result-heading">ข้อมูลการลงทะเบียน</h1>
        <p class="result-subheading">Registration Verification</p>

        <!-- Data rows -->
        <div class="result-table">

          <div class="result-row">
            <span class="result-key">ชื่อ - นามสกุล</span>
            <span class="result-val"><?= $display_name ?></span>
          </div>

          <div class="result-row">
            <span class="result-key">ระยะวิ่ง / ประเภท</span>
            <span class="result-val"><?= safe($row['race_distance'] ?? null) ?></span>
          </div>

          <div class="result-row">
            <span class="result-key">รุ่นอายุ</span>
            <span class="result-val"><?= safe($row['age_group'] ?? null) ?></span>
          </div>

          <div class="result-row">
            <span class="result-key">เพศ</span>
            <span class="result-val"><?= gender_label($row['gender'] ?? null) ?></span>
          </div>

          <div class="result-row">
            <span class="result-key">ไซส์เสื้อ</span>
            <span class="result-val"><?= safe($row['shirt_size'] ?? null) ?></span>
          </div>

          <div class="result-row">
            <span class="result-key">สังกัด</span>
            <span class="result-val"><?= safe($row['affiliation'] ?? null) ?></span>
          </div>

          <div class="result-row">
            <span class="result-key">การรับเสื้อ</span>
            <span class="result-val"><?= safe($row['shirt_delivery'] ?? $row['shirt_pickup'] ?? null) ?></span>
          </div>

          <div class="result-row">
            <span class="result-key">ของที่ระลึก</span>
            <span class="result-val"><?= safe($row['souvenir'] ?? null) ?></span>
          </div>

        </div><!-- /.result-table -->
      </div><!-- /.result-card -->
      <?php endforeach; ?>

      <?php if (count($results) > 1): ?>
      <p class="result-count">พบข้อมูลทั้งหมด <?= count($results) ?> รายการ</p>
      <?php endif; ?>

      <?php endif; ?>

    </div><!-- /.result-page-wrapper -->
  </div><!-- /.page-wrapper -->

</body>
</html>

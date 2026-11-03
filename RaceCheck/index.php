<?php
/**
 * RaceCheck — หน้าค้นหาข้อมูลการสมัคร
 * index.php
 */

$event_name  = 'Super C Chef Run 2026';
?>
<!DOCTYPE html>
<html lang="th">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <!-- SEO -->
  <title>Check Status — <?= htmlspecialchars($event_name) ?></title>
  <meta name="description" content="ตรวจสอบสถานะการสมัครแข่งขันวิ่ง <?= htmlspecialchars($event_name) ?> ค้นหาด้วยชื่อ-นามสกุล หรือหมายเลขบัตรประชาชน">
  <meta name="robots" content="noindex, nofollow">

  <!-- Favicon -->
  <link rel="icon" type="image/png" href="assets/image/Favicon.png">

  <!-- Google Fonts -->
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
      .search-card {
        padding: 38px 22px 40px !important;
        border-radius: 20px !important;
        max-width: 380px !important;
      }
      .card-header {
        margin-bottom: 22px !important;
      }
      .card-title {
        font-size: 1.35rem !important;
        line-height: 1.25 !important;
      }
      .card-subtitle {
        font-size: 0.84rem !important;
        margin-top: 4px !important;
      }
      .search-section {
        margin-bottom: 0 !important;
      }
      .section-label {
        font-size: 0.85rem !important;
        margin-bottom: 8px !important;
      }
      .field {
        padding: 11px 18px !important;
        font-size: 0.90rem !important;
      }
      .field-hint {
        font-size: 0.75rem !important;
        min-height: 0 !important;
        padding: 2px 4px 0 !important;
      }
      .field-hint:empty {
        display: none !important;
      }
      .input-wrap--stacked {
        margin-top: 8px !important;
      }
      .divider {
        margin: 20px 0 16px !important;
      }
      .btn-confirm {
        width: 140px !important;
        padding: 10px 0 !important;
        margin: 14px auto 0 !important;
        font-size: 0.92rem !important;
      }
    }
  </style>
</head>
<body>

  <div class="page-wrapper">

    <!-- ===== CARD CONTAINER ===== -->
    <div class="search-card" role="main">

      <!-- Header -->
      <div class="card-header">
        <h1 class="card-title">ตรวจสอบข้อมูลการสมัคร</h1>
        <p class="card-subtitle">Registration Verification</p>
      </div>

      <!-- Section 1: ตรวจสอบจากเลขที่บัตรประชาชน -->
      <div class="search-section">
        <p class="section-label">เลขบัตรประชาชน / Passport (ที่ลงทะเบียนไว้)</p>
        <form id="form-id-card" class="search-form" novalidate>
          <input type="hidden" name="mode" value="participant">
          <input type="hidden" name="type" value="id_card">
          <div class="input-wrap">
            <input
              class="field"
              type="text"
              id="input-id-card"
              name="query"
              placeholder="กรอกเลขบัตรประชาชนหรือ Passport ที่ลงทะเบียนไว้"
              autocomplete="off"
              autocorrect="off"
              spellcheck="false"
              inputmode="text"
              maxlength="20"
            >
          </div>
          <p class="field-hint error-hint" id="hint-id-card" aria-live="polite"></p>
          <button type="submit" class="btn-confirm" id="btn-id-card">ยืนยัน</button>
        </form>
      </div>

      <div class="divider"></div>

      <!-- Section 2: ตรวจสอบจากรหัสพนักงาน -->
      <div class="search-section">
        <p class="section-label">รหัสพนักงาน (ที่ลงทะเบียนไว้)</p>
        <form id="form-employee" class="search-form" novalidate>
          <input type="hidden" name="mode" value="employee">
          <input type="hidden" name="type" value="employee_code">
          <div class="input-wrap">
            <input
              class="field"
              type="text"
              id="input-employee"
              name="query"
              placeholder="กรอกรหัสพนักงาน"
              autocomplete="off"
              autocorrect="off"
              spellcheck="false"
              maxlength="50"
            >
          </div>
          <p class="field-hint error-hint" id="hint-employee" aria-live="polite"></p>
          <button type="submit" class="btn-confirm" id="btn-employee">ยืนยัน</button>
        </form>
      </div>

      <div class="divider"></div>

      <!-- Section 3: ตรวจสอบจากชื่อ และนามสกุล -->
      <div class="search-section">
        <p class="section-label">ชื่อ–นามสกุล (ตามที่ลงทะเบียนไว้)</p>
        <form id="form-name" class="search-form" novalidate>
          <input type="hidden" name="mode" value="participant">
          <input type="hidden" name="type" value="name">
          <div class="input-wrap">
            <input
              class="field"
              type="text"
              id="input-first-name"
              name="first_name"
              placeholder="กรอกชื่อจริง"
              autocomplete="off"
              autocorrect="off"
              spellcheck="false"
              maxlength="100"
            >
          </div>
          <div class="input-wrap input-wrap--stacked">
            <input
              class="field"
              type="text"
              id="input-last-name"
              name="last_name"
              placeholder="กรอกนามสกุล"
              autocomplete="off"
              autocorrect="off"
              spellcheck="false"
              maxlength="100"
            >
          </div>
          <p class="field-hint error-hint" id="hint-name" aria-live="polite"></p>
          <button type="submit" class="btn-confirm" id="btn-name">ยืนยัน</button>
        </form>
      </div>

    </div><!-- /.search-card -->

  </div><!-- /.page-wrapper -->

  <!-- ===== JAVASCRIPT ===== -->
  <script>
  (function () {
    'use strict';

    /* ---- Validation helpers ---- */
    function setHint(el, msg) {
      el.textContent = msg;
    }
    function clearHint(el) {
      el.textContent = '';
    }

    /* ---- Navigate to result page ---- */
    function goToResult(params) {
      const url = 'result.php?' + new URLSearchParams(params).toString();
      window.location.href = url;
    }

    /* ---- Submit with verification (AJAX check before navigating) ---- */
    function submitWithCheck(btn, hintEl, inputEl, params, customNotFoundMsg) {
      clearHint(hintEl);
      var origText = btn.textContent;
      btn.disabled = true;
      btn.textContent = 'กำลังค้นหา...';

      var fd = new FormData();
      for (var k in params) {
        fd.append(k, params[k]);
      }

      fetch('search.php', {
        method: 'POST',
        body: fd
      })
      .then(function (res) {
        return res.json().catch(function () {
          return { ok: false, message: 'เกิดข้อผิดพลาดในการเชื่อมต่อ' };
        });
      })
      .then(function (data) {
        btn.disabled = false;
        btn.textContent = origText;
        if (!data || !data.ok) {
          var msg = (data && data.message) ? data.message : (customNotFoundMsg || 'ไม่พบรายการที่คุณระบุ กรุณาลองใหม่อีกครั้ง');
          setHint(hintEl, '* ' + msg);
          if (inputEl) inputEl.focus();
        } else {
          goToResult(params);
        }
      })
      .catch(function () {
        btn.disabled = false;
        btn.textContent = origText;
        setHint(hintEl, '* เกิดข้อผิดพลาดในการเชื่อมต่อ กรุณาลองใหม่อีกครั้ง');
        if (inputEl) inputEl.focus();
      });
    }

    /* ---- 1. Form: เลขที่บัตรประชาชน ---- */
    const formIdCard  = document.getElementById('form-id-card');
    const inputIdCard = document.getElementById('input-id-card');
    const hintIdCard  = document.getElementById('hint-id-card');
    const btnIdCard   = document.getElementById('btn-id-card');

    inputIdCard.addEventListener('input', function () {
      clearHint(hintIdCard);
    });

    formIdCard.addEventListener('submit', function (e) {
      e.preventDefault();
      clearHint(hintIdCard);

      var raw   = inputIdCard.value.trim();
      var clean = raw.replace(/[\s\-]/g, '');

      if (clean === '') {
        setHint(hintIdCard, '* กรุณากรอกเลขบัตรประชาชน / Passport');
        inputIdCard.focus();
        return;
      }

      var isNationalId = /^\d{13}$/.test(clean);
      var isPassport   = /^[A-Za-z][A-Za-z0-9]{5,19}$/.test(clean);

      if (!isNationalId && !isPassport) {
        setHint(hintIdCard, '* กรุณากรอกเลขบัตรประชาชนให้ครบ 13 หลัก');
        inputIdCard.focus();
        return;
      }

      submitWithCheck(btnIdCard, hintIdCard, inputIdCard, {
        mode: 'participant',
        type: 'id_card',
        query: clean
      });
    });

    /* ---- 2. Form: รหัสพนักงาน ---- */
    const formEmployee    = document.getElementById('form-employee');
    const inputEmployee   = document.getElementById('input-employee');
    const hintEmployee    = document.getElementById('hint-employee');
    const btnEmployee     = document.getElementById('btn-employee');

    inputEmployee.addEventListener('input', function () {
      clearHint(hintEmployee);
    });

    formEmployee.addEventListener('submit', function (e) {
      e.preventDefault();
      clearHint(hintEmployee);

      var code = inputEmployee.value.trim();

      if (code === '') {
        setHint(hintEmployee, '* กรุณากรอกรหัสพนักงาน');
        inputEmployee.focus();
        return;
      }
      if (!/^[A-Za-z0-9\-_]+$/.test(code)) {
        setHint(hintEmployee, '* กรุณากรอกรหัสพนักงานเป็นตัวเลข 10 หลักเท่านั้น');
        inputEmployee.focus();
        return;
      }

      submitWithCheck(btnEmployee, hintEmployee, inputEmployee, {
        mode: 'employee',
        query: code
      });
    });

    /* ---- 3. Form: ชื่อ-นามสกุล ---- */
    const formName      = document.getElementById('form-name');
    const inputFirst    = document.getElementById('input-first-name');
    const inputLast     = document.getElementById('input-last-name');
    const hintName      = document.getElementById('hint-name');
    const btnName       = document.getElementById('btn-name');

    // ล้างข้อความเตือนเมื่อผู้ใช้เริ่มพิมพ์
    [inputFirst, inputLast].forEach(function (inp) {
      inp.addEventListener('input', function () {
        clearHint(hintName);
      });
    });

    formName.addEventListener('submit', function (e) {
      e.preventDefault();
      clearHint(hintName);

      var fn = inputFirst.value.trim();
      var ln = inputLast.value.trim();

      if (fn === '' || ln === '') {
        setHint(hintName, '* กรุณากรอกทั้งชื่อและนามสกุล');
        if (fn === '') {
          inputFirst.focus();
        } else {
          inputLast.focus();
        }
        return;
      }

      submitWithCheck(btnName, hintName, inputFirst, {
        mode: 'participant',
        type: 'name',
        first_name: fn,
        last_name: ln
      });
    });

  })();
  </script>

</body>
</html>

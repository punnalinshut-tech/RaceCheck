-- ============================================================
--  RaceCheck - Database Schema
--  File: schema.sql
--  Database: race_registration
--  Engine: InnoDB | Charset: utf8mb4
--  Compatible: MySQL 5.7+ / MariaDB 10.3+ (XAMPP)
-- ============================================================

-- ลบและสร้างฐานข้อมูลใหม่
DROP DATABASE IF EXISTS `race_registration`;
CREATE DATABASE `race_registration`
  DEFAULT CHARACTER SET utf8mb4
  DEFAULT COLLATE utf8mb4_unicode_ci;

USE `race_registration`;

-- ============================================================
--  TABLE: participants
--  ตรงตามโครงสร้าง Excel (reference/registration_structure.xlsx)
--  25 คอลัมน์ เรียงตามลำดับสำหรับ Import CSV ผ่าน phpMyAdmin
-- ============================================================
DROP TABLE IF EXISTS `participants`;

CREATE TABLE `participants` (

  -- 1. ลำดับ
  `sequence_no`     INT DEFAULT NULL                COMMENT 'ลำดับที่',

  -- 2. รหัสบัตรประชาชน (ข้อ 6: VARCHAR)
  `citizen_id`      VARCHAR(20) DEFAULT NULL        COMMENT 'เลขบัตรประชาชน 13 หลัก',

  -- 3. พาสปอร์ต (ข้อ 6: VARCHAR)
  `passport_no`     VARCHAR(50) DEFAULT NULL        COMMENT 'เลขพาสปอร์ต',

  -- 4. รหัสพนักงาน (ข้อ 6: VARCHAR)
  `employee_code`   VARCHAR(50) DEFAULT NULL        COMMENT 'รหัสพนักงาน',

  -- 5-6. ชื่อ - นามสกุล
  `first_name`      VARCHAR(100) DEFAULT NULL       COMMENT 'ชื่อ',
  `last_name`       VARCHAR(100) DEFAULT NULL       COMMENT 'นามสกุล',

  -- 7. เพศ
  `gender`          VARCHAR(10) DEFAULT NULL        COMMENT 'เพศ (F / M หรือ ชาย / หญิง)',

  -- 8. ไซส์เสื้อ
  `shirt_size`      VARCHAR(20) DEFAULT NULL        COMMENT 'ไซส์เสื้อ (S, M, L, XL, 2XL)',

  -- 9. ระยะวิ่ง
  `race_distance`   VARCHAR(100) DEFAULT NULL       COMMENT 'ระยะวิ่ง/ประเภท เช่น VR RUN, 5 KM, 10.5 KM, VIP 3 KM',

  -- 10. รุ่นอายุ
  `age_group`       VARCHAR(100) DEFAULT NULL       COMMENT 'รุ่นอายุ',

  -- 11. สังกัด
  `affiliation`     VARCHAR(200) DEFAULT NULL       COMMENT 'สังกัด / ชมรม / หน่วยงาน',

  -- 12. การรับเสื้อ
  `shirt_delivery`  VARCHAR(100) DEFAULT NULL       COMMENT 'วิธีรับเสื้อ เช่น จัดส่ง / รับด้วยตนเอง',

  -- 13. กระเป๋าที่ระลึก
  `souvenir`        VARCHAR(100) DEFAULT NULL       COMMENT 'กระเป๋าที่ระลึก เช่น ได้รับกระเป๋า / ไม่ได้รับกระเป๋า',

  -- 14. วันเกิด (ข้อ 7: VARCHAR(20) ชั่วคราว เพื่อรองรับการ Import วันที่จาก Excel)
  `birth_date`      VARCHAR(20) DEFAULT NULL        COMMENT 'วันเกิด (รองรับ Date string และ Serial จาก Excel)',

  -- 15. ชื่อผู้รับ
  `recipient_name`  VARCHAR(150) DEFAULT NULL       COMMENT 'ชื่อผู้รับพัสดุ',

  -- 16-23. ข้อมูลที่อยู่จัดส่ง
  `address`         VARCHAR(255) DEFAULT NULL       COMMENT 'รายละเอียดที่อยู่ (บ้านเลขที่ อาคาร ชั้น)',
  `moo`             VARCHAR(50) DEFAULT NULL        COMMENT 'หมู่',
  `village`         VARCHAR(150) DEFAULT NULL       COMMENT 'หมู่บ้าน / คอนโด / อาคาร',
  `soi`             VARCHAR(100) DEFAULT NULL       COMMENT 'ซอย',
  `road`            VARCHAR(100) DEFAULT NULL       COMMENT 'ถนน',
  `subdistrict`     VARCHAR(100) DEFAULT NULL       COMMENT 'แขวง/ตำบล',
  `district`        VARCHAR(100) DEFAULT NULL       COMMENT 'เขต/อำเภอ',
  `province`        VARCHAR(100) DEFAULT NULL       COMMENT 'จังหวัด',

  -- 24. รหัสไปรษณีย์ (ข้อ 6: VARCHAR)
  `postal_code`     VARCHAR(20) DEFAULT NULL        COMMENT 'รหัสไปรษณีย์',

  -- 25. เบอร์โทร (ข้อ 6: VARCHAR)
  `phone`           VARCHAR(50) DEFAULT NULL        COMMENT 'เบอร์โทรศัพท์'

) ENGINE=InnoDB
  DEFAULT CHARSET=utf8mb4
  COLLATE=utf8mb4_unicode_ci
  COMMENT='ตารางข้อมูลผู้สมัครแข่งขันวิ่ง (25 คอลัมน์ตรงตาม CSV/Excel)';

-- ============================================================
--  INDEXES
--  เพื่อประสิทธิภาพในการค้นหาข้อมูล
-- ============================================================
CREATE INDEX `idx_citizen_id`    ON `participants` (`citizen_id`);
CREATE INDEX `idx_passport_no`   ON `participants` (`passport_no`);
CREATE INDEX `idx_employee_code` ON `participants` (`employee_code`);
CREATE INDEX `idx_first_name`    ON `participants` (`first_name`);
CREATE INDEX `idx_last_name`     ON `participants` (`last_name`);

-- ============================================================
--  VIEW: v_participants_public
--  สำหรับระบบค้นหา — แสดงข้อมูลทั่วไป และ alias shirt_delivery เป็น shirt_pickup
-- ============================================================
CREATE OR REPLACE VIEW `v_participants_public` AS
SELECT
  `sequence_no`,
  `citizen_id`,
  `passport_no`,
  `employee_code`,
  `first_name`,
  `last_name`,
  `gender`,
  `shirt_size`,
  `race_distance`,
  `age_group`,
  `affiliation`,
  `shirt_delivery`,
  `shirt_delivery` AS `shirt_pickup`,
  `souvenir`
FROM `participants`;
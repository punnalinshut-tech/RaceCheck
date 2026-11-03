-- ============================================================
--  RaceCheck - Seed Data สำหรับทดสอบบน Local (XAMPP เท่านั้น)
--  File: seed.sql
--  คำเตือน: ห้าม Import ไฟล์นี้บน Production Server
--  ข้อมูลสมมติทั้งหมด 25 คอลัมน์ตรงตาม schema.sql ล่าสุด
-- ============================================================

USE `race_registration`;

INSERT INTO `participants` (
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
  `souvenir_bag`,
  `birth_date`,
  `recipient_name`,
  `address`,
  `moo`,
  `village`,
  `soi`,
  `road`,
  `subdistrict`,
  `district`,
  `province`,
  `postal_code`,
  `phone`
) VALUES
-- 1. ค้นหาด้วยบัตรประชาชน 13 หลัก
(1, '1100000000011', NULL, NULL, 'สมชาย', 'ใจดี', 'ชาย', 'M', '10.5 KM', 'ทั่วไปชาย 30-39 ปี', 'Thairun Club', 'จัดส่ง', 'ได้รับกระเป๋า', '15/03/1990', 'สมชาย ใจดี', '99/1 อาคารตัวอย่าง', '3', NULL, 'ซอยสุขุมวิท 10', 'สุขุมวิท', 'คลองเตย', 'คลองเตย', 'กรุงเทพมหานคร', '10110', '0812345671'),

-- 2. ค้นหาด้วยบัตรประชาชน (รับด้วยตนเอง)
(2, '1200000000022', NULL, NULL, 'วิภาดา', 'รักสุขใจ', 'หญิง', 'S', 'VR RUN', 'ทั่วไปหญิง 40-49 ปี', 'ISA', 'รับด้วยตนเอง', 'ไม่ได้รับกระเป๋า', '22/07/1985', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0823456782'),

-- 3. ค้นหาด้วยรหัสพนักงาน
(3, NULL, NULL, '5501000003', 'ประวิทย์', 'ดาวเหนือ', 'ชาย', 'L', 'VIP 3 KM', '40 ปีขึ้นไป', 'UC1', 'จัดส่ง', 'ได้รับกระเป๋า', '08/11/1978', 'ประวิทย์ ดาวเหนือ', '241/33 ตวงเงินอพาทเมนต์', '-', 'ชั้น 7', '28', 'พระราม 6', 'พญาไท', 'พญาไท', 'กรุงเทพมหานคร', '10400', '0834567893'),

-- 4. ค้นหาด้วยรหัสพนักงาน (รับด้วยตนเอง)
(4, NULL, NULL, '5806510004', 'นภัสสร', 'แสงดาว', 'หญิง', 'XS', '5 KM', '30-39 ปี', NULL, 'รับด้วยตนเอง', 'ได้รับกระเป๋า', '30/05/2002', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),

-- 5. ค้นหาด้วยพาสปอร์ต
(5, NULL, 'AA1234567', NULL, 'Kenji', 'Yamamoto', 'ชาย', 'M', '10.5 KM', '30-39 ปี', 'UC2', 'จัดส่ง', 'ได้รับกระเป๋า', '01/01/1988', 'Kenji Yamamoto', '88 Sukhumvit Road', NULL, NULL, 'Soi 11', 'Sukhumvit', 'Khlong Toei Nuea', 'Watthana', 'Bangkok', '10110', '0867890126');
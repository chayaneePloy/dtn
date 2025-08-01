-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 01, 2025 at 11:57 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `budget_dtn`
--

-- --------------------------------------------------------

--
-- Table structure for table `budget_detail`
--

CREATE TABLE `budget_detail` (
  `id_detail` int(11) NOT NULL,
  `budget_item_id` int(11) NOT NULL,
  `detail_name` varchar(100) NOT NULL,
  `requested_amount` decimal(12,2) NOT NULL,
  `approved_amount` decimal(12,2) NOT NULL,
  `percentage` decimal(5,2) GENERATED ALWAYS AS (`approved_amount` / `requested_amount` * 100) STORED,
  `fiscal_year` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_thai_520_w2;

--
-- Dumping data for table `budget_detail`
--

INSERT INTO `budget_detail` (`id_detail`, `budget_item_id`, `detail_name`, `requested_amount`, `approved_amount`, `fiscal_year`) VALUES
(1, 1, 'ค่าเครื่อง PC Notebook  Printer  Scanner', 1000000.00, 15000.00, 2024),
(2, 1, 'ระบบบริหารทรัพยากรองค์กร', 800000.00, 0.00, 2024),
(3, 1, 'เว็บไซต์ (www.dtn.go.th)', 300000.00, 0.00, 2024),
(4, 1, 'ระบบตรวจสอบการเข้าถึงข้อมูลส่วนบุคคล', 500000.00, 0.00, 2025),
(5, 1, 'ระบบติดตามผลการเจรจาการค้าระหว่างประเทศ', 700000.00, 0.00, 2024),
(6, 1, 'ระบบการเรียนการสอนด้านการค้าระหว่างประเทศแบบออนไลน์', 420000.00, 0.00, 2024),
(7, 1, 'ระบบห้องสมุดอิเล็กทรอนิกส์', 110200.00, 0.00, 2024),
(8, 1, 'ระบบโมบายแอพลิเคชัน FTA Choice', 450000.00, 0.00, 2025),
(9, 1, 'ระบบคลังข้อมูลทางการค้าของไทย', 160000.00, 0.00, 2024),
(10, 1, 'ระบบสิทธิประโยชน์ทางภาษี', 400000.00, 0.00, 2025),
(11, 1, 'ระบบไปรษณีย์อิเล็กทรอนิกส์', 489000.00, 0.00, 2024),
(12, 1, 'ระบบเครือข่ายและอุปกรณ์', 650000.00, 0.00, 2024),
(13, 1, 'ระบบติดตามการค้าระหว่างประเทศของไทย', 694400.00, 0.00, 2024),
(14, 1, 'ต่ออายุการใช้งานโดเมนเนม และใบรับรองความปลอดภัยอิเล็กทรอนิกส์ (SSL Certificate)', 200000.00, 0.00, 2025),
(15, 2, 'เครื่องคอมพิวเตอร์พกพาแบบหน้าจอสัมผัส (รวมปากกา) ', 400000.00, 0.00, 2025),
(16, 2, 'ชุดโปรแกรม สร้าง แก้ไข และจัดการเอกสารไฟล์ PDF  230 ชุด\r\n', 489000.00, 0.00, 2024),
(17, 2, 'ระบบเครือข่ายและอุปกรณ์', 650000.00, 0.00, 2024),
(18, 3, 'ค่าจ้างที่ปรึกษาด้านเทคโนโลยีสารสนเทศและการสื่อสาร กรมเจรจาการค้าระหว่างประเทศ', 1500000.00, 630000.00, 2024),
(19, 4, 'ค่าบริการหรือจัดหาระบบคลาวด์/ระบบสารสนเทศสำหรับการใช้คลาวด์ ', 590400.00, 274700.00, 2025);

-- --------------------------------------------------------

--
-- Table structure for table `budget_items`
--

CREATE TABLE `budget_items` (
  `id` int(11) NOT NULL,
  `item_name` varchar(100) NOT NULL,
  `requested_amount` decimal(12,2) NOT NULL,
  `approved_amount` decimal(12,2) NOT NULL,
  `percentage` decimal(5,2) GENERATED ALWAYS AS (`approved_amount` / `requested_amount` * 100) STORED,
  `fiscal_year` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_thai_520_w2;

--
-- Dumping data for table `budget_items`
--

INSERT INTO `budget_items` (`id`, `item_name`, `requested_amount`, `approved_amount`, `fiscal_year`) VALUES
(1, 'งบดำเนินงาน', 7274400.00, 6791000.00, 2024),
(2, 'งบลงทุน', 1832300.00, 1832300.00, 2024),
(3, 'งบรายจ่ายอื่น', 1500000.00, 630000.00, 2025),
(4, 'งบบูรณาการ', 1500000.00, 630000.00, 2025);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `budget_detail`
--
ALTER TABLE `budget_detail`
  ADD PRIMARY KEY (`id_detail`),
  ADD KEY `budget_item_id` (`budget_item_id`);

--
-- Indexes for table `budget_items`
--
ALTER TABLE `budget_items`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `budget_detail`
--
ALTER TABLE `budget_detail`
  MODIFY `id_detail` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `budget_items`
--
ALTER TABLE `budget_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `budget_detail`
--
ALTER TABLE `budget_detail`
  ADD CONSTRAINT `budget_detail_ibfk_1` FOREIGN KEY (`budget_item_id`) REFERENCES `budget_items` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

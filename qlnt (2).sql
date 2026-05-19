-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 24, 2026 at 09:28 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `qlnt`
--

-- --------------------------------------------------------

--
-- Table structure for table `cam_bien`
--

CREATE TABLE `cam_bien` (
  `idCamBien` int(11) NOT NULL,
  `idThietBi` int(11) DEFAULT NULL,
  `tenCamBien` varchar(100) DEFAULT NULL,
  `loaiCamBien` varchar(50) DEFAULT NULL,
  `donVi` varchar(20) DEFAULT NULL,
  `trangThai` smallint(6) DEFAULT 1,
  `ngayTao` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cam_bien`
--

INSERT INTO `cam_bien` (`idCamBien`, `idThietBi`, `tenCamBien`, `loaiCamBien`, `donVi`, `trangThai`, `ngayTao`) VALUES
(1, 1, 'Nhiệt độ', 'temp', '°C', 1, '2026-03-19 21:36:26'),
(2, 1, 'Độ ẩm', 'hum', '%', 1, '2026-03-19 21:36:26'),
(3, 1, 'Gas', 'gas', 'ppm', 1, '2026-03-19 21:36:26'),
(4, 1, 'Chuyển động', 'pir', 'bool', 1, '2026-03-19 21:36:26');

-- --------------------------------------------------------

--
-- Table structure for table `canh_bao`
--

CREATE TABLE `canh_bao` (
  `idCanhBao` int(11) NOT NULL,
  `idCamBien` int(11) DEFAULT NULL,
  `noiDung` varchar(256) DEFAULT NULL,
  `mucDo` varchar(50) DEFAULT NULL,
  `giaTriDo` float DEFAULT NULL,
  `nguongViPham` float DEFAULT NULL,
  `trangThaiXuLy` smallint(6) DEFAULT 0,
  `ngayTao` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cau_hinh`
--

CREATE TABLE `cau_hinh` (
  `idCauHinh` int(11) NOT NULL,
  `idPhong` int(11) DEFAULT NULL,
  `nhietDoMax` float DEFAULT NULL,
  `nhietDoMin` float DEFAULT NULL,
  `doAmMax` float DEFAULT NULL,
  `doAmMin` float DEFAULT NULL,
  `gasMax` float DEFAULT NULL,
  `khoiBaoChay` float DEFAULT NULL,
  `ngayCapNhat` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cau_hinh`
--

INSERT INTO `cau_hinh` (`idCauHinh`, `idPhong`, `nhietDoMax`, `nhietDoMin`, `doAmMax`, `doAmMin`, `gasMax`, `khoiBaoChay`, `ngayCapNhat`) VALUES
(1, 1, 35, NULL, 80, NULL, 300, NULL, '2026-03-19 21:36:35');

-- --------------------------------------------------------

--
-- Table structure for table `du_lieu_cam_bien`
--

CREATE TABLE `du_lieu_cam_bien` (
  `idDuLieu` int(11) NOT NULL,
  `idCamBien` int(11) DEFAULT NULL,
  `giaTri` float DEFAULT NULL,
  `thoiGianDo` timestamp NOT NULL DEFAULT current_timestamp(),
  `trangThai` smallint(6) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `du_lieu_cam_bien`
--

INSERT INTO `du_lieu_cam_bien` (`idDuLieu`, `idCamBien`, `giaTri`, `thoiGianDo`, `trangThai`) VALUES
(1, 1, 24, '2026-03-20 03:33:42', 1),
(2, 2, 40, '2026-03-20 03:33:42', 1),
(3, 3, 342, '2026-03-20 03:33:42', 1),
(4, 4, 0, '2026-03-20 03:33:42', 1),
(5, 1, 24, '2026-03-20 03:36:31', 1),
(6, 2, 40, '2026-03-20 03:36:31', 1),
(7, 3, 393, '2026-03-20 03:36:31', 1),
(8, 4, 0, '2026-03-20 03:36:31', 1),
(9, 1, 24, '2026-03-20 03:36:36', 1),
(10, 2, 40, '2026-03-20 03:36:36', 1),
(11, 3, 346, '2026-03-20 03:36:36', 1),
(12, 4, 0, '2026-03-20 03:36:36', 1),
(13, 1, 24, '2026-03-20 03:36:39', 1),
(14, 2, 40, '2026-03-20 03:36:39', 1),
(15, 3, 202, '2026-03-20 03:36:39', 1),
(16, 4, 0, '2026-03-20 03:36:39', 1),
(17, 1, 24, '2026-03-20 03:54:29', 1),
(18, 2, 40, '2026-03-20 03:54:29', 1),
(19, 3, 325, '2026-03-20 03:54:29', 1),
(20, 4, 0, '2026-03-20 03:54:29', 1),
(21, 1, 24, '2026-03-20 03:54:33', 1),
(22, 2, 40, '2026-03-20 03:54:33', 1),
(23, 3, 228, '2026-03-20 03:54:33', 1),
(24, 4, 0, '2026-03-20 03:54:33', 1),
(25, 1, 24, '2026-03-20 03:54:38', 1),
(26, 2, 40, '2026-03-20 03:54:38', 1),
(27, 3, 219, '2026-03-20 03:54:38', 1),
(28, 4, 0, '2026-03-20 03:54:38', 1),
(29, 1, 24, '2026-03-20 03:54:41', 1),
(30, 2, 40, '2026-03-20 03:54:41', 1),
(31, 3, 207, '2026-03-20 03:54:41', 1),
(32, 4, 0, '2026-03-20 03:54:41', 1),
(33, 1, 24, '2026-03-20 03:54:53', 1),
(34, 2, 40, '2026-03-20 03:54:53', 1),
(35, 3, 358, '2026-03-20 03:54:53', 1),
(36, 4, 0, '2026-03-20 03:54:53', 1),
(37, 1, 24, '2026-03-20 03:54:59', 1),
(38, 2, 40, '2026-03-20 03:54:59', 1),
(39, 3, 380, '2026-03-20 03:54:59', 1),
(40, 4, 0, '2026-03-20 03:54:59', 1),
(41, 1, 24, '2026-03-20 03:55:03', 1),
(42, 2, 40, '2026-03-20 03:55:03', 1),
(43, 3, 287, '2026-03-20 03:55:03', 1),
(44, 4, 0, '2026-03-20 03:55:03', 1),
(45, 1, 24, '2026-03-20 03:55:07', 1),
(46, 2, 40, '2026-03-20 03:55:07', 1),
(47, 3, 354, '2026-03-20 03:55:07', 1),
(48, 4, 0, '2026-03-20 03:55:07', 1),
(49, 1, 48.3, '2026-03-20 09:16:52', 1),
(50, 2, 63, '2026-03-20 09:16:52', 1),
(51, 3, 3628, '2026-03-20 09:16:52', 1),
(52, 4, 0, '2026-03-20 09:16:52', 1),
(53, 1, 48.3, '2026-03-20 09:16:56', 1),
(54, 2, 63, '2026-03-20 09:16:56', 1),
(55, 3, 3628, '2026-03-20 09:16:56', 1),
(56, 4, 0, '2026-03-20 09:16:56', 1),
(57, 1, 48.3, '2026-03-20 09:16:59', 1),
(58, 2, 63, '2026-03-20 09:16:59', 1),
(59, 3, 3628, '2026-03-20 09:16:59', 1),
(60, 4, 0, '2026-03-20 09:16:59', 1),
(61, 1, 48.3, '2026-03-20 09:17:16', 1),
(62, 2, 63, '2026-03-20 09:17:16', 1),
(63, 3, 3628, '2026-03-20 09:17:16', 1),
(64, 4, 0, '2026-03-20 09:17:16', 1),
(65, 1, 48.3, '2026-03-20 09:17:19', 1),
(66, 2, 63, '2026-03-20 09:17:19', 1),
(67, 3, 3628, '2026-03-20 09:17:19', 1),
(68, 4, 0, '2026-03-20 09:17:19', 1),
(69, 1, 48.3, '2026-03-20 09:17:23', 1),
(70, 2, 63, '2026-03-20 09:17:23', 1),
(71, 3, 3628, '2026-03-20 09:17:23', 1),
(72, 4, 0, '2026-03-20 09:17:23', 1),
(73, 1, 48.3, '2026-03-20 09:17:32', 1),
(74, 2, 63, '2026-03-20 09:17:32', 1),
(75, 3, 3628, '2026-03-20 09:17:32', 1),
(76, 4, 0, '2026-03-20 09:17:32', 1),
(77, 1, 48.3, '2026-03-20 09:20:25', 1),
(78, 2, 63, '2026-03-20 09:20:25', 1),
(79, 3, 3628, '2026-03-20 09:20:25', 1),
(80, 4, 0, '2026-03-20 09:20:25', 1),
(81, 1, 48.3, '2026-03-20 09:20:33', 1),
(82, 2, 63, '2026-03-20 09:20:33', 1),
(83, 3, 3628, '2026-03-20 09:20:33', 1),
(84, 4, 0, '2026-03-20 09:20:33', 1),
(85, 1, 48.3, '2026-03-20 09:20:37', 1),
(86, 2, 63, '2026-03-20 09:20:37', 1),
(87, 3, 3628, '2026-03-20 09:20:37', 1),
(88, 4, 0, '2026-03-20 09:20:37', 1),
(89, 1, 48.3, '2026-03-20 09:20:55', 1),
(90, 2, 63, '2026-03-20 09:20:55', 1),
(91, 3, 3628, '2026-03-20 09:20:55', 1),
(92, 4, 0, '2026-03-20 09:20:55', 1),
(93, 1, 48.3, '2026-03-20 09:21:09', 1),
(94, 2, 63, '2026-03-20 09:21:09', 1),
(95, 3, 3628, '2026-03-20 09:21:09', 1),
(96, 4, 0, '2026-03-20 09:21:09', 1),
(97, 1, 48.3, '2026-03-20 09:37:40', 1),
(98, 2, 63, '2026-03-20 09:37:40', 1),
(99, 3, 3628, '2026-03-20 09:37:40', 1),
(100, 4, 0, '2026-03-20 09:37:40', 1),
(101, 1, 48.3, '2026-03-20 09:37:48', 1),
(102, 2, 63, '2026-03-20 09:37:48', 1),
(103, 3, 3628, '2026-03-20 09:37:48', 1),
(104, 4, 0, '2026-03-20 09:37:48', 1),
(105, 1, 48.3, '2026-03-20 09:37:51', 1),
(106, 2, 63, '2026-03-20 09:37:51', 1),
(107, 3, 3628, '2026-03-20 09:37:51', 1),
(108, 4, 0, '2026-03-20 09:37:51', 1),
(109, 1, 48.3, '2026-03-20 09:37:55', 1),
(110, 2, 63, '2026-03-20 09:37:55', 1),
(111, 3, 3628, '2026-03-20 09:37:55', 1),
(112, 4, 0, '2026-03-20 09:37:55', 1),
(113, 1, 48.3, '2026-03-20 09:55:03', 1),
(114, 2, 63, '2026-03-20 09:55:03', 1),
(115, 3, 3628, '2026-03-20 09:55:03', 1),
(116, 4, 0, '2026-03-20 09:55:03', 1),
(117, 1, 48.3, '2026-03-20 09:55:21', 1),
(118, 2, 63, '2026-03-20 09:55:21', 1),
(119, 3, 3628, '2026-03-20 09:55:21', 1),
(120, 4, 0, '2026-03-20 09:55:21', 1),
(121, 1, 48.3, '2026-03-20 09:55:27', 1),
(122, 2, 63, '2026-03-20 09:55:27', 1),
(123, 3, 3628, '2026-03-20 09:55:27', 1),
(124, 4, 0, '2026-03-20 09:55:27', 1),
(125, 1, 48.3, '2026-03-20 09:55:31', 1),
(126, 2, 63, '2026-03-20 09:55:31', 1),
(127, 3, 3628, '2026-03-20 09:55:31', 1),
(128, 4, 0, '2026-03-20 09:55:31', 1),
(129, 1, 48.3, '2026-03-20 09:55:39', 1),
(130, 2, 63, '2026-03-20 09:55:39', 1),
(131, 3, 3628, '2026-03-20 09:55:39', 1),
(132, 4, 0, '2026-03-20 09:55:39', 1),
(133, 1, 48.3, '2026-03-20 09:55:55', 1),
(134, 2, 63, '2026-03-20 09:55:55', 1),
(135, 3, 3628, '2026-03-20 09:55:55', 1),
(136, 4, 0, '2026-03-20 09:55:55', 1),
(137, 1, 48.3, '2026-03-20 10:04:20', 1),
(138, 2, 63, '2026-03-20 10:04:20', 1),
(139, 3, 3628, '2026-03-20 10:04:20', 1),
(140, 4, 0, '2026-03-20 10:04:20', 1),
(141, 1, 48.3, '2026-03-20 10:04:24', 1),
(142, 2, 63, '2026-03-20 10:04:24', 1),
(143, 3, 3628, '2026-03-20 10:04:24', 1),
(144, 4, 0, '2026-03-20 10:04:24', 1),
(145, 1, 48.3, '2026-03-20 10:04:31', 1),
(146, 2, 63, '2026-03-20 10:04:31', 1),
(147, 3, 3628, '2026-03-20 10:04:31', 1),
(148, 4, 0, '2026-03-20 10:04:31', 1),
(149, 1, 27.1, '2026-03-20 10:04:40', 1),
(150, 2, 63, '2026-03-20 10:04:40', 1),
(151, 3, 3628, '2026-03-20 10:04:40', 1),
(152, 4, 0, '2026-03-20 10:04:40', 1),
(153, 1, 63.4, '2026-03-20 10:04:56', 1),
(154, 2, 63, '2026-03-20 10:04:56', 1),
(155, 3, 3628, '2026-03-20 10:04:56', 1),
(156, 4, 0, '2026-03-20 10:04:56', 1),
(157, 1, 43, '2026-03-20 10:05:00', 1),
(158, 2, 63, '2026-03-20 10:05:00', 1),
(159, 3, 3628, '2026-03-20 10:05:00', 1),
(160, 4, 0, '2026-03-20 10:05:00', 1),
(161, 1, 43, '2026-03-20 10:05:04', 1),
(162, 2, 63, '2026-03-20 10:05:04', 1),
(163, 3, 3628, '2026-03-20 10:05:04', 1),
(164, 4, 0, '2026-03-20 10:05:04', 1),
(165, 1, 43, '2026-03-20 10:16:01', 1),
(166, 2, 63, '2026-03-20 10:16:01', 1),
(167, 3, 3628, '2026-03-20 10:16:01', 1),
(168, 4, 0, '2026-03-20 10:16:01', 1),
(169, 1, 43, '2026-03-20 10:16:05', 1),
(170, 2, 63, '2026-03-20 10:16:05', 1),
(171, 3, 3628, '2026-03-20 10:16:05', 1),
(172, 4, 0, '2026-03-20 10:16:05', 1),
(173, 1, 43, '2026-03-20 10:16:14', 1),
(174, 2, 63, '2026-03-20 10:16:14', 1),
(175, 3, 3628, '2026-03-20 10:16:14', 1),
(176, 4, 0, '2026-03-20 10:16:14', 1),
(177, 1, -3, '2026-03-20 10:16:17', 1),
(178, 2, 63, '2026-03-20 10:16:17', 1),
(179, 3, 3628, '2026-03-20 10:16:17', 1),
(180, 4, 0, '2026-03-20 10:16:17', 1),
(181, 1, 71.3, '2026-03-20 10:16:21', 1),
(182, 2, 63, '2026-03-20 10:16:21', 1),
(183, 3, 3628, '2026-03-20 10:16:21', 1),
(184, 4, 0, '2026-03-20 10:16:21', 1),
(185, 1, 28.8, '2026-03-20 10:16:30', 1),
(186, 2, 63, '2026-03-20 10:16:30', 1),
(187, 3, 3628, '2026-03-20 10:16:30', 1),
(188, 4, 0, '2026-03-20 10:16:30', 1),
(189, 1, 28.8, '2026-03-20 10:16:37', 1),
(190, 2, 63, '2026-03-20 10:16:37', 1),
(191, 3, 3628, '2026-03-20 10:16:37', 1),
(192, 4, 0, '2026-03-20 10:16:37', 1),
(193, 1, 28.8, '2026-03-20 10:17:00', 1),
(194, 2, 63, '2026-03-20 10:17:00', 1),
(195, 3, 3628, '2026-03-20 10:17:00', 1),
(196, 4, 0, '2026-03-20 10:17:00', 1),
(197, 1, 28.8, '2026-03-20 10:17:04', 1),
(198, 2, 63, '2026-03-20 10:17:04', 1),
(199, 3, 3628, '2026-03-20 10:17:04', 1),
(200, 4, 0, '2026-03-20 10:17:04', 1),
(201, 1, 28.8, '2026-03-20 10:17:08', 1),
(202, 2, 63, '2026-03-20 10:17:08', 1),
(203, 3, 3628, '2026-03-20 10:17:08', 1),
(204, 4, 0, '2026-03-20 10:17:08', 1),
(205, 1, 28.8, '2026-03-20 10:17:21', 1),
(206, 2, 63, '2026-03-20 10:17:21', 1),
(207, 3, 3628, '2026-03-20 10:17:21', 1),
(208, 4, 0, '2026-03-20 10:17:21', 1),
(209, 1, 28.8, '2026-03-20 10:17:35', 1),
(210, 2, 63, '2026-03-20 10:17:35', 1),
(211, 3, 3628, '2026-03-20 10:17:35', 1),
(212, 4, 0, '2026-03-20 10:17:35', 1),
(213, 1, 28.8, '2026-03-20 10:17:39', 1),
(214, 2, 63, '2026-03-20 10:17:39', 1),
(215, 3, 3628, '2026-03-20 10:17:39', 1),
(216, 4, 0, '2026-03-20 10:17:39', 1),
(217, 1, 4.1, '2026-03-20 10:17:42', 1),
(218, 2, 63, '2026-03-20 10:17:42', 1),
(219, 3, 3628, '2026-03-20 10:17:42', 1),
(220, 4, 0, '2026-03-20 10:17:42', 1),
(221, 1, 57.2, '2026-03-20 10:17:45', 1),
(222, 2, 63, '2026-03-20 10:17:45', 1),
(223, 3, 3628, '2026-03-20 10:17:45', 1),
(224, 4, 0, '2026-03-20 10:17:45', 1),
(225, 1, 14.7, '2026-03-20 10:17:54', 1),
(226, 2, 63, '2026-03-20 10:17:54', 1),
(227, 3, 3628, '2026-03-20 10:17:54', 1),
(228, 4, 0, '2026-03-20 10:17:54', 1),
(229, 1, 43.9, '2026-03-20 10:18:02', 1),
(230, 2, 63, '2026-03-20 10:18:02', 1),
(231, 3, 3628, '2026-03-20 10:18:02', 1),
(232, 4, 0, '2026-03-20 10:18:02', 1),
(233, 1, 18.2, '2026-03-20 10:18:10', 1),
(234, 2, 63, '2026-03-20 10:18:10', 1),
(235, 3, 3628, '2026-03-20 10:18:10', 1),
(236, 4, 0, '2026-03-20 10:18:10', 1),
(237, 1, 18.2, '2026-03-20 10:18:20', 1),
(238, 2, 63, '2026-03-20 10:18:20', 1),
(239, 3, 3628, '2026-03-20 10:18:20', 1),
(240, 4, 0, '2026-03-20 10:18:20', 1),
(241, 1, 18.2, '2026-03-20 10:18:29', 1),
(242, 2, 63, '2026-03-20 10:18:29', 1),
(243, 3, 3628, '2026-03-20 10:18:29', 1),
(244, 4, 0, '2026-03-20 10:18:29', 1),
(245, 1, 18.2, '2026-03-20 10:18:32', 1),
(246, 2, 63, '2026-03-20 10:18:32', 1),
(247, 3, 3628, '2026-03-20 10:18:32', 1),
(248, 4, 0, '2026-03-20 10:18:32', 1),
(249, 1, 18.2, '2026-03-20 10:18:36', 1),
(250, 2, 63, '2026-03-20 10:18:36', 1),
(251, 3, 3628, '2026-03-20 10:18:36', 1),
(252, 4, 0, '2026-03-20 10:18:36', 1),
(253, 1, 18.2, '2026-03-20 10:18:40', 1),
(254, 2, 63, '2026-03-20 10:18:40', 1),
(255, 3, 3628, '2026-03-20 10:18:40', 1),
(256, 4, 0, '2026-03-20 10:18:40', 1),
(257, 1, 18.2, '2026-03-20 10:18:44', 1),
(258, 2, 63, '2026-03-20 10:18:44', 1),
(259, 3, 3628, '2026-03-20 10:18:44', 1),
(260, 4, 0, '2026-03-20 10:18:44', 1),
(261, 1, 37.7, '2026-03-20 10:18:47', 1),
(262, 2, 63, '2026-03-20 10:18:47', 1),
(263, 3, 3628, '2026-03-20 10:18:47', 1),
(264, 4, 0, '2026-03-20 10:18:47', 1),
(265, 1, 37.7, '2026-03-20 10:18:56', 1),
(266, 2, 63, '2026-03-20 10:18:56', 1),
(267, 3, 3628, '2026-03-20 10:18:56', 1),
(268, 4, 0, '2026-03-20 10:18:56', 1),
(269, 1, 37.7, '2026-03-20 10:19:24', 1),
(270, 2, 63, '2026-03-20 10:19:24', 1),
(271, 3, 3628, '2026-03-20 10:19:24', 1),
(272, 4, 0, '2026-03-20 10:19:24', 1),
(273, 1, 37.7, '2026-03-20 10:19:30', 1),
(274, 2, 63, '2026-03-20 10:19:30', 1),
(275, 3, 3628, '2026-03-20 10:19:30', 1),
(276, 4, 0, '2026-03-20 10:19:30', 1),
(277, 1, 37.7, '2026-03-20 10:19:44', 1),
(278, 2, 63, '2026-03-20 10:19:44', 1),
(279, 3, 3628, '2026-03-20 10:19:44', 1),
(280, 4, 0, '2026-03-20 10:19:44', 1),
(281, 1, 37.7, '2026-03-20 10:26:48', 1),
(282, 2, 63, '2026-03-20 10:26:48', 1),
(283, 3, 3628, '2026-03-20 10:26:48', 1),
(284, 4, 0, '2026-03-20 10:26:48', 1),
(285, 1, 37.7, '2026-03-20 10:26:51', 1),
(286, 2, 63, '2026-03-20 10:26:51', 1),
(287, 3, 3628, '2026-03-20 10:26:51', 1),
(288, 4, 0, '2026-03-20 10:26:51', 1),
(289, 1, 3.2, '2026-03-20 10:27:02', 1),
(290, 2, 63, '2026-03-20 10:27:02', 1),
(291, 3, 3628, '2026-03-20 10:27:02', 1),
(292, 4, 0, '2026-03-20 10:27:02', 1),
(293, 1, 51, '2026-03-20 10:27:17', 1),
(294, 2, 63, '2026-03-20 10:27:17', 1),
(295, 3, 3628, '2026-03-20 10:27:17', 1),
(296, 4, 0, '2026-03-20 10:27:17', 1),
(297, 1, 51, '2026-03-20 10:27:34', 1),
(298, 2, 63, '2026-03-20 10:27:34', 1),
(299, 3, 3628, '2026-03-20 10:27:34', 1),
(300, 4, 0, '2026-03-20 10:27:34', 1),
(301, 1, 51, '2026-03-20 10:27:37', 1),
(302, 2, 63, '2026-03-20 10:27:37', 1),
(303, 3, 3628, '2026-03-20 10:27:37', 1),
(304, 4, 0, '2026-03-20 10:27:37', 1),
(305, 1, 51, '2026-03-20 10:27:56', 1),
(306, 2, 63, '2026-03-20 10:27:56', 1),
(307, 3, 3628, '2026-03-20 10:27:56', 1),
(308, 4, 0, '2026-03-20 10:27:56', 1),
(309, 1, 51, '2026-03-20 10:27:59', 1),
(310, 2, 63, '2026-03-20 10:27:59', 1),
(311, 3, 3628, '2026-03-20 10:27:59', 1),
(312, 4, 0, '2026-03-20 10:27:59', 1),
(313, 1, 51, '2026-03-20 10:28:03', 1),
(314, 2, 63, '2026-03-20 10:28:03', 1),
(315, 3, 3628, '2026-03-20 10:28:03', 1),
(316, 4, 0, '2026-03-20 10:28:03', 1),
(317, 1, 51, '2026-03-20 10:28:07', 1),
(318, 2, 63, '2026-03-20 10:28:07', 1),
(319, 3, 3628, '2026-03-20 10:28:07', 1),
(320, 4, 0, '2026-03-20 10:28:07', 1),
(321, 1, 51, '2026-03-20 10:34:38', 1),
(322, 2, 63, '2026-03-20 10:34:38', 1),
(323, 3, 3628, '2026-03-20 10:34:38', 1),
(324, 4, 0, '2026-03-20 10:34:38', 1),
(325, 1, 51, '2026-03-20 10:34:41', 1),
(326, 2, 63, '2026-03-20 10:34:41', 1),
(327, 3, 3628, '2026-03-20 10:34:41', 1),
(328, 4, 0, '2026-03-20 10:34:41', 1),
(329, 1, 51, '2026-03-20 10:34:45', 1),
(330, 2, 63, '2026-03-20 10:34:45', 1),
(331, 3, 3628, '2026-03-20 10:34:45', 1),
(332, 4, 0, '2026-03-20 10:34:45', 1),
(333, 1, 51, '2026-03-20 10:34:49', 1),
(334, 2, 63, '2026-03-20 10:34:49', 1),
(335, 3, 3628, '2026-03-20 10:34:49', 1),
(336, 4, 0, '2026-03-20 10:34:49', 1),
(337, 1, 51, '2026-03-20 10:35:18', 1),
(338, 2, 63, '2026-03-20 10:35:18', 1),
(339, 3, 3628, '2026-03-20 10:35:18', 1),
(340, 4, 0, '2026-03-20 10:35:18', 1),
(341, 1, 51, '2026-03-20 10:35:29', 1),
(342, 2, 63, '2026-03-20 10:35:29', 1),
(343, 3, 3628, '2026-03-20 10:35:29', 1),
(344, 4, 0, '2026-03-20 10:35:29', 1),
(345, 1, 51, '2026-03-20 10:37:33', 1),
(346, 2, 63, '2026-03-20 10:37:33', 1),
(347, 3, 3628, '2026-03-20 10:37:33', 1),
(348, 4, 0, '2026-03-20 10:37:33', 1),
(349, 1, 51, '2026-03-20 10:37:36', 1),
(350, 2, 63, '2026-03-20 10:37:36', 1),
(351, 3, 3628, '2026-03-20 10:37:36', 1),
(352, 4, 0, '2026-03-20 10:37:36', 1),
(353, 1, 51, '2026-03-20 10:37:43', 1),
(354, 2, 63, '2026-03-20 10:37:43', 1),
(355, 3, 3628, '2026-03-20 10:37:43', 1),
(356, 4, 0, '2026-03-20 10:37:43', 1),
(357, 1, 51, '2026-03-20 10:37:46', 1),
(358, 2, 63, '2026-03-20 10:37:46', 1),
(359, 3, 3628, '2026-03-20 10:37:46', 1),
(360, 4, 0, '2026-03-20 10:37:46', 1),
(361, 1, 51, '2026-03-20 10:37:54', 1),
(362, 2, 63, '2026-03-20 10:37:54', 1),
(363, 3, 3628, '2026-03-20 10:37:54', 1),
(364, 4, 0, '2026-03-20 10:37:54', 1),
(365, 1, 51, '2026-03-20 10:38:04', 1),
(366, 2, 63, '2026-03-20 10:38:04', 1),
(367, 3, 3628, '2026-03-20 10:38:04', 1),
(368, 4, 0, '2026-03-20 10:38:04', 1),
(369, 1, 51, '2026-03-20 10:45:11', 1),
(370, 2, 63, '2026-03-20 10:45:11', 1),
(371, 3, 3628, '2026-03-20 10:45:11', 1),
(372, 4, 0, '2026-03-20 10:45:11', 1),
(373, 1, 51, '2026-03-20 10:45:14', 1),
(374, 2, 63, '2026-03-20 10:45:14', 1),
(375, 3, 3628, '2026-03-20 10:45:14', 1),
(376, 4, 0, '2026-03-20 10:45:14', 1),
(377, 1, 51, '2026-03-20 10:45:18', 1),
(378, 2, 63, '2026-03-20 10:45:18', 1),
(379, 3, 3628, '2026-03-20 10:45:18', 1),
(380, 4, 0, '2026-03-20 10:45:18', 1),
(381, 1, 51, '2026-03-20 10:45:21', 1),
(382, 2, 63, '2026-03-20 10:45:21', 1),
(383, 3, 3628, '2026-03-20 10:45:21', 1),
(384, 4, 0, '2026-03-20 10:45:21', 1),
(385, 1, 51, '2026-03-20 10:45:25', 1),
(386, 2, 63, '2026-03-20 10:45:25', 1),
(387, 3, 3628, '2026-03-20 10:45:25', 1),
(388, 4, 0, '2026-03-20 10:45:25', 1),
(389, 1, 51, '2026-03-20 10:45:40', 1),
(390, 2, 63, '2026-03-20 10:45:40', 1),
(391, 3, 3628, '2026-03-20 10:45:40', 1),
(392, 4, 0, '2026-03-20 10:45:40', 1),
(393, 1, 51, '2026-03-20 10:49:14', 1),
(394, 2, 63, '2026-03-20 10:49:14', 1),
(395, 3, 3628, '2026-03-20 10:49:15', 1),
(396, 4, 0, '2026-03-20 10:49:15', 1),
(397, 1, 51, '2026-03-20 10:49:21', 1),
(398, 2, 63, '2026-03-20 10:49:21', 1),
(399, 3, 3628, '2026-03-20 10:49:21', 1),
(400, 4, 0, '2026-03-20 10:49:21', 1),
(401, 1, 51, '2026-03-20 10:49:35', 1),
(402, 2, 63, '2026-03-20 10:49:35', 1),
(403, 3, 3628, '2026-03-20 10:49:35', 1),
(404, 4, 0, '2026-03-20 10:49:35', 1),
(405, 1, 51, '2026-03-20 10:49:54', 1),
(406, 2, 63, '2026-03-20 10:49:54', 1),
(407, 3, 3628, '2026-03-20 10:49:54', 1),
(408, 4, 0, '2026-03-20 10:49:54', 1),
(409, 1, 4.1, '2026-03-20 10:49:57', 1),
(410, 2, 63, '2026-03-20 10:49:57', 1),
(411, 3, 3628, '2026-03-20 10:49:57', 1),
(412, 4, 0, '2026-03-20 10:49:57', 1),
(413, 1, 43, '2026-03-20 10:50:11', 1),
(414, 2, 63, '2026-03-20 10:50:11', 1),
(415, 3, 3628, '2026-03-20 10:50:11', 1),
(416, 4, 0, '2026-03-20 10:50:11', 1),
(417, 1, 20, '2026-03-21 00:50:16', 1),
(418, 2, 40, '2026-03-21 00:50:16', 1),
(419, 3, 3628, '2026-03-21 00:50:16', 1),
(420, 4, 0, '2026-03-21 00:50:16', 1),
(421, 1, 20, '2026-03-21 00:50:20', 1),
(422, 2, 40, '2026-03-21 00:50:20', 1),
(423, 3, 3628, '2026-03-21 00:50:20', 1),
(424, 4, 0, '2026-03-21 00:50:20', 1),
(425, 1, 20, '2026-03-21 00:50:28', 1),
(426, 2, 40, '2026-03-21 00:50:28', 1),
(427, 3, 3628, '2026-03-21 00:50:28', 1),
(428, 4, 0, '2026-03-21 00:50:28', 1),
(429, 1, 20, '2026-03-21 00:50:41', 1),
(430, 2, 40, '2026-03-21 00:50:41', 1),
(431, 3, 3628, '2026-03-21 00:50:41', 1),
(432, 4, 0, '2026-03-21 00:50:41', 1),
(433, 1, 20, '2026-03-21 00:55:59', 1),
(434, 2, 40, '2026-03-21 00:55:59', 1),
(435, 3, 3628, '2026-03-21 00:55:59', 1),
(436, 4, 0, '2026-03-21 00:55:59', 1),
(437, 1, 20, '2026-03-21 00:56:03', 1),
(438, 2, 40, '2026-03-21 00:56:03', 1),
(439, 3, 3628, '2026-03-21 00:56:03', 1),
(440, 4, 0, '2026-03-21 00:56:03', 1),
(441, 1, 34.2, '2026-03-21 00:56:11', 1),
(442, 2, 40, '2026-03-21 00:56:11', 1),
(443, 3, 3628, '2026-03-21 00:56:11', 1),
(444, 4, 0, '2026-03-21 00:56:11', 1),
(445, 1, 34.2, '2026-03-21 00:56:15', 1),
(446, 2, 40, '2026-03-21 00:56:15', 1),
(447, 3, 3628, '2026-03-21 00:56:15', 1),
(448, 4, 0, '2026-03-21 00:56:15', 1),
(449, 1, 43, '2026-03-21 00:56:31', 1),
(450, 2, 40, '2026-03-21 00:56:31', 1),
(451, 3, 3628, '2026-03-21 00:56:31', 1),
(452, 4, 0, '2026-03-21 00:56:31', 1),
(453, 1, -6.6, '2026-03-21 00:56:34', 1),
(454, 2, 40, '2026-03-21 00:56:34', 1),
(455, 3, 3628, '2026-03-21 00:56:34', 1),
(456, 4, 0, '2026-03-21 00:56:34', 1),
(457, 1, 25.3, '2026-03-21 00:56:38', 1),
(458, 2, 40, '2026-03-21 00:56:38', 1),
(459, 3, 3628, '2026-03-21 00:56:38', 1),
(460, 4, 0, '2026-03-21 00:56:38', 1),
(461, 1, 50.1, '2026-03-21 00:56:46', 1),
(462, 2, 40, '2026-03-21 00:56:46', 1),
(463, 3, 3628, '2026-03-21 00:56:46', 1),
(464, 4, 0, '2026-03-21 00:56:46', 1),
(465, 1, 4.1, '2026-03-21 00:56:50', 1),
(466, 2, 40, '2026-03-21 00:56:50', 1),
(467, 3, 3628, '2026-03-21 00:56:50', 1),
(468, 4, 0, '2026-03-21 00:56:50', 1),
(469, 1, 34.2, '2026-03-21 00:56:58', 1),
(470, 2, 40, '2026-03-21 00:56:58', 1),
(471, 3, 3628, '2026-03-21 00:56:58', 1),
(472, 4, 0, '2026-03-21 00:56:58', 1),
(473, 1, 7.6, '2026-03-21 00:57:06', 1),
(474, 2, 40, '2026-03-21 00:57:06', 1),
(475, 3, 3628, '2026-03-21 00:57:06', 1),
(476, 4, 0, '2026-03-21 00:57:06', 1);

-- --------------------------------------------------------

--
-- Table structure for table `kich_ban_tu_dong`
--

CREATE TABLE `kich_ban_tu_dong` (
  `idKichBan` int(11) NOT NULL,
  `tenKichBan` varchar(100) DEFAULT NULL,
  `dieuKien` varchar(256) DEFAULT NULL,
  `hanhDong` varchar(256) DEFAULT NULL,
  `moTa` varchar(256) DEFAULT NULL,
  `kichHoat` smallint(6) DEFAULT 1,
  `ngayTao` timestamp NOT NULL DEFAULT current_timestamp(),
  `gioBatDau` time DEFAULT NULL,
  `gioKetThuc` time DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `nguoidung`
--

CREATE TABLE `nguoidung` (
  `idNguoiDung` int(11) NOT NULL,
  `google_id` varchar(255) DEFAULT NULL,
  `maNguoiDung` varchar(20) NOT NULL,
  `tenDangNhap` varchar(50) NOT NULL,
  `matKhau` varchar(255) NOT NULL,
  `hoTen` varchar(100) DEFAULT NULL,
  `avatar` varchar(255) DEFAULT NULL,
  `idNhom` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `nguoidung`
--

INSERT INTO `nguoidung` (`idNguoiDung`, `google_id`, `maNguoiDung`, `tenDangNhap`, `matKhau`, `hoTen`, `avatar`, `idNhom`) VALUES
(1, 'WAOL7KLGiKN3a74UR2Bh9E9fhVh2', 'NV001', '23004224@st.vlute.edu.vn', '$2y$10$RBqVcid3zofoFZ5NF9iGe.YUh2PlPwj.xBWX9KNTFLqKpS5hj.F.O', 'Nguyễn Quang Huy', 'https://lh3.googleusercontent.com/a/ACg8ocLfB-BxeYVkYrqhq8JAbAtPUq_lCA3r8WIyj2Gs3eOwoDO2tw=s96-c', 1),
(2, NULL, 'NV002', 'manager@gmail.com', '$2y$10$0EkmBIGFIPf0S228At/7leRtYcsSTpI53WYx.qAd79VnIRMhrY5KK', 'Nguyễn Văn Quản Lý', NULL, NULL),
(5, NULL, 'NV100', 'admin@gmail.com', '$2y$10$0EkmBIGFIPf0S228At/7leRtYcsSTpI53WYx.qAd79VnIRMhrY5KK', 'Admin hệ thống', NULL, 1);

-- --------------------------------------------------------

--
-- Table structure for table `nhat_ky`
--

CREATE TABLE `nhat_ky` (
  `idNhatKy` int(11) NOT NULL,
  `idNguoiDung` int(11) DEFAULT NULL,
  `idThietBi` int(11) DEFAULT NULL,
  `loaiNhatKy` varchar(50) DEFAULT NULL,
  `hanhDong` varchar(256) DEFAULT NULL,
  `noiDung` varchar(256) DEFAULT NULL,
  `diaChiIp` varchar(50) DEFAULT NULL,
  `thoiGian` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `nhomnguoidung`
--

CREATE TABLE `nhomnguoidung` (
  `idNhom` int(11) NOT NULL,
  `maNhom` varchar(20) NOT NULL,
  `tenNhom` varchar(50) NOT NULL,
  `moTa` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `nhomnguoidung`
--

INSERT INTO `nhomnguoidung` (`idNhom`, `maNhom`, `tenNhom`, `moTa`) VALUES
(1, 'ADMIN', 'Quản trị viên', 'Toàn quyền điều khiển hệ thống');

-- --------------------------------------------------------

--
-- Table structure for table `nhomnguoidung_quyen`
--

CREATE TABLE `nhomnguoidung_quyen` (
  `idNhom` int(11) NOT NULL,
  `idQuyen` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `nhomnguoidung_quyen`
--

INSERT INTO `nhomnguoidung_quyen` (`idNhom`, `idQuyen`) VALUES
(1, 1),
(1, 2),
(1, 3),
(1, 4),
(1, 5),
(1, 6);

-- --------------------------------------------------------

--
-- Table structure for table `phong`
--

CREATE TABLE `phong` (
  `idPhong` int(11) NOT NULL,
  `tenPhong` varchar(100) DEFAULT NULL,
  `loaiPhong` varchar(50) DEFAULT NULL,
  `viTri` varchar(100) DEFAULT NULL,
  `moTa` varchar(256) DEFAULT NULL,
  `dienTich` float DEFAULT NULL,
  `trangThai` smallint(6) DEFAULT 1,
  `ngayTao` timestamp NOT NULL DEFAULT current_timestamp(),
  `cheDoTuDong` smallint(6) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `phong`
--

INSERT INTO `phong` (`idPhong`, `tenPhong`, `loaiPhong`, `viTri`, `moTa`, `dienTich`, `trangThai`, `ngayTao`, `cheDoTuDong`) VALUES
(1, 'Phòng 101', NULL, NULL, NULL, NULL, 1, '2026-03-19 21:36:26', 0);

-- --------------------------------------------------------

--
-- Table structure for table `phong_kich_ban`
--

CREATE TABLE `phong_kich_ban` (
  `idPhong` int(11) NOT NULL,
  `idKichBan` int(11) NOT NULL,
  `ngayKichHoat` timestamp NOT NULL DEFAULT current_timestamp(),
  `trangThai` smallint(6) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `quyen`
--

CREATE TABLE `quyen` (
  `idQuyen` int(11) NOT NULL,
  `maQuyen` varchar(50) NOT NULL,
  `tenQuyen` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `quyen`
--

INSERT INTO `quyen` (`idQuyen`, `maQuyen`, `tenQuyen`) VALUES
(1, 'nguoidung.view', 'Xem và quản lý người dùng'),
(2, 'thietbi.view', 'Xem và điều khiển thiết bị'),
(3, 'trangchu.view', 'Xem trang chủ và cảm biến'),
(4, 'phantich.view', 'Xem báo cáo và phân tích'),
(5, 'tudong.view', 'Xem cấu hình tự động hóa'),
(6, 'canhbao.view', 'Xem nhật ký cảnh báo');

-- --------------------------------------------------------

--
-- Table structure for table `thiet_bi`
--

CREATE TABLE `thiet_bi` (
  `idThietBi` int(11) NOT NULL,
  `idPhong` int(11) DEFAULT NULL,
  `tenThietBi` varchar(100) DEFAULT NULL,
  `loaiThietBi` varchar(50) DEFAULT NULL,
  `diaChiIp` varchar(50) DEFAULT NULL,
  `macAddress` varchar(50) DEFAULT NULL,
  `topicMqtt` varchar(100) DEFAULT NULL,
  `trangThaiKetNoi` smallint(6) DEFAULT 0,
  `lastHeartbeat` timestamp NULL DEFAULT NULL,
  `firmwareVersion` varchar(50) DEFAULT NULL,
  `ngayTao` timestamp NOT NULL DEFAULT current_timestamp(),
  `ngayCapNhat` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `thiet_bi`
--

INSERT INTO `thiet_bi` (`idThietBi`, `idPhong`, `tenThietBi`, `loaiThietBi`, `diaChiIp`, `macAddress`, `topicMqtt`, `trangThaiKetNoi`, `lastHeartbeat`, `firmwareVersion`, `ngayTao`, `ngayCapNhat`) VALUES
(1, 1, 'ESP32 Wokwi', NULL, NULL, NULL, 'nhatro123/room1', 0, NULL, NULL, '2026-03-19 21:36:26', '2026-03-19 21:36:26');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cam_bien`
--
ALTER TABLE `cam_bien`
  ADD PRIMARY KEY (`idCamBien`),
  ADD KEY `fk_cb_tb` (`idThietBi`);

--
-- Indexes for table `canh_bao`
--
ALTER TABLE `canh_bao`
  ADD PRIMARY KEY (`idCanhBao`),
  ADD KEY `fk_ca_cb` (`idCamBien`);

--
-- Indexes for table `cau_hinh`
--
ALTER TABLE `cau_hinh`
  ADD PRIMARY KEY (`idCauHinh`),
  ADD KEY `fk_ch_phong` (`idPhong`);

--
-- Indexes for table `du_lieu_cam_bien`
--
ALTER TABLE `du_lieu_cam_bien`
  ADD PRIMARY KEY (`idDuLieu`),
  ADD KEY `fk_dl_cb` (`idCamBien`);

--
-- Indexes for table `kich_ban_tu_dong`
--
ALTER TABLE `kich_ban_tu_dong`
  ADD PRIMARY KEY (`idKichBan`);

--
-- Indexes for table `nguoidung`
--
ALTER TABLE `nguoidung`
  ADD PRIMARY KEY (`idNguoiDung`),
  ADD UNIQUE KEY `uk_maNguoiDung` (`maNguoiDung`),
  ADD UNIQUE KEY `uk_tenDangNhap` (`tenDangNhap`),
  ADD UNIQUE KEY `uk_google_id` (`google_id`),
  ADD KEY `fk_nd_nhom` (`idNhom`);

--
-- Indexes for table `nhat_ky`
--
ALTER TABLE `nhat_ky`
  ADD PRIMARY KEY (`idNhatKy`),
  ADD KEY `fk_nk_nd` (`idNguoiDung`),
  ADD KEY `fk_nk_tb` (`idThietBi`);

--
-- Indexes for table `nhomnguoidung`
--
ALTER TABLE `nhomnguoidung`
  ADD PRIMARY KEY (`idNhom`),
  ADD UNIQUE KEY `uk_maNhom` (`maNhom`);

--
-- Indexes for table `nhomnguoidung_quyen`
--
ALTER TABLE `nhomnguoidung_quyen`
  ADD PRIMARY KEY (`idNhom`,`idQuyen`),
  ADD KEY `fk_nnq_quyen` (`idQuyen`);

--
-- Indexes for table `phong`
--
ALTER TABLE `phong`
  ADD PRIMARY KEY (`idPhong`);

--
-- Indexes for table `phong_kich_ban`
--
ALTER TABLE `phong_kich_ban`
  ADD PRIMARY KEY (`idPhong`,`idKichBan`),
  ADD KEY `fk_pkb_kb` (`idKichBan`);

--
-- Indexes for table `quyen`
--
ALTER TABLE `quyen`
  ADD PRIMARY KEY (`idQuyen`),
  ADD UNIQUE KEY `uk_maQuyen` (`maQuyen`);

--
-- Indexes for table `thiet_bi`
--
ALTER TABLE `thiet_bi`
  ADD PRIMARY KEY (`idThietBi`),
  ADD KEY `fk_tb_phong` (`idPhong`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cam_bien`
--
ALTER TABLE `cam_bien`
  MODIFY `idCamBien` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `canh_bao`
--
ALTER TABLE `canh_bao`
  MODIFY `idCanhBao` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `cau_hinh`
--
ALTER TABLE `cau_hinh`
  MODIFY `idCauHinh` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `du_lieu_cam_bien`
--
ALTER TABLE `du_lieu_cam_bien`
  MODIFY `idDuLieu` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=477;

--
-- AUTO_INCREMENT for table `kich_ban_tu_dong`
--
ALTER TABLE `kich_ban_tu_dong`
  MODIFY `idKichBan` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `nguoidung`
--
ALTER TABLE `nguoidung`
  MODIFY `idNguoiDung` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `nhat_ky`
--
ALTER TABLE `nhat_ky`
  MODIFY `idNhatKy` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `nhomnguoidung`
--
ALTER TABLE `nhomnguoidung`
  MODIFY `idNhom` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `phong`
--
ALTER TABLE `phong`
  MODIFY `idPhong` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `quyen`
--
ALTER TABLE `quyen`
  MODIFY `idQuyen` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `thiet_bi`
--
ALTER TABLE `thiet_bi`
  MODIFY `idThietBi` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `cam_bien`
--
ALTER TABLE `cam_bien`
  ADD CONSTRAINT `fk_cb_tb` FOREIGN KEY (`idThietBi`) REFERENCES `thiet_bi` (`idThietBi`) ON DELETE CASCADE;

--
-- Constraints for table `canh_bao`
--
ALTER TABLE `canh_bao`
  ADD CONSTRAINT `fk_ca_cb` FOREIGN KEY (`idCamBien`) REFERENCES `cam_bien` (`idCamBien`) ON DELETE CASCADE;

--
-- Constraints for table `cau_hinh`
--
ALTER TABLE `cau_hinh`
  ADD CONSTRAINT `fk_ch_phong` FOREIGN KEY (`idPhong`) REFERENCES `phong` (`idPhong`) ON DELETE CASCADE;

--
-- Constraints for table `du_lieu_cam_bien`
--
ALTER TABLE `du_lieu_cam_bien`
  ADD CONSTRAINT `fk_dl_cb` FOREIGN KEY (`idCamBien`) REFERENCES `cam_bien` (`idCamBien`) ON DELETE CASCADE;

--
-- Constraints for table `nguoidung`
--
ALTER TABLE `nguoidung`
  ADD CONSTRAINT `fk_nd_nhom` FOREIGN KEY (`idNhom`) REFERENCES `nhomnguoidung` (`idNhom`) ON DELETE SET NULL;

--
-- Constraints for table `nhat_ky`
--
ALTER TABLE `nhat_ky`
  ADD CONSTRAINT `fk_nk_nd` FOREIGN KEY (`idNguoiDung`) REFERENCES `nguoidung` (`idNguoiDung`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_nk_tb` FOREIGN KEY (`idThietBi`) REFERENCES `thiet_bi` (`idThietBi`) ON DELETE SET NULL;

--
-- Constraints for table `nhomnguoidung_quyen`
--
ALTER TABLE `nhomnguoidung_quyen`
  ADD CONSTRAINT `fk_nnq_nhom` FOREIGN KEY (`idNhom`) REFERENCES `nhomnguoidung` (`idNhom`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_nnq_quyen` FOREIGN KEY (`idQuyen`) REFERENCES `quyen` (`idQuyen`) ON DELETE CASCADE;

--
-- Constraints for table `phong_kich_ban`
--
ALTER TABLE `phong_kich_ban`
  ADD CONSTRAINT `fk_pkb_kb` FOREIGN KEY (`idKichBan`) REFERENCES `kich_ban_tu_dong` (`idKichBan`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_pkb_phong` FOREIGN KEY (`idPhong`) REFERENCES `phong` (`idPhong`) ON DELETE CASCADE;

--
-- Constraints for table `thiet_bi`
--
ALTER TABLE `thiet_bi`
  ADD CONSTRAINT `fk_tb_phong` FOREIGN KEY (`idPhong`) REFERENCES `phong` (`idPhong`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

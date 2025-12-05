-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Máy chủ: 127.0.0.1
-- Thời gian đã tạo: Th12 05, 2025 lúc 12:06 PM
-- Phiên bản máy phục vụ: 10.4.32-MariaDB
-- Phiên bản PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Cơ sở dữ liệu: `website`
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `order_code` varchar(50) NOT NULL,
  `receiver` varchar(100) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `address` varchar(255) NOT NULL,
  `delivery_method` varchar(20) NOT NULL DEFAULT 'home',
  `payment_method` varchar(20) NOT NULL,
  `total_amount` decimal(15,2) NOT NULL DEFAULT 0.00,
  `discount_amount` decimal(15,2) NOT NULL DEFAULT 0.00,
  `coupon_code` varchar(100) DEFAULT NULL,
  `transaction_info` varchar(30) NOT NULL,
  `note` text DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `received_amount` decimal(15,2) DEFAULT 0.00,
  `lack_amount` decimal(15,2) DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `order_code`, `receiver`, `phone`, `address`, `delivery_method`, `payment_method`, `total_amount`, `discount_amount`, `coupon_code`, `transaction_info`, `note`, `created_at`, `received_amount`, `lack_amount`) VALUES
(22, 15, 'HD1764691634', 'Tạ Văn Hội', '0355046630', '', 'store', 'bank_after', 576000.00, 64000.00, 'Giáng sinh 25/12', 'thanhtoanthieu', 'Không', '2025-12-02 23:07:14', 500000.00, 76000.00),
(23, 15, 'HD1764691683', 'Tạ Văn Hội', '0355046630', '', 'store', 'bank_before', 405000.00, 45000.00, 'Giáng sinh 25/12', 'dathanhtoan', 'KHÔNG', '2025-12-02 23:08:03', 405000.00, 0.00),
(24, 15, 'HD1764691956', 'Tạ Văn Hội', '0355046630', '', 'store', 'cod', 210000.00, 20000.00, 'Giáng sinh 25/12', 'chothanhtoan', 'Không ', '2025-12-02 23:12:36', 0.00, 0.00),
(25, 15, 'HD1764696377', 'Tạ Văn Hội', '0355046630', '', 'store', 'cod', 1200000.00, 0.00, '', 'chothanhtoan', 'Không ', '2025-12-03 00:26:17', 0.00, 0.00),
(26, 15, 'HD1764703531', 'Tạ Văn Hội', '0355046630', '', 'store', 'bank_before', 220000.00, 10000.00, 'Giảm 20/11', 'dathanhtoan', 'Không ', '2025-12-03 02:25:31', 220000.00, 0.00),
(27, 15, 'HD1764703907', 'Tạ Văn Hội', '0355046630', '', 'store', 'cod', 220000.00, 10000.00, 'Giảm 20/11', 'chothanhtoan', 'kHÔNG', '2025-12-03 02:31:47', 0.00, 0.00),
(28, 15, 'HD1764704193', 'Tạ Văn Hội', '0355046630', '', 'store', 'cod', 800000.00, 0.00, '', 'chothanhtoan', 'lp', '2025-12-03 02:36:33', 0.00, 0.00),
(29, 15, 'HD1764704877', 'Tạ Văn Hội', '0355046630', '', 'store', 'cod', 495000.00, 55000.00, 'Giáng sinh 25/12', 'chothanhtoan', 'Không ', '2025-12-03 02:47:57', 0.00, 0.00),
(30, 15, 'HD1764769627', 'Tạ Văn Hội', '0355046630', '', 'store', 'bank_before', 900000.00, 100000.00, 'Giáng sinh 25/12', 'dathanhtoan', 'Khong', '2025-12-03 20:47:07', 900000.00, 0.00);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `order_details`
--

CREATE TABLE `order_details` (
  `id` int(11) NOT NULL,
  `order_id` varchar(20) NOT NULL,
  `product_id` varchar(50) NOT NULL,
  `size` varchar(10) NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` decimal(15,2) NOT NULL,
  `sale_price` decimal(15,2) NOT NULL,
  `total` decimal(15,2) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `product_type` varchar(100) DEFAULT NULL,
  `product_name` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `order_details`
--

INSERT INTO `order_details` (`id`, `order_id`, `product_id`, `size`, `quantity`, `price`, `sale_price`, `total`, `image`, `product_type`, `product_name`) VALUES
(56, '2', '04', '17x7.5cm', 4, 250000.00, 0.00, 0.00, NULL, NULL, NULL),
(57, '2', '01', '13x6cm', 1, 200000.00, 0.00, 0.00, NULL, NULL, NULL),
(58, '3', '04', '25x7.5cm', 1, 400000.00, 0.00, 0.00, NULL, NULL, NULL),
(59, '4', '04', '13x6cm', 1, 200000.00, 0.00, 0.00, NULL, NULL, NULL),
(60, '5', '05', '17x7.5', 1, 0.00, 0.00, 0.00, NULL, NULL, NULL),
(61, '5', '04', '17x7.5cm', 1, 250000.00, 0.00, 0.00, NULL, NULL, NULL),
(62, '6', 'addon_23', 'masp', 0, 0.00, 0.00, 0.00, NULL, NULL, NULL),
(63, '6', 'addon_23', 'tensp', 0, 0.00, 0.00, 0.00, NULL, NULL, NULL),
(64, '6', 'addon_23', 'hinhanh', 0, 0.00, 0.00, 0.00, NULL, NULL, NULL),
(65, '6', 'addon_23', 'gia', 0, 0.00, 0.00, 0.00, NULL, NULL, NULL),
(66, '6', 'addon_23', 'qty', 0, 0.00, 0.00, 0.00, NULL, NULL, NULL),
(67, '6', 'addon_23', 'type', 0, 0.00, 0.00, 0.00, NULL, NULL, NULL),
(68, '6', 'addon_24', 'masp', 0, 0.00, 0.00, 0.00, NULL, NULL, NULL),
(69, '6', 'addon_24', 'tensp', 0, 0.00, 0.00, 0.00, NULL, NULL, NULL),
(70, '6', 'addon_24', 'hinhanh', 0, 0.00, 0.00, 0.00, NULL, NULL, NULL),
(71, '6', 'addon_24', 'gia', 0, 0.00, 0.00, 0.00, NULL, NULL, NULL),
(72, '6', 'addon_24', 'qty', 0, 0.00, 0.00, 0.00, NULL, NULL, NULL),
(73, '6', 'addon_24', 'type', 0, 0.00, 0.00, 0.00, NULL, NULL, NULL),
(74, '7', 'addon_24', 'masp', 0, 0.00, 0.00, 0.00, NULL, NULL, NULL),
(75, '7', 'addon_24', 'tensp', 0, 0.00, 0.00, 0.00, NULL, NULL, NULL),
(76, '7', 'addon_24', 'hinhanh', 0, 0.00, 0.00, 0.00, NULL, NULL, NULL),
(77, '7', 'addon_24', 'gia', 0, 0.00, 0.00, 0.00, NULL, NULL, NULL),
(78, '7', 'addon_24', 'qty', 0, 0.00, 0.00, 0.00, NULL, NULL, NULL),
(79, '7', 'addon_24', 'type', 0, 0.00, 0.00, 0.00, NULL, NULL, NULL),
(80, '7', 'addon_18', 'masp', 0, 0.00, 0.00, 0.00, NULL, NULL, NULL),
(81, '7', 'addon_18', 'tensp', 0, 0.00, 0.00, 0.00, NULL, NULL, NULL),
(82, '7', 'addon_18', 'hinhanh', 0, 0.00, 0.00, 0.00, NULL, NULL, NULL),
(83, '7', 'addon_18', 'gia', 0, 0.00, 0.00, 0.00, NULL, NULL, NULL),
(84, '7', 'addon_18', 'qty', 0, 0.00, 0.00, 0.00, NULL, NULL, NULL),
(85, '7', 'addon_18', 'type', 0, 0.00, 0.00, 0.00, NULL, NULL, NULL),
(86, '7', 'addon_17', 'masp', 0, 0.00, 0.00, 0.00, NULL, NULL, NULL),
(87, '7', 'addon_17', 'tensp', 0, 0.00, 0.00, 0.00, NULL, NULL, NULL),
(88, '7', 'addon_17', 'hinhanh', 0, 0.00, 0.00, 0.00, NULL, NULL, NULL),
(89, '7', 'addon_17', 'gia', 0, 0.00, 0.00, 0.00, NULL, NULL, NULL),
(90, '7', 'addon_17', 'qty', 0, 0.00, 0.00, 0.00, NULL, NULL, NULL),
(91, '7', 'addon_17', 'type', 0, 0.00, 0.00, 0.00, NULL, NULL, NULL),
(92, '8', 'addon_24', 'masp', 0, 0.00, 0.00, 0.00, NULL, NULL, NULL),
(93, '8', 'addon_24', 'tensp', 0, 0.00, 0.00, 0.00, NULL, NULL, NULL),
(94, '8', 'addon_24', 'hinhanh', 0, 0.00, 0.00, 0.00, NULL, NULL, NULL),
(95, '8', 'addon_24', 'gia', 0, 0.00, 0.00, 0.00, NULL, NULL, NULL),
(96, '8', 'addon_24', 'qty', 0, 0.00, 0.00, 0.00, NULL, NULL, NULL),
(97, '8', 'addon_24', 'type', 0, 0.00, 0.00, 0.00, NULL, NULL, NULL),
(98, '8', 'addon_23', 'masp', 0, 0.00, 0.00, 0.00, NULL, NULL, NULL),
(99, '8', 'addon_23', 'tensp', 0, 0.00, 0.00, 0.00, NULL, NULL, NULL),
(100, '8', 'addon_23', 'hinhanh', 0, 0.00, 0.00, 0.00, NULL, NULL, NULL),
(101, '8', 'addon_23', 'gia', 0, 0.00, 0.00, 0.00, NULL, NULL, NULL),
(102, '8', 'addon_23', 'qty', 0, 0.00, 0.00, 0.00, NULL, NULL, NULL),
(103, '8', 'addon_23', 'type', 0, 0.00, 0.00, 0.00, NULL, NULL, NULL),
(104, '9', 'addon_24', 'masp', 0, 0.00, 0.00, 0.00, NULL, NULL, NULL),
(105, '9', 'addon_24', 'tensp', 0, 0.00, 0.00, 0.00, NULL, NULL, NULL),
(106, '9', 'addon_24', 'hinhanh', 0, 0.00, 0.00, 0.00, NULL, NULL, NULL),
(107, '9', 'addon_24', 'gia', 0, 0.00, 0.00, 0.00, NULL, NULL, NULL),
(108, '9', 'addon_24', 'qty', 0, 0.00, 0.00, 0.00, NULL, NULL, NULL),
(109, '9', 'addon_24', 'type', 0, 0.00, 0.00, 0.00, NULL, NULL, NULL),
(110, '9', 'addon_23', 'masp', 0, 0.00, 0.00, 0.00, NULL, NULL, NULL),
(111, '9', 'addon_23', 'tensp', 0, 0.00, 0.00, 0.00, NULL, NULL, NULL),
(112, '9', 'addon_23', 'hinhanh', 0, 0.00, 0.00, 0.00, NULL, NULL, NULL),
(113, '9', 'addon_23', 'gia', 0, 0.00, 0.00, 0.00, NULL, NULL, NULL),
(114, '9', 'addon_23', 'qty', 0, 0.00, 0.00, 0.00, NULL, NULL, NULL),
(115, '9', 'addon_23', 'type', 0, 0.00, 0.00, 0.00, NULL, NULL, NULL),
(116, '10', 'addon_23', 'masp', 0, 0.00, 0.00, 0.00, NULL, NULL, NULL),
(117, '10', 'addon_23', 'tensp', 0, 0.00, 0.00, 0.00, NULL, NULL, NULL),
(118, '10', 'addon_23', 'hinhanh', 0, 0.00, 0.00, 0.00, NULL, NULL, NULL),
(119, '10', 'addon_23', 'gia', 0, 0.00, 0.00, 0.00, NULL, NULL, NULL),
(120, '10', 'addon_23', 'qty', 0, 0.00, 0.00, 0.00, NULL, NULL, NULL),
(121, '10', 'addon_23', 'type', 0, 0.00, 0.00, 0.00, NULL, NULL, NULL),
(122, '10', 'addon_22', 'masp', 0, 0.00, 0.00, 0.00, NULL, NULL, NULL),
(123, '10', 'addon_22', 'tensp', 0, 0.00, 0.00, 0.00, NULL, NULL, NULL),
(124, '10', 'addon_22', 'hinhanh', 0, 0.00, 0.00, 0.00, NULL, NULL, NULL),
(125, '10', 'addon_22', 'gia', 0, 0.00, 0.00, 0.00, NULL, NULL, NULL),
(126, '10', 'addon_22', 'qty', 0, 0.00, 0.00, 0.00, NULL, NULL, NULL),
(127, '10', 'addon_22', 'type', 0, 0.00, 0.00, 0.00, NULL, NULL, NULL),
(128, '10', 'addon_24', 'masp', 0, 0.00, 0.00, 0.00, NULL, NULL, NULL),
(129, '10', 'addon_24', 'tensp', 0, 0.00, 0.00, 0.00, NULL, NULL, NULL),
(130, '10', 'addon_24', 'hinhanh', 0, 0.00, 0.00, 0.00, NULL, NULL, NULL),
(131, '10', 'addon_24', 'gia', 0, 0.00, 0.00, 0.00, NULL, NULL, NULL),
(132, '10', 'addon_24', 'qty', 0, 0.00, 0.00, 0.00, NULL, NULL, NULL),
(133, '10', 'addon_24', 'type', 0, 0.00, 0.00, 0.00, NULL, NULL, NULL),
(134, '11', 'addon_23', 'masp', 0, 0.00, 0.00, 0.00, NULL, NULL, NULL),
(135, '11', 'addon_23', 'tensp', 0, 0.00, 0.00, 0.00, NULL, NULL, NULL),
(136, '11', 'addon_23', 'hinhanh', 0, 0.00, 0.00, 0.00, NULL, NULL, NULL),
(137, '11', 'addon_23', 'gia', 0, 0.00, 0.00, 0.00, NULL, NULL, NULL),
(138, '11', 'addon_23', 'qty', 0, 0.00, 0.00, 0.00, NULL, NULL, NULL),
(139, '11', 'addon_23', 'type', 0, 0.00, 0.00, 0.00, NULL, NULL, NULL),
(140, '11', 'addon_22', 'masp', 0, 0.00, 0.00, 0.00, NULL, NULL, NULL),
(141, '11', 'addon_22', 'tensp', 0, 0.00, 0.00, 0.00, NULL, NULL, NULL),
(142, '11', 'addon_22', 'hinhanh', 0, 0.00, 0.00, 0.00, NULL, NULL, NULL),
(143, '11', 'addon_22', 'gia', 0, 0.00, 0.00, 0.00, NULL, NULL, NULL),
(144, '11', 'addon_22', 'qty', 0, 0.00, 0.00, 0.00, NULL, NULL, NULL),
(145, '11', 'addon_22', 'type', 0, 0.00, 0.00, 0.00, NULL, NULL, NULL),
(146, '11', 'addon_24', 'masp', 0, 0.00, 0.00, 0.00, NULL, NULL, NULL),
(147, '11', 'addon_24', 'tensp', 0, 0.00, 0.00, 0.00, NULL, NULL, NULL),
(148, '11', 'addon_24', 'hinhanh', 0, 0.00, 0.00, 0.00, NULL, NULL, NULL),
(149, '11', 'addon_24', 'gia', 0, 0.00, 0.00, 0.00, NULL, NULL, NULL),
(150, '11', 'addon_24', 'qty', 0, 0.00, 0.00, 0.00, NULL, NULL, NULL),
(151, '11', 'addon_24', 'type', 0, 0.00, 0.00, 0.00, NULL, NULL, NULL),
(152, '12', '04', '13x6cm', 1, 200000.00, 0.00, 0.00, NULL, NULL, NULL),
(153, '13', 'addon_23', 'masp', 0, 0.00, 0.00, 0.00, NULL, NULL, NULL),
(154, '13', 'addon_23', 'tensp', 0, 0.00, 0.00, 0.00, NULL, NULL, NULL),
(155, '13', 'addon_23', 'hinhanh', 0, 0.00, 0.00, 0.00, NULL, NULL, NULL),
(156, '13', 'addon_23', 'gia', 0, 0.00, 0.00, 0.00, NULL, NULL, NULL),
(157, '13', 'addon_23', 'qty', 0, 0.00, 0.00, 0.00, NULL, NULL, NULL),
(158, '13', 'addon_23', 'type', 0, 0.00, 0.00, 0.00, NULL, NULL, NULL),
(159, '13', 'addon_22', 'masp', 0, 0.00, 0.00, 0.00, NULL, NULL, NULL),
(160, '13', 'addon_22', 'tensp', 0, 0.00, 0.00, 0.00, NULL, NULL, NULL),
(161, '13', 'addon_22', 'hinhanh', 0, 0.00, 0.00, 0.00, NULL, NULL, NULL),
(162, '13', 'addon_22', 'gia', 0, 0.00, 0.00, 0.00, NULL, NULL, NULL),
(163, '13', 'addon_22', 'qty', 0, 0.00, 0.00, 0.00, NULL, NULL, NULL),
(164, '13', 'addon_22', 'type', 0, 0.00, 0.00, 0.00, NULL, NULL, NULL),
(165, '13', 'addon_14', 'masp', 0, 0.00, 0.00, 0.00, NULL, NULL, NULL),
(166, '13', 'addon_14', 'tensp', 0, 0.00, 0.00, 0.00, NULL, NULL, NULL),
(167, '13', 'addon_14', 'hinhanh', 0, 0.00, 0.00, 0.00, NULL, NULL, NULL),
(168, '13', 'addon_14', 'gia', 0, 0.00, 0.00, 0.00, NULL, NULL, NULL),
(169, '13', 'addon_14', 'qty', 0, 0.00, 0.00, 0.00, NULL, NULL, NULL),
(170, '13', 'addon_14', 'type', 0, 0.00, 0.00, 0.00, NULL, NULL, NULL),
(171, '13', 'addon_15', 'masp', 0, 0.00, 0.00, 0.00, NULL, NULL, NULL),
(172, '13', 'addon_15', 'tensp', 0, 0.00, 0.00, 0.00, NULL, NULL, NULL),
(173, '13', 'addon_15', 'hinhanh', 0, 0.00, 0.00, 0.00, NULL, NULL, NULL),
(174, '13', 'addon_15', 'gia', 0, 0.00, 0.00, 0.00, NULL, NULL, NULL),
(175, '13', 'addon_15', 'qty', 0, 0.00, 0.00, 0.00, NULL, NULL, NULL),
(176, '13', 'addon_15', 'type', 0, 0.00, 0.00, 0.00, NULL, NULL, NULL),
(177, '14', 'addon_24', 'masp', 0, 0.00, 0.00, 0.00, NULL, NULL, NULL),
(178, '14', 'addon_24', 'tensp', 0, 0.00, 0.00, 0.00, NULL, NULL, NULL),
(179, '14', 'addon_24', 'hinhanh', 0, 0.00, 0.00, 0.00, NULL, NULL, NULL),
(180, '14', 'addon_24', 'gia', 0, 0.00, 0.00, 0.00, NULL, NULL, NULL),
(181, '14', 'addon_24', 'qty', 0, 0.00, 0.00, 0.00, NULL, NULL, NULL),
(182, '14', 'addon_24', 'type', 0, 0.00, 0.00, 0.00, NULL, NULL, NULL),
(183, '14', 'addon_23', 'masp', 0, 0.00, 0.00, 0.00, NULL, NULL, NULL),
(184, '14', 'addon_23', 'tensp', 0, 0.00, 0.00, 0.00, NULL, NULL, NULL),
(185, '14', 'addon_23', 'hinhanh', 0, 0.00, 0.00, 0.00, NULL, NULL, NULL),
(186, '14', 'addon_23', 'gia', 0, 0.00, 0.00, 0.00, NULL, NULL, NULL),
(187, '14', 'addon_23', 'qty', 0, 0.00, 0.00, 0.00, NULL, NULL, NULL),
(188, '14', 'addon_23', 'type', 0, 0.00, 0.00, 0.00, NULL, NULL, NULL),
(189, '14', 'addon_22', 'masp', 0, 0.00, 0.00, 0.00, NULL, NULL, NULL),
(190, '14', 'addon_22', 'tensp', 0, 0.00, 0.00, 0.00, NULL, NULL, NULL),
(191, '14', 'addon_22', 'hinhanh', 0, 0.00, 0.00, 0.00, NULL, NULL, NULL),
(192, '14', 'addon_22', 'gia', 0, 0.00, 0.00, 0.00, NULL, NULL, NULL),
(193, '14', 'addon_22', 'qty', 0, 0.00, 0.00, 0.00, NULL, NULL, NULL),
(194, '14', 'addon_22', 'type', 0, 0.00, 0.00, 0.00, NULL, NULL, NULL),
(195, '15', '04', '13x6cm', 1, 200000.00, 0.00, 0.00, NULL, NULL, NULL),
(196, '16', 'addon_24', 'masp', 0, 0.00, 0.00, 0.00, NULL, NULL, NULL),
(197, '16', 'addon_24', 'tensp', 0, 0.00, 0.00, 0.00, NULL, NULL, NULL),
(198, '16', 'addon_24', 'hinhanh', 0, 0.00, 0.00, 0.00, NULL, NULL, NULL),
(199, '16', 'addon_24', 'gia', 0, 0.00, 0.00, 0.00, NULL, NULL, NULL),
(200, '16', 'addon_24', 'qty', 0, 0.00, 0.00, 0.00, NULL, NULL, NULL),
(201, '16', 'addon_24', 'type', 0, 0.00, 0.00, 0.00, NULL, NULL, NULL),
(202, '16', 'addon_23', 'masp', 0, 0.00, 0.00, 0.00, NULL, NULL, NULL),
(203, '16', 'addon_23', 'tensp', 0, 0.00, 0.00, 0.00, NULL, NULL, NULL),
(204, '16', 'addon_23', 'hinhanh', 0, 0.00, 0.00, 0.00, NULL, NULL, NULL),
(205, '16', 'addon_23', 'gia', 0, 0.00, 0.00, 0.00, NULL, NULL, NULL),
(206, '16', 'addon_23', 'qty', 0, 0.00, 0.00, 0.00, NULL, NULL, NULL),
(207, '16', 'addon_23', 'type', 0, 0.00, 0.00, 0.00, NULL, NULL, NULL),
(208, '16', 'addon_22', 'masp', 0, 0.00, 0.00, 0.00, NULL, NULL, NULL),
(209, '16', 'addon_22', 'tensp', 0, 0.00, 0.00, 0.00, NULL, NULL, NULL),
(210, '16', 'addon_22', 'hinhanh', 0, 0.00, 0.00, 0.00, NULL, NULL, NULL),
(211, '16', 'addon_22', 'gia', 0, 0.00, 0.00, 0.00, NULL, NULL, NULL),
(212, '16', 'addon_22', 'qty', 0, 0.00, 0.00, 0.00, NULL, NULL, NULL),
(213, '16', 'addon_22', 'type', 0, 0.00, 0.00, 0.00, NULL, NULL, NULL),
(214, '17', '04', '17x7.5cm', 1, 250000.00, 0.00, 0.00, NULL, NULL, NULL),
(215, '17', 'addon_24', 'masp', 0, 0.00, 0.00, 0.00, NULL, NULL, NULL),
(216, '17', 'addon_24', 'tensp', 0, 0.00, 0.00, 0.00, NULL, NULL, NULL),
(217, '17', 'addon_24', 'hinhanh', 0, 0.00, 0.00, 0.00, NULL, NULL, NULL),
(218, '17', 'addon_24', 'gia', 0, 0.00, 0.00, 0.00, NULL, NULL, NULL),
(219, '17', 'addon_24', 'qty', 0, 0.00, 0.00, 0.00, NULL, NULL, NULL),
(220, '17', 'addon_24', 'type', 0, 0.00, 0.00, 0.00, NULL, NULL, NULL),
(221, '17', 'addon_20', 'masp', 0, 0.00, 0.00, 0.00, NULL, NULL, NULL),
(222, '17', 'addon_20', 'tensp', 0, 0.00, 0.00, 0.00, NULL, NULL, NULL),
(223, '17', 'addon_20', 'hinhanh', 0, 0.00, 0.00, 0.00, NULL, NULL, NULL),
(224, '17', 'addon_20', 'gia', 0, 0.00, 0.00, 0.00, NULL, NULL, NULL),
(225, '17', 'addon_20', 'qty', 0, 0.00, 0.00, 0.00, NULL, NULL, NULL),
(226, '17', 'addon_20', 'type', 0, 0.00, 0.00, 0.00, NULL, NULL, NULL),
(227, '18', '04', '17x7.5cm', 4, 250000.00, 0.00, 0.00, NULL, NULL, NULL),
(228, '19', '05', '13x6cm', 2, 200000.00, 0.00, 0.00, NULL, NULL, NULL),
(229, '19', 'addon_24', 'masp', 0, 0.00, 0.00, 0.00, NULL, NULL, NULL),
(230, '19', 'addon_24', 'tensp', 0, 0.00, 0.00, 0.00, NULL, NULL, NULL),
(231, '19', 'addon_24', 'hinhanh', 0, 0.00, 0.00, 0.00, NULL, NULL, NULL),
(232, '19', 'addon_24', 'gia', 0, 0.00, 0.00, 0.00, NULL, NULL, NULL),
(233, '19', 'addon_24', 'qty', 0, 0.00, 0.00, 0.00, NULL, NULL, NULL),
(234, '19', 'addon_24', 'type', 0, 0.00, 0.00, 0.00, NULL, NULL, NULL),
(235, '19', 'addon_23', 'masp', 0, 0.00, 0.00, 0.00, NULL, NULL, NULL),
(236, '19', 'addon_23', 'tensp', 0, 0.00, 0.00, 0.00, NULL, NULL, NULL),
(237, '19', 'addon_23', 'hinhanh', 0, 0.00, 0.00, 0.00, NULL, NULL, NULL),
(238, '19', 'addon_23', 'gia', 0, 0.00, 0.00, 0.00, NULL, NULL, NULL),
(239, '19', 'addon_23', 'qty', 0, 0.00, 0.00, 0.00, NULL, NULL, NULL),
(240, '19', 'addon_23', 'type', 0, 0.00, 0.00, 0.00, NULL, NULL, NULL),
(241, '20', '04', '17x7.5cm', 1, 0.00, 0.00, 0.00, NULL, NULL, NULL),
(242, '20', '06', '17x7.5cm', 1, 0.00, 0.00, 0.00, NULL, NULL, NULL),
(243, '20', '05', '21x7.5', 2, 0.00, 0.00, 0.00, NULL, NULL, NULL),
(244, '21', 'addon_24', 'masp', 0, 0.00, 0.00, 0.00, NULL, NULL, NULL),
(245, '21', 'addon_24', 'tensp', 0, 0.00, 0.00, 0.00, NULL, NULL, NULL),
(246, '21', 'addon_24', 'hinhanh', 0, 0.00, 0.00, 0.00, NULL, NULL, NULL),
(247, '21', 'addon_24', 'gia', 0, 0.00, 0.00, 0.00, NULL, NULL, NULL),
(248, '21', 'addon_24', 'qty', 0, 0.00, 0.00, 0.00, NULL, NULL, NULL),
(249, '21', 'addon_24', 'type', 0, 0.00, 0.00, 0.00, NULL, NULL, NULL),
(250, '21', 'addon_23', 'masp', 0, 0.00, 0.00, 0.00, NULL, NULL, NULL),
(251, '21', 'addon_23', 'tensp', 0, 0.00, 0.00, 0.00, NULL, NULL, NULL),
(252, '21', 'addon_23', 'hinhanh', 0, 0.00, 0.00, 0.00, NULL, NULL, NULL),
(253, '21', 'addon_23', 'gia', 0, 0.00, 0.00, 0.00, NULL, NULL, NULL),
(254, '21', 'addon_23', 'qty', 0, 0.00, 0.00, 0.00, NULL, NULL, NULL),
(255, '21', 'addon_23', 'type', 0, 0.00, 0.00, 0.00, NULL, NULL, NULL),
(256, '21', 'addon_22', 'masp', 0, 0.00, 0.00, 0.00, NULL, NULL, NULL),
(257, '21', 'addon_22', 'tensp', 0, 0.00, 0.00, 0.00, NULL, NULL, NULL),
(258, '21', 'addon_22', 'hinhanh', 0, 0.00, 0.00, 0.00, NULL, NULL, NULL),
(259, '21', 'addon_22', 'gia', 0, 0.00, 0.00, 0.00, NULL, NULL, NULL),
(260, '21', 'addon_22', 'qty', 0, 0.00, 0.00, 0.00, NULL, NULL, NULL),
(261, '21', 'addon_22', 'type', 0, 0.00, 0.00, 0.00, NULL, NULL, NULL),
(262, '21', 'addon_17', 'masp', 0, 0.00, 0.00, 0.00, NULL, NULL, NULL),
(263, '21', 'addon_17', 'tensp', 0, 0.00, 0.00, 0.00, NULL, NULL, NULL),
(264, '21', 'addon_17', 'hinhanh', 0, 0.00, 0.00, 0.00, NULL, NULL, NULL),
(265, '21', 'addon_17', 'gia', 0, 0.00, 0.00, 0.00, NULL, NULL, NULL),
(266, '21', 'addon_17', 'qty', 0, 0.00, 0.00, 0.00, NULL, NULL, NULL),
(267, '21', 'addon_17', 'type', 0, 0.00, 0.00, 0.00, NULL, NULL, NULL),
(268, '22', '04', '17x7.5cm', 1, 0.00, 0.00, 0.00, NULL, NULL, NULL),
(269, '22', '05', '17x7.5', 1, 0.00, 0.00, 0.00, NULL, NULL, NULL),
(270, '22', 'addon_18', 'masp', 0, 0.00, 0.00, 0.00, NULL, NULL, NULL),
(271, '22', 'addon_18', 'tensp', 0, 0.00, 0.00, 0.00, NULL, NULL, NULL),
(272, '22', 'addon_18', 'hinhanh', 0, 0.00, 0.00, 0.00, NULL, NULL, NULL),
(273, '22', 'addon_18', 'gia', 0, 0.00, 0.00, 0.00, NULL, NULL, NULL),
(274, '22', 'addon_18', 'qty', 0, 0.00, 0.00, 0.00, NULL, NULL, NULL),
(275, '22', 'addon_18', 'type', 0, 0.00, 0.00, 0.00, NULL, NULL, NULL),
(276, '22', 'addon_19', 'masp', 0, 0.00, 0.00, 0.00, NULL, NULL, NULL),
(277, '22', 'addon_19', 'tensp', 0, 0.00, 0.00, 0.00, NULL, NULL, NULL),
(278, '22', 'addon_19', 'hinhanh', 0, 0.00, 0.00, 0.00, NULL, NULL, NULL),
(279, '22', 'addon_19', 'gia', 0, 0.00, 0.00, 0.00, NULL, NULL, NULL),
(280, '22', 'addon_19', 'qty', 0, 0.00, 0.00, 0.00, NULL, NULL, NULL),
(281, '22', 'addon_19', 'type', 0, 0.00, 0.00, 0.00, NULL, NULL, NULL),
(282, '23', '04', '17x7.5cm', 1, 0.00, 0.00, 0.00, NULL, NULL, NULL),
(283, '23', '01', '13x6cm', 1, 0.00, 0.00, 0.00, NULL, NULL, NULL),
(284, '24', '04', '13x6cm', 1, 200000.00, 0.00, 0.00, NULL, NULL, NULL),
(285, '25', '01', '17x7.5cm', 2, 0.00, 0.00, 0.00, NULL, NULL, NULL),
(286, '25', '05', '17x7.5', 1, 0.00, 0.00, 0.00, NULL, NULL, NULL),
(287, '25', '04', '25x7.5cm', 1, 0.00, 0.00, 0.00, NULL, NULL, NULL),
(288, '26', '04', '13x6cm', 1, 200000.00, 0.00, 0.00, NULL, NULL, NULL),
(289, '27', '04', '13x6cm', 1, 200000.00, 0.00, 0.00, NULL, NULL, NULL),
(290, '28', '05', '17x7.5', 1, 0.00, 0.00, 0.00, NULL, NULL, NULL),
(291, '28', '04', '17x7.5cm', 2, 0.00, 0.00, 0.00, NULL, NULL, NULL),
(292, '29', '04', '17x7.5cm', 1, 250000.00, 0.00, 0.00, NULL, NULL, NULL),
(293, '29', '05', '17x7.5', 1, 300000.00, 0.00, 0.00, NULL, NULL, NULL),
(294, '30', '04', '21x7.5cm', 1, 300000.00, 0.00, 0.00, NULL, NULL, NULL),
(295, '30', '05', '17x7.5', 1, 300000.00, 0.00, 0.00, NULL, NULL, NULL),
(296, '30', '06', '17x7.5cm', 1, 300000.00, 0.00, 0.00, NULL, NULL, NULL),
(297, '30', 'addon_24', 'masp', 0, 0.00, 0.00, 0.00, NULL, NULL, NULL),
(298, '30', 'addon_24', 'tensp', 0, 0.00, 0.00, 0.00, NULL, NULL, NULL),
(299, '30', 'addon_24', 'hinhanh', 0, 0.00, 0.00, 0.00, NULL, NULL, NULL),
(300, '30', 'addon_24', 'gia', 0, 0.00, 0.00, 0.00, NULL, NULL, NULL),
(301, '30', 'addon_24', 'qty', 0, 0.00, 0.00, 0.00, NULL, NULL, NULL),
(302, '30', 'addon_24', 'type', 0, 0.00, 0.00, 0.00, NULL, NULL, NULL),
(303, '30', 'addon_17', 'masp', 0, 0.00, 0.00, 0.00, NULL, NULL, NULL),
(304, '30', 'addon_17', 'tensp', 0, 0.00, 0.00, 0.00, NULL, NULL, NULL),
(305, '30', 'addon_17', 'hinhanh', 0, 0.00, 0.00, 0.00, NULL, NULL, NULL),
(306, '30', 'addon_17', 'gia', 0, 0.00, 0.00, 0.00, NULL, NULL, NULL),
(307, '30', 'addon_17', 'qty', 0, 0.00, 0.00, 0.00, NULL, NULL, NULL),
(308, '30', 'addon_17', 'type', 0, 0.00, 0.00, 0.00, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `promotions`
--

CREATE TABLE `promotions` (
  `id` int(11) NOT NULL,
  `code` varchar(100) NOT NULL,
  `type` varchar(20) NOT NULL COMMENT 'percent|fixed',
  `value` decimal(12,2) NOT NULL DEFAULT 0.00,
  `min_order_amount` decimal(12,2) DEFAULT 0.00,
  `start_date` datetime DEFAULT NULL,
  `end_date` datetime DEFAULT NULL,
  `status` varchar(20) DEFAULT 'active',
  `usage_limit` int(11) DEFAULT NULL,
  `usage_count` int(11) DEFAULT 0,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `promotions`
--

INSERT INTO `promotions` (`id`, `code`, `type`, `value`, `min_order_amount`, `start_date`, `end_date`, `status`, `usage_limit`, `usage_count`, `created_at`, `updated_at`) VALUES
(1, 'mmbeo', 'percent', 60.00, 100000000.00, '2025-10-24 18:50:00', '2025-10-27 18:50:00', 'active', 10, 0, '2025-10-25 18:50:51', '2025-10-25 19:16:11'),
(2, 'mmbeov2', 'percent', 60.00, 0.00, '2025-10-24 19:17:00', '2025-10-27 19:17:00', 'active', 100, 0, '2025-10-25 19:17:54', NULL),
(3, 'Giảm 20/11', 'percent', 5.00, 10.00, '2025-11-15 22:45:00', '2025-12-15 22:45:00', 'active', 100000, 0, '2025-11-16 22:45:57', NULL),
(4, 'Giáng sinh 25/12', 'percent', 10.00, 100000.00, '2025-11-29 01:42:00', '2025-12-30 01:42:00', 'active', 1000, 0, '2025-11-30 01:43:00', NULL),
(5, 'tết dương', 'percent', 15.00, 100000.00, '2025-11-30 01:43:00', '2026-01-30 01:43:00', 'active', 1000, 0, '2025-11-30 01:43:50', NULL);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `promotion_code`
--

CREATE TABLE `promotion_code` (
  `id` int(11) NOT NULL,
  `code` varchar(50) NOT NULL,
  `value` decimal(10,2) NOT NULL,
  `type` enum('percent','amount') NOT NULL,
  `start_date` datetime NOT NULL,
  `end_date` datetime NOT NULL,
  `status` enum('active','inactive') DEFAULT 'active',
  `usage_limit` int(11) DEFAULT NULL,
  `used_count` int(11) DEFAULT 0,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `promotion_code`
--

INSERT INTO `promotion_code` (`id`, `code`, `value`, `type`, `start_date`, `end_date`, `status`, `usage_limit`, `used_count`, `created_at`, `updated_at`) VALUES
(1, '20THANG11', 30.00, 'percent', '2025-11-15 00:00:00', '2025-11-21 23:59:59', 'active', 100, 0, '2025-10-25 18:40:29', '2025-10-25 18:40:29'),
(2, '20THANG10', 20.00, 'percent', '2025-10-15 00:00:00', '2025-10-21 23:59:59', 'active', 100, 0, '2025-10-25 18:40:29', '2025-10-25 18:40:29'),
(3, 'GIANGSINH', 500000.00, 'amount', '2025-12-20 00:00:00', '2025-12-26 23:59:59', 'active', 50, 0, '2025-10-25 18:40:29', '2025-10-25 18:40:29'),
(4, 'TET2026', 15.00, 'percent', '2026-01-20 00:00:00', '2026-02-10 23:59:59', 'active', 200, 0, '2025-10-25 18:40:29', '2025-10-25 18:40:29');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `reviews`
--

CREATE TABLE `reviews` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `user_name` varchar(255) NOT NULL,
  `user_email` varchar(255) NOT NULL,
  `product_id` varchar(50) NOT NULL,
  `rating` int(11) NOT NULL DEFAULT 5,
  `comment` text DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `status` varchar(20) DEFAULT 'pending',
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `reviews`
--

INSERT INTO `reviews` (`id`, `user_id`, `user_name`, `user_email`, `product_id`, `rating`, `comment`, `image`, `status`, `created_at`, `updated_at`) VALUES
(1, 15, 'Công Đoàn', 'hoilovedtl2307@gmail.com', '31', 5, 'Bánh ngon vcl', 'review_1764698252_692f288c6d196.JPG', 'approved', '2025-12-03 00:57:32', '2025-12-03 00:57:50'),
(2, 15, 'Công Đoàn', 'hoilovedtl2307@gmail.com', '52', 1, 'bánh ăn như cc', NULL, 'approved', '2025-12-03 01:18:00', '2025-12-03 01:18:22');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `tblloaisp`
--

CREATE TABLE `tblloaisp` (
  `maLoaiSP` varchar(20) NOT NULL,
  `tenLoaiSP` varchar(50) NOT NULL,
  `moTaLoaiSP` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `tblloaisp`
--

INSERT INTO `tblloaisp` (`maLoaiSP`, `tenLoaiSP`, `moTaLoaiSP`) VALUES
('BanhInHinh', 'Bánh in hình ', 'Bánh in hình theo yêu cầu'),
('BanhKem', 'Bánh hoa quả', 'kem béo và hoa quả tươi ngon mỗi ngày'),
('Banhman', 'Bánh mặn', 'Bánh mới ra lò'),
('BanhMi', 'Bánh mì', 'Bánh mì thơm ngon nóng hổi mới ra lò'),
('BanhSinhNhat', 'Bánh sinh nhật', 'Bánh sinh nhật của nhà vua'),
('BanhTrangMieng', 'Bánh tráng miệng', 'Bánh để ăn sau khi dùng bữa '),
('BanhTrungBay', 'Bánh trưng bày ', 'Bánh để trang trí '),
('CacLoaiBanhKhac', 'Các loại bánh khác ', 'Các loại bánh ngoài danh sách '),
('HopBanhTet', 'Hộp bánh tết - Trung Cấp ', 'Bánh bán trong dịp tết'),
('HopBanhTet2', 'Hộp bánh tết - Cao Cấp ', 'Cho người có tiền ăn'),
('PhuKien', 'Phụ kiện', 'Dùng để bán cùng với bánh');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `tblsanpham`
--

CREATE TABLE `tblsanpham` (
  `maLoaiSP` varchar(50) NOT NULL,
  `masp` varchar(20) NOT NULL,
  `tensp` varchar(50) NOT NULL,
  `hinhanh` varchar(50) NOT NULL,
  `soluong` int(11) NOT NULL,
  `mota` varchar(200) NOT NULL,
  `createDate` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `tblsanpham`
--

INSERT INTO `tblsanpham` (`maLoaiSP`, `masp`, `tensp`, `hinhanh`, `soluong`, `mota`, `createDate`) VALUES
('Bánh hoa quả', '01', 'Bánh kem dâu socola', 'Bánh kem dâu chocolate.JPG', 198, 'Bánh kem dâu thơm mát ', '2025-11-25'),
('Bánh hoa quả', '02', 'Bánh kem hoa quả', 'Bánh kem hoa quả.jpg', 100, 'Bánh kem hoa quả tươi ngon', '2025-11-24'),
('Bánh hoa quả', '03', 'Bánh kem đào', 'Bánh kem đào lát mỏng.JPG', 100, 'bánh kem đào lát mỏng siêu ngon', '2025-11-24'),
('Bánh hoa quả', '04', 'Bánh táo xanh', 'Bánh táo xanh lát mỏng.JPG', 243, 'Bánh táo từ cay táo xanh', '2025-11-25'),
('Bánh hoa quả', '05', 'Bánh kem xoài chanh', 'Bánh kem xoài chanh.JPG', 196, 'bánh kem xoài xanh với chanh chua kết hợp hoàn hảo', '2025-11-25'),
('Bánh hoa quả', '06', 'Bánh kem dâu tuyết', 'Bánh kem dâu tuyết.JPG', 199, 'Bánh kem dâu tuyết mát lạnh tươi mát', '2025-11-25'),
('Bánh hoa quả', '07', 'Bánh kem việt quất', 'Bánh kem việt quất tím .JPG', 150, 'Bánh kem việt quất tím với việt quất tươi ngon', '2025-11-25'),
('Bánh hoa quả', '08', 'Bánh việt quất xanh', 'Bánh kem việt quất.JPG', 50, 'Bánh việt quất xanh tươi ngon', '2025-11-25'),
('Bánh hoa quả', '09', 'Bánh kem dâu cổ điển', 'Bánh kem dâu cổ điển.JPG', 300, 'bánh dâu mùi vị cổ điển ', '2025-11-25'),
('Phụ kiện', '10', 'Trang trí sinh nhật', 'Trang trí sinh nhật loại 1.JPG', 50, 'Trang trí vật loại 1 ', '2025-11-27'),
('Phụ kiện', '11', 'Trang trí sinh nhật ', 'Trang trí sinh nhật loại 2.JPG', 50, 'Trang trí sinh nhật loại 2 ', '2025-11-27'),
('Phụ kiện', '12', 'Trang trí sinh nhật ', 'Trang trí sinh nhật loại 3.JPG', 50, 'Trang trí sinh nhật loại 3 ', '2025-11-27'),
('Phụ kiện', '13', 'Nến pháo bông ', 'Nến pháo bông.JPG', 50, 'Nến pháo bông ', '2025-11-27'),
('Phụ kiện', '14', 'Mũ sinh nhật màu hồng', 'Mũ sinh nhật màu hồng.JPG', 50, 'Mũ sinh nhật màu hồng ', '2025-11-27'),
('Phụ kiện', '15', 'Mũ sinh nhật của vua', 'Mũ sinh nhật hoàng gia.JPG', 50, 'Mũ sinh nhật hoàng gia', '2025-11-27'),
('Phụ kiện', '16', 'Mũ sinh nhật cầu vồng', 'Mũ sinh nhật cầu vồng.JPG', 50, 'Mũ sinh nhật cầu vồng', '2025-11-27'),
('Phụ kiện', '17', 'Kính sinh nhật màu vàng ', 'Kính sinh nhật màu vàng.JPG', 50, 'Kính sinh nhật màu vàng ', '2025-11-27'),
('Phụ kiện', '18', 'Kính sinh nhật màu bạc', 'Kính sinh nhật màu bạc.JPG', 50, 'Kính sinh nhật màu bạc', '2025-11-27'),
('Phụ kiện', '19', 'Kính sinh nhật màu hồng ', 'Kính sinh nhật màu hồng.JPG', 50, 'Kính sinh nhật màu hồng ', '2025-11-27'),
('Phụ kiện', '20', 'Đĩa nhựa ăn bánh ', 'Đĩa nhựa ăn bánh.JPG', 50, 'Đĩa nhựa ăn bánh ', '2025-11-27'),
('Phụ kiện', '21', 'Cốc giấy ', 'Cốc giấy.JPG', 50, 'Cốc giấy', '2025-11-27'),
('Phụ kiện', '22', 'Nến sinh nhật vàng', 'Bộ nến sinh nhật số vàng Gold.JPG', 50, 'Nến sinh nhật bằng số vàng', '2025-11-27'),
('Phụ kiện', '23', 'Nến sinh nhật số cơ ', 'Bộ nến sinh nhật số cơ bản.JPG', 50, 'Nến sinh nhật số cơ bản ', '2025-11-27'),
('Phụ kiện', '24', 'Bộ nến bay màu vàng', 'Bộ bóng bay số màu vàng Gold.JPG', 50, 'Bộ nến bay màu vàng', '2025-11-27'),
('Phụ kiện', '25', 'Bóng bay hồng và vàng', 'Bóng bay hồng và vàng.JPG', 50, 'Bóng bay hồng và vàng', '2025-11-27'),
('Bánh in hình ', '26', 'Bánh WWE', 'Bánh WWE.jpg', 100, 'Bánh WWE', '2025-11-29'),
('Bánh in hình ', '27', 'Bánh Arsenal ', 'Bánh Arsenal.jpg', 150, 'Bánh Arsenal ', '2025-11-29'),
('Bánh in hình ', '28', 'Bánh Manchester United', 'Bánh Manchester United.JPG', 100, 'Bánh Manchester United', '2025-11-29'),
('Bánh in hình ', '29', 'Bánh Son Goku ', 'Bánh SonGoku.JPG', 150, 'Bánh Son Goku ', '2025-11-29'),
('Bánh in hình ', '30', 'Bánh Hitler', 'ChatGPT Image Nov 29, 2025, 08_53_54 AM.png', 100, 'Bánh Hitler', '2025-11-29'),
('Bánh in hình ', '31', 'Bánh Avengers', 'Bánh Avengers.JPG', 100, 'Bánh Avengers', '2025-11-29'),
('Bánh in hình ', '32', 'Bánh Spiderman', 'Bánh Spiderman.JPG', 50, 'Bánh Spiderman', '2025-11-29'),
('Bánh sinh nhật', '33', 'Bánh sinh nhật dâu Marshmallow', 'Bánh kem dâu Marshmallow.jpg', 100, 'Bánh kem dâu Marshmallow', '2025-11-29'),
('Bánh sinh nhật', '34', 'Bánh sinh nhật trái tim hường phấn', 'Bánh kem trái tim hường phấn.JPG', 100, 'Bánh sinh nhật trái tim hường phấn', '2025-11-29'),
('Bánh sinh nhật', '35', 'Bánh sinh nhật oreo', 'Bánh Oreo kem.JPG', 100, 'Bánh sinh nhật oreo', '2025-11-29'),
('Bánh sinh nhật', '36', 'Bánh sinh nhật kem tím nơ đen ', 'Bánh kem tím nơ đen.JPG', 150, 'Bánh sinh nhật kem tím nơ đen ', '2025-11-29'),
('Bánh sinh nhật', '37', 'Bánh xinh nhật cherry xanh ', 'Bánh kem cherry xanh.JPG', 140, 'Bánh xinh nhật cherry xanh ', '2025-11-29'),
('Bánh sinh nhật', '38', 'Bánh kem sinh nhật gấu và thỏ ', 'Bánh kem gấu và thỏ.JPG', 100, 'Bánh kem sinh nhật gấu và thỏ ', '2025-11-29'),
('Bánh sinh nhật', '39', 'Bánh sinh nhật chocolate phủ cherry ', 'Bánh chocolate phủ cherry.JPG', 100, 'Bánh sinh nhật chocolate phủ cherry ', '2025-11-29'),
('Bánh sinh nhật', '40', 'Bánh sinh nhật cầu vồng', 'Bánh sinh nhật cầu vồng.JPG', 50, 'Bánh sinh nhật cầu vồng', '2025-11-29'),
('Bánh sinh nhật', '41', 'Bánh sinh nhật trái tim phủ cherry ', 'Bánh kem trái tim phủ cherry.JPG', 100, 'Bánh sinh nhật trái tim phủ cherry ', '2025-11-29'),
('Bánh mì', '42', 'Bánh gối ', 'Bánh gối.jpg', 50, 'Bánh gối ', '2025-11-29'),
('Bánh mì', '43', 'Bánh mì dài', 'Bánh mì dài.jpg', 50, 'Bánh mì dài', '2025-11-29'),
('Bánh mì', '44', 'Bánh mì tròn', 'Bánh mì tròn.jpg', 50, 'Bánh mì tròn', '2025-11-29'),
('Bánh mì', '45', 'Bánh sừng bò', 'Bánh sừng bò.jpg', 50, 'Bánh sừng bò', '2025-11-29'),
('Bánh mì', '46', 'Bánh sừng bò cuộn', 'Bánh sừng bò cuộn.jpg', 50, 'Bánh sừng bò cuộn', '2025-11-29'),
('Bánh mặn', '47', 'Bánh Doner kebab thịt cừu', 'Bánh Doner kebab thịt cừu.jpg', 50, 'Bánh Doner kebab thịt cừu', '2025-11-29'),
('Bánh mặn', '48', 'Bánh Burrito hoàn chỉnh', 'Bánh Burrito hoàn chỉnh.jpg', 50, 'Bánh Burrito hoàn chỉnh', '2025-11-29'),
('Bánh mặn', '49', 'Bánh tacos mexico', 'Bánh tacos mexico.jpg', 50, 'Bánh tacos mexico', '2025-11-29'),
('Bánh mặn', '50', 'Bánh nướng nhồi thịt bò', 'Bánh nướng nhồi thịt bò.jpg', 50, 'Bánh nướng nhồi thịt bò', '2025-11-29'),
('Bánh mặn', '51', 'Bánh nướng nhồi thịt gà', 'Bánh nướng nhồi thịt gà.jpg', 50, 'Bánh nướng nhồi thịt gà', '2025-11-29'),
('Bánh trưng bày ', '52', 'Bánh Blue Valentine', 'Bánh Blue Valentine.jpg', 50, 'Bánh Blue Valentine', '2025-11-29'),
('Bánh trưng bày ', '53', 'Bánh cưới hồng tuyền', 'Bánh cưới hồng tuyền.JPG', 50, 'Bánh cưới hồng tuyền', '2025-11-29'),
('Bánh trưng bày ', '54', 'Bánh cưới 3 tầng hoa hồng', 'Bánh cưới 3 tầng hoa hồng.JPG', 50, 'Bánh cưới 3 tầng hoa hồng', '2025-11-29'),
('Bánh trưng bày ', '55', 'Bánh cưới hoa trắng', 'Bánh cưới hoa trắng.JPG', 60, 'Bánh cưới hoa trắng', '2025-11-29'),
('Bánh tráng miệng', '56', 'Bánh su kem oreo', 'Bánh su kem oreo.jpg', 50, 'Bánh su kem oreo', '2025-11-29'),
('Bánh tráng miệng', '57', 'Bánh su kem matcha', 'Bánh su kem matcha.jpg', 100, 'Bánh su kem matcha', '2025-11-29'),
('Bánh tráng miệng', '58', 'Bánh su kem cơ bản', 'Bánh su kem cơ bản.jpg', 100, 'Bánh su kem cơ bản', '2025-11-29'),
('Bánh tráng miệng', '59', 'Cupcake giáng sinh', 'Cupcake giáng sinh.jpg', 40, 'Cupcake giáng sinh', '2025-11-29'),
('Bánh tráng miệng', '60', 'Cupcake việt quất', 'Cupcake việt quất.jpg', 50, 'Cupcake việt quất', '2025-11-29'),
('Bánh tráng miệng', '61', 'Cupcake chocolate cherry', 'Cupcake chocolate cherry.jpg', 50, 'Cupcake chocolate cherry', '2025-11-29'),
('Bánh tráng miệng', '62', 'Cupcake Chocolate', 'Cupcake Chocolate.png', 40, 'Cupcake Chocolate', '2025-11-29'),
('Bánh tráng miệng', '63', 'Cupcake kem caramen', 'Cupcake kem caramen.jpg', 50, 'Cupcake kem caramen', '2025-11-29'),
('Bánh tráng miệng', '64', 'Bánh donut nhồi kem mứt', 'Bánh donut nhồi kem mứt.jpg', 50, 'Bánh donut nhồi kem mứt', '2025-11-29'),
('Bánh tráng miệng', '65', 'Bánh donut nhồi kem cheese', 'Bánh donut nhồi kem cheese.jpg', 50, 'Bánh donut nhồi kem cheese', '2025-11-29'),
('Bánh tráng miệng', '66', 'Bánh bông lan lõi mứt', 'Bánh bông lan lõi mứt.jpg', 58, 'Bánh bông lan lõi mứt', '2025-11-29'),
('Bánh tráng miệng', '67', 'Bánh cacao các loại hạt', 'Bánh cacao các loại hạt.jpg', 54, 'Bánh cacao các loại hạt', '2025-11-29'),
('Bánh tráng miệng', '68', 'Bánh cheesecake', 'Bánh cheesecake.JPG', 43, 'Bánh cheesecake', '2025-11-29'),
('Bánh tráng miệng', '69', 'Bánh pie dâu', 'Bánh pie dâu.JPG', 50, 'Bánh pie dâu', '2025-11-29'),
('Bánh tráng miệng', '70', 'Bánh tiramisu', 'Bánh tiramisu.JPG', 50, 'Bánh tiramisu', '2025-11-29'),
('Bánh tráng miệng', '71', 'Bánh red velvet', 'Bánh red velvet.JPG', 45, 'Bánh red velvet', '2025-11-29'),
('Các loại bánh khác ', '72', 'Bánh trứng tart', 'Bánh trứng tart.jpg', 25, 'Bánh trứng tart', '2025-11-29'),
('Bánh tráng miệng', '73', 'Bánh chanh lát', 'Bánh chanh lát.JPG', 50, 'Bánh chanh lát', '2025-11-29'),
('Các loại bánh khác ', '74', 'Bánh rán phủ hạt matcha', 'Bánh rán phủ hạt matcha.jpg', 55, 'Bánh rán phủ hạt matcha', '2025-11-29'),
('Các loại bánh khác ', '75', 'Bánh rán phủ hạt', 'Bánh rán phủ hạt.jpg', 50, 'Bánh rán phủ hạt', '2025-11-29'),
('Các loại bánh khác ', '76', 'Bánh quy giáng sinh', 'Bánh quy giáng sinh.jpg', 50, 'Bánh quy giáng sinh', '2025-11-29'),
('Hộp bánh tết - Trung Cấp ', '77', 'set quà tết thiên phương ', 'thienphuong.jpg', 50, 'set quà tết thiên phương ', '2025-12-03'),
('Hộp bánh tết - Trung Cấp ', '78', 'set quà tết thanh tước ', 'thanhtuoc.jpg', 50, 'set quà tết thanh tước ', '2025-12-03'),
('Hộp bánh tết - Trung Cấp ', '79', 'set quà tết mộc lan', 'molan.jpg', 50, 'set quà tết mộc lan', '2025-12-03'),
('Hộp bánh tết - Trung Cấp ', '80', 'set quà tết kim lộc', 'kimloc.jpg', 50, 'set quà tết kim lộc', '2025-12-03'),
('Hộp bánh tết - Cao Cấp ', '81', 'set quà tết ma đào', 'madao.jpg', 50, 'set quà tết ma đào', '2025-12-03'),
('Hộp bánh tết - Cao Cấp ', '82', 'set quà tết hồng mã', 'hongma.jpg', 50, 'set quà tết hồng mã', '2025-12-03'),
('Hộp bánh tết - Cao Cấp ', '83', 'set quà tết xuân diệu 4 hũ ', 'xuandieu4.jpg', 50, 'set quà tết xuân diệu 4 hũ ', '2025-12-03'),
('Hộp bánh tết - Cao Cấp ', '84', 'set quà tết xuân diệu 6 hũ ', 'xuandieu6.jpg', 50, 'set quà tết xuân diệu 6 hũ ', '2025-12-03');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `tbluser`
--

CREATE TABLE `tbluser` (
  `user_id` int(11) NOT NULL,
  `fullname` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `is_verified` tinyint(1) DEFAULT 0,
  `verification_token` int(11) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `role` enum('user','admin') DEFAULT 'user'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `tbluser`
--

INSERT INTO `tbluser` (`user_id`, `fullname`, `email`, `password`, `is_verified`, `verification_token`, `created_at`, `role`) VALUES
(14, 'Tạ Văn Hội', 'chitogelovehoi@gmail.com', '$2y$10$6Nh4E756KMVHD4Srhyrple9rmma6SUw2LORdNKTrV1Pe3W3DAOBRO', 0, 0, '2025-10-15 19:41:24', 'admin'),
(15, 'Công Đoàn', 'hoilovedtl2307@gmail.com', '$2y$10$rr1e96nYEzjJAyl/rpm13.qpmA7DVEfVOjJW/coSEOBctkpaykY2i', 0, 73, '2025-10-15 19:44:38', 'user'),
(16, 'admin', 'hoivuaveo3@gmail.com', '$2y$10$q9Z9/tGNEDwOun.lBW/XWemRYgF1gPItA1gmnGI3KFxHrZowXDjb.', 0, 41, '2025-10-15 19:50:35', 'admin'),
(17, 'Công Đoàn', 'vuacuaveo3@gmail.com', '$2y$10$QL3UG37CpgQG77KCUebKpOkwM/oS51vedZggp13Zvs1mUEGrur2v.', 0, 465, '2025-10-22 18:28:59', 'user'),
(18, 'Tạ Văn Hội', 'vanhoi1704@gmail.com', '$2y$10$Qgtg2i4nt/8q8BYt9M3OoOP4PB4ATqQYbMVUxMXww/ENvXvhJxN2i', 0, 485306, '2025-10-25 19:12:27', 'user'),
(19, 'Hoài', 'hoaiveo33@gmail.com', '$2y$10$MN/J3v07DBkXFbHth95t5.8BD1l5xbjR2gJFDejrkCtrxlrHP43w6', 0, 0, '2025-11-21 18:35:08', 'user'),
(20, 'Hưng', 'hungveo333@gmail.com', '$2y$10$YV.wcW5i3jWJUdHvy.P66OvcCmZYq//v9YPhiC/5p8z6mF9LcCTi2', 0, 0, '2025-11-21 18:38:08', 'user');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `tbl_sanpham_size`
--

CREATE TABLE `tbl_sanpham_size` (
  `id` int(11) NOT NULL,
  `masp` varchar(20) NOT NULL,
  `size` varchar(50) NOT NULL,
  `giaNhap` int(11) NOT NULL,
  `giaXuat` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `tbl_sanpham_size`
--

INSERT INTO `tbl_sanpham_size` (`id`, `masp`, `size`, `giaNhap`, `giaXuat`) VALUES
(19, '02', '13x6cm', 100000, 150000),
(20, '02', '17x7.5cm', 150000, 200000),
(21, '03', '13x6cm', 100000, 200000),
(22, '03', '17x7.5cm', 150000, 250000),
(23, '03', '21x7.5cm', 200000, 300000),
(24, '04', '13x6cm', 100000, 200000),
(25, '04', '17x7.5cm', 150000, 250000),
(26, '04', '21x7.5cm', 200000, 300000),
(27, '04', '25x7.5cm', 250000, 400000),
(31, '05', '13x6cm', 100000, 200000),
(32, '05', '17x7.5', 150000, 300000),
(33, '05', '21x7.5', 200000, 350000),
(34, '06', '13x6cm', 100000, 200000),
(35, '06', '17x7.5cm', 150000, 300000),
(36, '06', '21x7.5cm', 200000, 400000),
(37, '01', '13x6cm', 100000, 200000),
(38, '01', '17x7.5cm', 150000, 250000),
(39, '07', '13x6cm', 100000, 200000),
(40, '07', '17x7.5cm', 150000, 300000),
(41, '07', '21x7.5cm', 200000, 400000),
(48, '08', '13x6cm', 100000, 200000),
(49, '08', '17x7.5cm', 150000, 300000),
(50, '08', '21x7.5cm', 200000, 400000),
(51, '09', 'set 1', 100000, 200000),
(52, '09', 'set 5', 200000, 400000),
(55, '11', 'Loại 1 ', 5000, 20000),
(56, '12', 'Loại 1 ', 5000, 20000),
(57, '10', 'Loại 1 ', 5000, 20000),
(58, '13', 'Loại 1 ', 10000, 40000),
(65, '20', 'Loại 1 ', 5000, 20000),
(66, '21', 'Loại 1 ', 5000, 15000),
(68, '23', 'set 1', 20000, 50000),
(69, '24', 'set 1', 20000, 50000),
(71, '14', 'Loại 1 ', 5000, 20000),
(73, '15', 'Loại 1 ', 10000, 40000),
(74, '18', 'Loại 1 ', 15000, 45000),
(75, '19', 'Loại 1 ', 15000, 45000),
(76, '16', 'Loại 1 ', 5000, 20000),
(77, '17', 'Loại 1 ', 20000, 50000),
(79, '25', 'Loại 1 ', 10000, 40000),
(80, '26', '13x6cm', 150000, 300000),
(81, '26', '17x7.5cm', 200000, 400000),
(82, '26', '21x7.5cm', 250000, 500000),
(83, '27', '13x6cm', 100000, 200000),
(84, '27', '17x7.5cm', 150000, 300000),
(85, '27', '21x7.5cm', 200000, 400000),
(86, '27', '25x7.5cm', 250000, 500000),
(87, '28', '13x6cm', 100000, 200000),
(88, '28', '17x7.5cm', 150000, 300000),
(89, '28', '21x7.5cm', 200000, 400000),
(90, '29', '13x6cm', 100000, 250000),
(91, '29', '17x7.5cm', 150000, 300000),
(92, '29', '21x7.5cm', 200000, 350000),
(93, '30', '13x6cm', 100000, 200000),
(94, '30', '17x7.5cm', 150000, 300000),
(95, '30', '21x7.5cm', 200000, 350000),
(96, '31', '13x6cm', 100000, 200000),
(97, '31', '17x7.5cm', 150000, 300000),
(98, '31', '21x7.5cm', 200000, 400000),
(99, '32', '13x6cm', 100000, 250000),
(100, '32', '17x7.5cm', 150000, 300000),
(101, '32', '21x7.5cm', 200000, 350000),
(102, '33', '13x6cm', 120000, 300000),
(103, '33', '17x7.5cm', 200000, 400000),
(104, '33', '21x7.5cm', 250000, 450000),
(108, '34', '13x6cm', 100000, 250000),
(109, '34', '17x7.5cm', 150000, 300000),
(110, '34', '21x7.5cm', 200000, 350000),
(111, '35', '13x6cm', 120000, 250000),
(112, '35', '17x7.5cm', 200000, 300000),
(113, '35', '21x7.5cm', 250000, 400000),
(114, '36', '13x6cm', 100000, 250000),
(115, '36', '17x7.5cm', 150000, 300000),
(116, '36', '21x7.5cm', 200000, 400000),
(117, '37', '13x6cm', 100000, 240000),
(118, '37', '17x7.5cm', 120000, 300000),
(119, '37', '21x7.5cm', 200000, 400000),
(120, '38', '13x6cm', 150000, 300000),
(121, '38', '17x7.5cm', 200000, 400000),
(122, '38', '21x7.5cm', 250000, 500000),
(123, '38', '25x7.5cm', 300000, 600000),
(124, '40', '13x6cm', 100000, 200000),
(125, '40', '17x7.5cm', 150000, 250000),
(126, '40', '21x7.5cm', 200000, 300000),
(127, '41', '13x6cm', 120000, 250000),
(128, '41', '17x7.5cm', 200000, 300000),
(129, '41', '21x7.5cm', 250000, 400000),
(130, '39', '13x6cm', 120000, 200000),
(131, '39', '17x7.5cm', 200000, 300000),
(132, '39', '21x7.5cm', 250000, 400000),
(133, '42', 'Loại 1 ', 5000, 20000),
(134, '43', 'Loại 1 ', 5000, 20000),
(135, '44', 'Loại 1 ', 5000, 20000),
(136, '45', 'Loại 1 ', 5000, 30000),
(137, '46', 'Loại 1 ', 10000, 50000),
(138, '47', 'Loại 1 ', 20000, 45000),
(139, '48', 'Loại 1 ', 20000, 50000),
(140, '49', 'Loại 1 ', 20000, 50000),
(141, '50', 'Loại 1 ', 25000, 50000),
(142, '51', 'Loại 1 ', 25000, 55000),
(143, '52', '13x6cm', 100000, 200000),
(144, '52', '17x7.5cm', 150000, 300000),
(145, '52', '21x7.5cm', 200000, 400000),
(146, '53', '13x6cm', 100000, 250000),
(147, '53', '17x7.5cm', 150000, 300000),
(148, '53', '21x7.5cm', 200000, 400000),
(149, '54', '13x6cm', 150000, 300000),
(150, '54', '17x7.5cm', 200000, 400000),
(151, '54', '21x7.5cm', 300000, 500000),
(152, '55', '13x6cm', 120000, 200000),
(153, '55', '17x7.5cm', 200000, 300000),
(154, '55', '21x7.5cm', 300000, 400000),
(156, '57', 'set 1', 30000, 70000),
(157, '57', 'set2', 60000, 150000),
(158, '56', 'set 1', 30000, 70000),
(159, '56', 'set 2 ', 70000, 150000),
(160, '58', 'set 1', 25000, 60000),
(161, '58', 'set 2 ', 50000, 130000),
(162, '58', 'set 3 ', 80000, 200000),
(166, '59', 'set 1', 50000, 100000),
(167, '59', 'set 2 ', 75000, 150000),
(168, '59', 'set 3 ', 100000, 200000),
(169, '60', 'set 1', 30000, 100000),
(170, '60', 'set 2 ', 60000, 170000),
(171, '60', 'set 3 ', 90000, 250000),
(172, '61', 'set 1', 50000, 120000),
(173, '61', 'set 2 ', 75000, 200000),
(174, '61', 'set 3 ', 120000, 250000),
(175, '63', 'set 1', 50000, 120000),
(176, '63', 'set 2 ', 80000, 200000),
(177, '63', 'set 3 ', 140000, 300000),
(178, '62', 'set 1', 50000, 120000),
(179, '62', 'set 2 ', 80000, 200000),
(180, '62', 'set 3 ', 140000, 300000),
(181, '64', 'set 1', 30000, 75000),
(182, '64', 'set 2 ', 70000, 120000),
(183, '64', 'set 3 ', 120000, 200000),
(184, '65', 'set 1', 35000, 75000),
(185, '65', 'set 2 ', 75000, 140000),
(186, '65', 'set 3 ', 120000, 200000),
(187, '66', '13x6cm', 50000, 120000),
(188, '66', '17x7.5cm', 120000, 200000),
(189, '66', '21x7.5cm', 160000, 280000),
(190, '67', 'set 1', 45000, 90000),
(191, '67', 'set 2 ', 75000, 150000),
(192, '67', 'set 3 ', 12000, 220000),
(193, '68', '13x6cm', 45000, 90000),
(194, '68', '17x7.5cm', 75000, 140000),
(195, '68', '21x7.5cm', 125000, 200000),
(196, '69', '13x6cm', 50000, 120000),
(197, '69', '17x7.5cm', 75000, 150000),
(198, '69', '21x7.5cm', 125000, 220000),
(199, '70', 'set 1', 50000, 90000),
(200, '70', 'set 2 ', 75000, 125000),
(201, '70', 'set 3 ', 125000, 210000),
(202, '71', '13x6cm', 80000, 140000),
(203, '71', '17x7.5cm', 120000, 210000),
(204, '71', '21x7.5cm', 160000, 260000),
(205, '73', 'set 1', 50000, 120000),
(206, '73', 'set 2 ', 80000, 170000),
(207, '73', 'set 3 ', 120000, 220000),
(208, '72', 'set 1', 45000, 90000),
(209, '72', 'set 2 ', 75000, 120000),
(210, '72', 'set 3 ', 140000, 200000),
(211, '74', 'set 1', 25000, 55000),
(212, '74', 'set 2 ', 50000, 100000),
(213, '75', 'set 1', 30000, 65000),
(214, '75', 'set 2 ', 60000, 130000),
(215, '76', 'set 1 ', 50000, 100000),
(216, '76', 'set 2 ', 80000, 180000),
(217, '22', 'set 1', 20000, 50000),
(234, '77', 'set 1', 400000, 850000),
(235, '77', 'set 2', 300000, 750000),
(236, '77', 'set 3', 200000, 650000),
(237, '77', 'set 4', 150000, 550000),
(238, '78', 'set 1', 550000, 850000),
(239, '78', 'set 2 ', 450000, 750000),
(240, '78', 'set 3 ', 350000, 650000),
(241, '78', 'set 4 ', 250000, 550000),
(242, '79', 'set 1', 650000, 950000),
(243, '79', 'set 2', 550000, 850000),
(244, '79', 'set 3', 450000, 750000),
(245, '80', 'set 1', 600000, 850000),
(246, '80', 'set 2', 500000, 750000),
(247, '80', 'set 3 ', 400000, 650000),
(248, '80', 'set 4', 350000, 550000),
(249, '81', 'set 1', 900000, 1500000),
(250, '81', 'set 2 ', 700000, 1300000),
(251, '81', 'set 3 ', 600000, 1000000),
(252, '82', 'set 1', 950000, 1600000),
(253, '82', 'set 2', 800000, 1200000),
(254, '82', 'set 3', 650000, 1000000),
(255, '83', 'set 1', 500000, 1000000),
(256, '84', 'set 1', 900000, 1500000);

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `order_details`
--
ALTER TABLE `order_details`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `promotions`
--
ALTER TABLE `promotions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `code` (`code`);

--
-- Chỉ mục cho bảng `promotion_code`
--
ALTER TABLE `promotion_code`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `code` (`code`);

--
-- Chỉ mục cho bảng `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `tblloaisp`
--
ALTER TABLE `tblloaisp`
  ADD PRIMARY KEY (`maLoaiSP`);

--
-- Chỉ mục cho bảng `tblsanpham`
--
ALTER TABLE `tblsanpham`
  ADD PRIMARY KEY (`masp`);

--
-- Chỉ mục cho bảng `tbluser`
--
ALTER TABLE `tbluser`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Chỉ mục cho bảng `tbl_sanpham_size`
--
ALTER TABLE `tbl_sanpham_size`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_tbl_sanpham_size_masp` (`masp`);

--
-- AUTO_INCREMENT cho các bảng đã đổ
--

--
-- AUTO_INCREMENT cho bảng `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT cho bảng `order_details`
--
ALTER TABLE `order_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=309;

--
-- AUTO_INCREMENT cho bảng `promotions`
--
ALTER TABLE `promotions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT cho bảng `promotion_code`
--
ALTER TABLE `promotion_code`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT cho bảng `reviews`
--
ALTER TABLE `reviews`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT cho bảng `tbluser`
--
ALTER TABLE `tbluser`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT cho bảng `tbl_sanpham_size`
--
ALTER TABLE `tbl_sanpham_size`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=257;

--
-- Các ràng buộc cho các bảng đã đổ
--

--
-- Các ràng buộc cho bảng `tbl_sanpham_size`
--
ALTER TABLE `tbl_sanpham_size`
  ADD CONSTRAINT `fk_sanpham_size` FOREIGN KEY (`masp`) REFERENCES `tblsanpham` (`masp`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_tbl_sanpham_size_masp` FOREIGN KEY (`masp`) REFERENCES `tblsanpham` (`masp`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

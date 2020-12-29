-- phpMyAdmin SQL Dump
-- version 4.6.4
-- https://www.phpmyadmin.net/
--
-- Máy chủ: 127.0.0.1
-- Thời gian đã tạo: Th12 29, 2020 lúc 10:04 SA
-- Phiên bản máy phục vụ: 5.7.14
-- Phiên bản PHP: 7.0.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Cơ sở dữ liệu: `deviation_plus`
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `arc`
--

CREATE TABLE `arc` (
  `id` int(11) NOT NULL,
  `point_begin_id` int(11) NOT NULL,
  `point_end_id` int(11) NOT NULL,
  `vehicle_id` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `arc`
--

INSERT INTO `arc` (`id`, `point_begin_id`, `point_end_id`, `vehicle_id`) VALUES
(1, 14, 16, 0),
(2, 17, 19, 0),
(3, 20, 22, 0),
(4, 23, 25, 0),
(5, 26, 28, 0),
(6, 29, 31, 0);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `arc_point`
--

CREATE TABLE `arc_point` (
  `id` int(11) NOT NULL,
  `arc_id` int(11) NOT NULL,
  `point_id` int(11) NOT NULL,
  `sequence` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `arc_point`
--

INSERT INTO `arc_point` (`id`, `arc_id`, `point_id`, `sequence`) VALUES
(1, 6, 30, 1);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `gps_point`
--

CREATE TABLE `gps_point` (
  `id` int(11) NOT NULL,
  `timestamp` timestamp NOT NULL,
  `deviation` float NOT NULL,
  `point_id` int(11) NOT NULL,
  `vehicle_id` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `point`
--

CREATE TABLE `point` (
  `id` int(11) NOT NULL,
  `longitude` int(11) NOT NULL,
  `latitude` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `point`
--

INSERT INTO `point` (`id`, `longitude`, `latitude`) VALUES
(10, 5, 6),
(9, 3, 4),
(8, 1, 2),
(11, 1, 2),
(12, 3, 4),
(13, 5, 6),
(14, 1, 2),
(15, 3, 4),
(16, 5, 6),
(17, 1, 2),
(18, 3, 4),
(19, 5, 6),
(20, 1, 2),
(21, 3, 4),
(22, 5, 6),
(23, 1, 2),
(24, 3, 4),
(25, 5, 6),
(26, 1, 2),
(27, 3, 4),
(28, 5, 6),
(29, 1, 2),
(30, 3, 4),
(31, 5, 6);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `vehicle`
--

CREATE TABLE `vehicle` (
  `id` int(11) NOT NULL,
  `registration_plate` text COLLATE utf8_unicode_ci NOT NULL,
  `color` text COLLATE utf8_unicode_ci NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `vehicle`
--

INSERT INTO `vehicle` (`id`, `registration_plate`, `color`) VALUES
(1, 'gdrgre', '#0040ff');

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `arc`
--
ALTER TABLE `arc`
  ADD PRIMARY KEY (`id`),
  ADD KEY `point_begin_id` (`point_begin_id`),
  ADD KEY `point_end_id` (`point_end_id`),
  ADD KEY `vehicle_id` (`vehicle_id`);

--
-- Chỉ mục cho bảng `arc_point`
--
ALTER TABLE `arc_point`
  ADD PRIMARY KEY (`id`),
  ADD KEY `point_id` (`point_id`),
  ADD KEY `arc_id` (`arc_id`);

--
-- Chỉ mục cho bảng `gps_point`
--
ALTER TABLE `gps_point`
  ADD PRIMARY KEY (`id`),
  ADD KEY `point_id` (`point_id`),
  ADD KEY `vehicle_id` (`vehicle_id`);

--
-- Chỉ mục cho bảng `point`
--
ALTER TABLE `point`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `vehicle`
--
ALTER TABLE `vehicle`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT cho các bảng đã đổ
--

--
-- AUTO_INCREMENT cho bảng `arc`
--
ALTER TABLE `arc`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT cho bảng `arc_point`
--
ALTER TABLE `arc_point`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT cho bảng `gps_point`
--
ALTER TABLE `gps_point`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT cho bảng `point`
--
ALTER TABLE `point`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;
--
-- AUTO_INCREMENT cho bảng `vehicle`
--
ALTER TABLE `vehicle`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

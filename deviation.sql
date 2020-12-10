-- phpMyAdmin SQL Dump
-- version 4.6.4
-- https://www.phpmyadmin.net/
--
-- Máy chủ: 127.0.0.1
-- Thời gian đã tạo: Th12 10, 2020 lúc 02:50 CH
-- Phiên bản máy phục vụ: 5.7.14
-- Phiên bản PHP: 7.0.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Cơ sở dữ liệu: `deviation`
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `arc`
--

CREATE TABLE `arc` (
  `id` int(11) NOT NULL,
  `node_begin_id` int(11) DEFAULT NULL,
  `node_end_id` int(11) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Đang đổ dữ liệu cho bảng `arc`
--

INSERT INTO `arc` (`id`, `node_begin_id`, `node_end_id`) VALUES
(1, 111, 222),
(2, 111, 222);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `arc_point`
--

CREATE TABLE `arc_point` (
  `Id` int(11) NOT NULL,
  `Arc_id` int(11) DEFAULT NULL,
  `Point_id` int(11) DEFAULT NULL,
  `Sequence` int(11) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `deviation`
--

CREATE TABLE `deviation` (
  `id` int(11) NOT NULL,
  `gps_point_id` int(11) DEFAULT NULL,
  `VALUE` float DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `gps_point`
--

CREATE TABLE `gps_point` (
  `id` int(11) NOT NULL,
  `point_id` int(11) DEFAULT NULL,
  `vehicle_id` int(11) DEFAULT NULL,
  `TIMESTAMP` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `node`
--

CREATE TABLE `node` (
  `Id` int(11) NOT NULL,
  `point_id` int(11) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `node_1`
--

CREATE TABLE `node_1` (
  `id` int(11) NOT NULL,
  `point_id` int(11) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `point`
--

CREATE TABLE `point` (
  `id` int(11) NOT NULL,
  `Longtitude` float DEFAULT NULL,
  `latitude` float DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `vehicle`
--

CREATE TABLE `vehicle` (
  `id` int(11) NOT NULL,
  `registration_plate` char(10) DEFAULT NULL,
  `color` char(10) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `arc`
--
ALTER TABLE `arc`
  ADD PRIMARY KEY (`id`),
  ADD KEY `node_begin_id` (`node_begin_id`),
  ADD KEY `node_end_id` (`node_end_id`);

--
-- Chỉ mục cho bảng `arc_point`
--
ALTER TABLE `arc_point`
  ADD PRIMARY KEY (`Id`),
  ADD KEY `Arc_id` (`Arc_id`),
  ADD KEY `Point_id` (`Point_id`);

--
-- Chỉ mục cho bảng `deviation`
--
ALTER TABLE `deviation`
  ADD PRIMARY KEY (`id`),
  ADD KEY `gps_point_id` (`gps_point_id`);

--
-- Chỉ mục cho bảng `gps_point`
--
ALTER TABLE `gps_point`
  ADD PRIMARY KEY (`id`),
  ADD KEY `point_id` (`point_id`),
  ADD KEY `vehicle_id` (`vehicle_id`);

--
-- Chỉ mục cho bảng `node`
--
ALTER TABLE `node`
  ADD PRIMARY KEY (`Id`);

--
-- Chỉ mục cho bảng `node_1`
--
ALTER TABLE `node_1`
  ADD PRIMARY KEY (`id`);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT cho bảng `arc_point`
--
ALTER TABLE `arc_point`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT cho bảng `deviation`
--
ALTER TABLE `deviation`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT cho bảng `gps_point`
--
ALTER TABLE `gps_point`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT cho bảng `node`
--
ALTER TABLE `node`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT cho bảng `node_1`
--
ALTER TABLE `node_1`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT cho bảng `point`
--
ALTER TABLE `point`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT cho bảng `vehicle`
--
ALTER TABLE `vehicle`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

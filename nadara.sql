-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: 15 مايو 2026 الساعة 00:23
-- إصدار الخادم: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `nadara`
--

-- --------------------------------------------------------

--
-- بنية الجدول `admins`
--

CREATE TABLE `admins` (
  `admin_id` int(11) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- إرجاع أو استيراد بيانات الجدول `admins`
--

INSERT INTO `admins` (`admin_id`, `full_name`, `email`, `password`) VALUES
(101, 'Raghad Aldossary', 'admin@nadara.com', 'admin123'),
(102, 'Shaden Alghamdi', 'shaden@nadara.com', 'nadara2026');

-- --------------------------------------------------------

--
-- بنية الجدول `products`
--

CREATE TABLE `products` (
  `product_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `image` varchar(255) NOT NULL,
  `stock` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `description` text DEFAULT NULL,
  `category` varchar(100) DEFAULT NULL,
  `size` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- إرجاع أو استيراد بيانات الجدول `products`
--

INSERT INTO `products` (`product_id`, `name`, `image`, `stock`, `price`, `description`, `category`, `size`) VALUES
(1, 'Hydrating Cleanser', 'hydrating_cleanser.jpg', 27, 18.99, 'Daily cleanser for soft and refreshed skin.', 'Cleanser', '100ml'),
(2, 'Glow Serum', 'glow_serum.jpg', 35, 24.99, 'Brightening serum for radiant and healthy skin.', 'Toner', '50ml'),
(3, 'Moisturizing Cream', 'moisturizing_cream.jpg', 20, 22.99, 'Rich moisturizing cream for daily hydration.', 'Cream', '50ml'),
(4, 'Daily Sunscreen SPF 50', 'daily_sunscreen_spf50.jpg', 13, 19.99, 'Lightweight sunscreen for daily protection.', 'Serum', '50ml'),
(5, 'Soft Balance Toner', 'soft_balance_toner.jpg', 20, 16.99, 'Gentle toner suitable for sensitive skin.', 'Sunscreen', '120ml'),
(6, 'Aloe Repair Mask', 'aloe_repair_mask.jpg', 10, 21.50, 'Calming mask that refreshes tired skin.', 'Mask', '75ml');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`admin_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`product_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `product_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

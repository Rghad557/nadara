-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: 10 أبريل 2026 الساعة 14:33
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
-- بنية الجدول `categories`
--

CREATE TABLE `categories` (
  `category_id` int(11) NOT NULL,
  `category_name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- إرجاع أو استيراد بيانات الجدول `categories`
--

INSERT INTO `categories` (`category_id`, `category_name`) VALUES
(1, 'Cleanser'),
(2, 'Toner'),
(3, 'Serum'),
(4, 'Moisturizer'),
(5, 'Sunscreen'),
(6, 'Mask');

-- --------------------------------------------------------

--
-- بنية الجدول `concerns`
--

CREATE TABLE `concerns` (
  `concern_id` int(11) NOT NULL,
  `concern_name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- إرجاع أو استيراد بيانات الجدول `concerns`
--

INSERT INTO `concerns` (`concern_id`, `concern_name`) VALUES
(1, 'Brightening'),
(2, 'Hydrating'),
(3, 'Acne'),
(4, 'Redness'),
(5, 'Anti-aging'),
(6, 'Dark Spots');

-- --------------------------------------------------------

--
-- بنية الجدول `orders`
--

CREATE TABLE `orders` (
  `order_id` int(11) NOT NULL,
  `customer_name` varchar(100) NOT NULL,
  `order_date` datetime DEFAULT current_timestamp(),
  `total_amount` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- إرجاع أو استيراد بيانات الجدول `orders`
--

INSERT INTO `orders` (`order_id`, `customer_name`, `order_date`, `total_amount`) VALUES
(1, 'Sarah Ahmed', '2026-04-10 15:16:29', 67.97),
(2, 'Mona Khalid', '2026-04-10 15:16:29', 42.49);

-- --------------------------------------------------------

--
-- بنية الجدول `order_items`
--

CREATE TABLE `order_items` (
  `order_item_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `unit_price` decimal(10,2) NOT NULL,
  `item_total` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- إرجاع أو استيراد بيانات الجدول `order_items`
--

INSERT INTO `order_items` (`order_item_id`, `order_id`, `product_id`, `quantity`, `unit_price`, `item_total`) VALUES
(1, 1, 2, 1, 24.99, 24.99),
(2, 1, 3, 1, 22.99, 22.99),
(3, 1, 4, 1, 19.99, 19.99),
(4, 2, 1, 1, 18.99, 18.99),
(5, 2, 5, 1, 16.99, 16.99),
(6, 2, 6, 1, 21.50, 21.50);

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
  `size` varchar(50) DEFAULT NULL,
  `category_id` int(11) DEFAULT NULL,
  `skin_type_id` int(11) DEFAULT NULL,
  `concern_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- إرجاع أو استيراد بيانات الجدول `products`
--

INSERT INTO `products` (`product_id`, `name`, `image`, `stock`, `price`, `description`, `size`, `category_id`, `skin_type_id`, `concern_id`) VALUES
(1, 'Hydrating Cleanser', 'hydrating_cleanser.jpg', 30, 18.99, 'Daily cleanser for soft and refreshed skin.', '100ml', 1, 5, 2),
(2, 'Glow Serum', 'glow_serum.jpg', 35, 24.99, 'Brightening serum for radiant and healthy skin.', '50ml', 3, 5, 1),
(3, 'Moisturizing Cream', 'moisturizing_cream.jpg', 10, 22.99, 'Rich moisturizing cream for daily hydration.', '50ml', 4, 2, 2),
(4, 'Daily Sunscreen SPF 50', 'daily_sunscreen_spf50.jpg', 15, 19.99, 'Lightweight sunscreen for daily protection.', '50ml', 5, 5, 6),
(5, 'Soft Balance Toner', 'soft_balance_toner.jpg', 20, 16.99, 'Gentle toner suitable for sensitive skin.', '120ml', 2, 4, 4),
(6, 'Aloe Repair Mask', 'aloe_repair_mask.jpg', 12, 21.50, 'Calming mask that refreshes tired skin.', '75ml', 6, 4, 4);

-- --------------------------------------------------------

--
-- بنية الجدول `skin_types`
--

CREATE TABLE `skin_types` (
  `skin_type_id` int(11) NOT NULL,
  `skin_type_name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- إرجاع أو استيراد بيانات الجدول `skin_types`
--

INSERT INTO `skin_types` (`skin_type_id`, `skin_type_name`) VALUES
(1, 'Oily'),
(2, 'Dry'),
(3, 'Combination'),
(4, 'Sensitive'),
(5, 'Normal');

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
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`category_id`);

--
-- Indexes for table `concerns`
--
ALTER TABLE `concerns`
  ADD PRIMARY KEY (`concern_id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`order_id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`order_item_id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`product_id`),
  ADD KEY `category_id` (`category_id`),
  ADD KEY `skin_type_id` (`skin_type_id`),
  ADD KEY `concern_id` (`concern_id`);

--
-- Indexes for table `skin_types`
--
ALTER TABLE `skin_types`
  ADD PRIMARY KEY (`skin_type_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `category_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `concerns`
--
ALTER TABLE `concerns`
  MODIFY `concern_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `order_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `order_item_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `product_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `skin_types`
--
ALTER TABLE `skin_types`
  MODIFY `skin_type_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- قيود الجداول المُلقاة.
--

--
-- قيود الجداول `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`),
  ADD CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`);

--
-- قيود الجداول `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`category_id`),
  ADD CONSTRAINT `products_ibfk_2` FOREIGN KEY (`skin_type_id`) REFERENCES `skin_types` (`skin_type_id`),
  ADD CONSTRAINT `products_ibfk_3` FOREIGN KEY (`concern_id`) REFERENCES `concerns` (`concern_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

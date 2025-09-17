-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 16, 2025 at 09:52 AM
-- Server version: 8.0.41
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `liora_store`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `admin_id` int NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(500) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`admin_id`, `username`, `password`, `created_at`) VALUES
(1, 'admin', '$2y$10$sGYoqgyqwAKd0oVoBWewAedShveigEUAcFGnVNicm2OP/ASkQugMK', '2025-09-16 07:33:49');

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `cart_id` int NOT NULL,
  `user_id` int DEFAULT NULL,
  `session_id` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cart_items`
--

CREATE TABLE `cart_items` (
  `cart_item_id` int NOT NULL,
  `cart_id` int DEFAULT NULL,
  `product_id` int DEFAULT NULL,
  `quantity` int NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `order_id` int NOT NULL,
  `user_id` int DEFAULT NULL,
  `total_amount` decimal(10,2) NOT NULL,
  `status` enum('pending','processing','shipped','delivered') DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `order_item_id` int NOT NULL,
  `order_id` int DEFAULT NULL,
  `product_id` int DEFAULT NULL,
  `quantity` int NOT NULL,
  `price_at_time` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `product_id` int NOT NULL,
  `category` enum('mens','womens','kids','best_sellers') NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text,
  `price` decimal(10,2) NOT NULL,
  `image_path` varchar(255) NOT NULL,
  `stock` int NOT NULL DEFAULT '0',
  `is_new_arrival` tinyint(1) DEFAULT '0',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`product_id`, `category`, `name`, `description`, `price`, `image_path`, `stock`, `is_new_arrival`, `created_at`) VALUES
(1, 'mens', 'Classic Denim Jeans', 'Comfortable straight-fit denim jeans', 79.99, 'uploads/1758008548_1757962937_Classic Denim Jeans.png', 50, 1, '2025-09-16 07:34:03'),
(2, 'mens', 'Cotton T-Shirt', 'Premium cotton crew neck t-shirt', 24.99, 'uploads/1758008537_TOPS_INTRUDEREKCESSSSTEECHARCOAL_797_700x.webp', 100, 0, '2025-09-16 07:34:03'),
(4, 'mens', 'Sports Jacket', 'Lightweight athletic jacket', 89.99, 'uploads/1758008624_Harvest-Gold-Sport-Jacket-Regular-Fit-front-A88240001_900x1125_crop_center.png', 25, 1, '2025-09-16 07:34:03'),
(5, 'mens', 'Cargo Pants', 'Multi-pocket cargo pants', 59.99, 'uploads/cargo-boy.png', 40, 0, '2025-09-16 07:34:03'),
(6, 'womens', 'Floral Summer Dress', 'Light and breezy floral print dress', 69.99, 'uploads/1758008241_Floral Dress.jpg', 35, 1, '2025-09-16 07:34:03'),
(7, 'womens', 'Chiffon Blouse', 'Elegant chiffon top for any occasion', 54.99, 'uploads/chiffon top.jpg', 45, 0, '2025-09-16 07:34:03'),
(8, 'womens', 'Blue Skinny Leg Jeans', 'Trendy high-waisted wide leg jeans', 79.99, 'uploads/1758008302_jeans.jpg', 30, 1, '2025-09-16 07:34:03'),
(9, 'womens', 'Cotton T-Shirt', 'Soft cotton basic tee', 29.99, 'uploads/1758008470_pink-cotton-poplin-hi-low-casual-long-shirt.jpg.webp', 80, 0, '2025-09-16 07:34:03'),
(11, 'kids', 'Superhero T-Shirt', 'Fun superhero themed t-shirt', 19.99, 'uploads/superhero-boy.png', 60, 1, '2025-09-16 07:34:03'),
(12, 'kids', 'Princess Dress', 'Beautiful princess costume dress', 39.99, 'uploads/princess-girl.png', 40, 0, '2025-09-16 07:34:03'),
(13, 'kids', 'Cargo Shorts', 'Comfortable cargo shorts for boys', 24.99, 'uploads/cargo-boy.png', 50, 0, '2025-09-16 07:34:03'),
(14, 'kids', 'Floral Dress', 'Pretty floral print dress for girls', 34.99, 'uploads/Floral-girls.png', 35, 1, '2025-09-16 07:34:03'),
(15, 'kids', 'Baby Onesie', 'Soft cotton onesie for babies', 14.99, 'uploads/onesie-baby.png', 70, 0, '2025-09-16 07:34:03'),
(16, 'best_sellers', 'Sparkly Princess Dress', 'Most popular princess dress', 44.99, 'uploads/1758008647_tutu-girl.png', 20, 0, '2025-09-16 07:34:03'),
(17, 'best_sellers', 'Classic Denim Jeans', 'Our bestselling jeans', 79.99, 'uploads/Classic Denim Jeans.png', 30, 0, '2025-09-16 07:34:03'),
(18, 'kids', 'Girl\'s Jacket', 'Comfy & Stylish Jacket ', 15.00, 'uploads/1758009073_djaket-girl.png', 25, 1, '2025-09-16 07:51:13');

-- --------------------------------------------------------

--
-- Table structure for table `product_searches`
--

CREATE TABLE `product_searches` (
  `search_id` int NOT NULL,
  `product_id` int DEFAULT NULL,
  `search_count` int DEFAULT '1',
  `last_searched` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`admin_id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`cart_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `cart_items`
--
ALTER TABLE `cart_items`
  ADD PRIMARY KEY (`cart_item_id`),
  ADD KEY `cart_id` (`cart_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`order_id`),
  ADD KEY `user_id` (`user_id`);

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
  ADD KEY `idx_product_category` (`category`);

--
-- Indexes for table `product_searches`
--
ALTER TABLE `product_searches`
  ADD PRIMARY KEY (`search_id`),
  ADD KEY `product_id` (`product_id`),
  ADD KEY `idx_search_count` (`search_count`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `admin_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `cart_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `cart_items`
--
ALTER TABLE `cart_items`
  MODIFY `cart_item_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `order_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `order_item_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `product_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `product_searches`
--
ALTER TABLE `product_searches`
  MODIFY `search_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `cart`
--
ALTER TABLE `cart`
  ADD CONSTRAINT `cart_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `cart_items`
--
ALTER TABLE `cart_items`
  ADD CONSTRAINT `cart_items_ibfk_1` FOREIGN KEY (`cart_id`) REFERENCES `cart` (`cart_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `cart_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`) ON DELETE CASCADE;

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE SET NULL;

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`) ON DELETE SET NULL;

--
-- Constraints for table `product_searches`
--
ALTER TABLE `product_searches`
  ADD CONSTRAINT `product_searches_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Nov 12, 2025 at 10:50 AM
-- Server version: 8.0.43
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `dragonstonedb`
--

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `product_id` int NOT NULL,
  `quantity` int DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int NOT NULL,
  `name` varchar(100) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `co2_saved_per_item` int DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `image`, `co2_saved_per_item`, `created_at`) VALUES
(1, 'Cleaning & Household Supplies', 'cleaning.jpg', 120, '2025-10-21 15:49:09'),
(2, 'Kitchen & Dining', 'Housing Kitchens.jpg', 80, '2025-10-21 15:49:09'),
(3, 'Home Décor & Living', 'Discover.jpg', 100, '2025-10-21 15:49:09'),
(4, 'Bathroom & Personal Care', 'bathroom.jpg', 90, '2025-10-21 15:49:09'),
(5, 'Lifestyle & Wellness', 'lifestyle.jpg', 110, '2025-10-21 15:49:09'),
(6, 'Kids & Pets', 'kid.jpg', 70, '2025-10-21 15:49:09'),
(7, 'Outdoor & Garden', 'Outdoor.jpg', 150, '2025-11-11 19:40:23');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `total` decimal(10,2) NOT NULL,
  `status` varchar(50) DEFAULT 'Pending',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `total`, `status`, `created_at`) VALUES
(1, 2, 1219.98, 'Pending', '2025-10-21 15:49:09'),
(2, 3, 69.98, 'Delivered', '2025-10-21 15:49:09'),
(3, 2, 2439.96, 'pending', '2025-10-21 15:51:39'),
(4, 1, 1599.98, 'pending', '2025-10-28 01:22:39'),
(5, 4, 29.99, 'pending', '2025-10-28 22:00:10'),
(6, 4, 999.99, 'pending', '2025-10-28 23:09:39'),
(7, 4, 40.00, 'pending', '2025-10-28 23:16:42'),
(8, 4, 59.99, 'pending', '2025-10-28 23:21:53');

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `id` int NOT NULL,
  `order_id` int NOT NULL,
  `product_id` int NOT NULL,
  `quantity` int NOT NULL,
  `price` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int NOT NULL,
  `category_id` int NOT NULL,
  `name` varchar(150) NOT NULL,
  `description` text,
  `price` decimal(10,2) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `category_id`, `name`, `description`, `price`, `image`, `created_at`) VALUES
(1, 1, 'Bingo', 'cleaning', 67.00, 'Bingo.jpg', '2025-11-11 22:16:50'),
(2, 2, 'Beeswax food', 'kitchen', 67.00, 'Beeswaxfood.webp', '2025-11-12 06:50:06'),
(4, 4, 'Toothpaste', 'Bathroom', 12.99, 'Toothpaste.jpg', '2025-11-11 21:28:44'),
(5, 5, 'Aluminum bottle', 'lifestyle', 200.00, 'Aluminium-bottle.webp', '2025-11-12 07:52:28'),
(6, 6, 'Baby clearance', 'kids', 45.00, 'Baby-clearance.webp', '2025-11-12 06:19:38'),
(7, 7, 'Acapulco pot', 'outdoor', 567.00, 'Acapulco-pot.webp', '2025-11-12 08:56:39'),
(30, 4, 'toothbrush', 'bathroom', 10.00, 'toothbrush.jpg', '2025-11-11 21:42:52'),
(32, 4, 'Vaseline', 'personal care', 78.00, 'Vaseline.jpg', '2025-11-11 21:44:00'),
(34, 4, 'Vaseline jelly', 'personal care ', 48.99, 'Jelly.jpg', '2025-11-11 21:45:08'),
(36, 4, 'Jelly Vaseline', 'personal care', 38.99, 'jelly-Vaseline.jpg', '2025-11-11 21:46:27'),
(38, 4, 'Glove', 'bathroom', 24.00, 'Glove.jpg', '2025-11-11 21:47:22'),
(40, 4, 'Floss', 'bathroom', 120.00, 'Floss-picks.jpg', '2025-11-11 21:48:20'),
(42, 4, 'Dove', 'bathroom', 38.99, 'Dove.jpg', '2025-11-11 21:50:43'),
(43, 4, 'Cerave', 'personal care', 200.00, 'Cerave.jpg', '2025-11-11 21:53:19'),
(45, 1, 'Clorox bleach', 'cleaning', 67.00, 'Clorox-Bleach.jpg', '2025-11-11 22:17:45'),
(46, 1, 'Drain', 'cleaning', 124.00, 'Drain-clog.jpg', '2025-11-11 22:18:32'),
(47, 1, 'Glass cleans', 'cleaning', 59.00, 'Glass-clean.jpg', '2025-11-11 22:20:12'),
(48, 1, 'Couch clean', 'cleaning', 250.00, 'Couchclean.jpg', '2025-11-11 22:22:02'),
(49, 1, 'Tissue paper', 'supplies', 130.00, 'Tissues.jpg', '2025-11-11 22:25:49'),
(50, 1, 'Produto', 'cleaning', 45.00, 'Produto.jpg', '2025-11-11 22:27:16'),
(51, 1, 'Purex', 'Cleaning', 67.99, 'Purex.jpg', '2025-11-11 22:27:59'),
(52, 1, 'Domestos', 'cleaning', 35.00, 'Domestos.jpg', '2025-11-12 04:42:01'),
(53, 1, 'Pine gel', 'cleaning', 45.99, 'Pine-gel.jpg', '2025-11-12 04:43:35'),
(54, 1, 'Broom', 'cleaning', 83.00, 'Broom.webp', '2025-11-12 04:52:01'),
(55, 1, 'Handy andy', 'cleaning', 35.00, 'Handy.webp', '2025-11-12 04:52:47'),
(56, 1, 'Jik', 'cleaning', 35.00, 'jik.webp', '2025-11-12 04:53:28'),
(57, 1, 'Omo', 'cleaning', 45.00, 'Omo.webp', '2025-11-12 04:54:51'),
(58, 3, 'Home decor', 'Home', 1500.00, 'home-decor.avif', '2025-11-12 05:02:46'),
(59, 3, 'Decor', 'home', 600.00, 'decor (3).jpg', '2025-11-12 05:03:48'),
(60, 3, 'Ceramic-vase', 'heme decor', 850.00, 'Ceramic-vase.webp', '2025-11-12 05:04:30'),
(61, 3, 'Ceramic balls', 'home decor', 1590.00, 'Ceramic-balls.webp', '2025-11-12 05:09:27'),
(62, 3, 'Ornaments for living', 'Home decor', 1200.00, 'Hornement.webp', '2025-11-12 05:11:17'),
(63, 3, 'Horn sculpture', 'Home decor', 2500.00, 'Horn-sculpture.webp', '2025-11-12 05:13:01'),
(64, 3, 'Living decor', 'home decor', 650.99, 'homedeco.jpg', '2025-11-12 05:13:55'),
(65, 3, 'Mirror', 'home decor', 350.00, 'Mirror.jpg', '2025-11-12 05:14:37'),
(66, 3, 'Room plant', 'home decor', 670.00, 'Room-plant.webp', '2025-11-12 05:15:30'),
(67, 3, 'Wood frame', 'home decor', 68.00, 'Wood-frame.jpg', '2025-11-12 05:16:21'),
(68, 3, 'Wall decor', 'home decor', 349.99, 'Wall-decor.jpg', '2025-11-12 05:17:06'),
(71, 3, 'Jars glass', 'home decor', 78.99, 'Decor.jpg', '2025-11-12 05:59:05'),
(72, 3, 'Home light ', 'home decor', 2300.00, 'home-ligth.avif', '2025-11-12 06:01:06'),
(73, 6, 'Baby nest', 'kids', 545.00, 'Baby-net.webp', '2025-11-12 06:20:32'),
(74, 6, 'Baby cotton', 'kids', 34.00, 'Cotton-kids.webp', '2025-11-12 06:21:10'),
(75, 6, 'Fisher toy', 'kids', 400.00, 'Fisher-toy.webp', '2025-11-12 06:21:52'),
(76, 6, 'Giraffe toy', 'kids', 78.99, 'Giraffe.webp', '2025-11-12 06:22:36'),
(77, 6, 'Long sleeve', 'kids', 159.00, 'Long-sleeve.webp', '2025-11-12 06:23:54'),
(78, 6, 'Montessori', 'kids', 178.00, 'Montessori.webp', '2025-11-12 06:24:33'),
(79, 6, 'Newborn', 'kids', 239.00, 'Newborn.webp', '2025-11-12 06:25:42'),
(80, 6, 'Reusable pack', 'kids', 350.00, 'Pack-flower.webp', '2025-11-12 06:26:37'),
(81, 6, 'Pet shampoo', 'pet', 190.99, 'Pet-shampoo.webp', '2025-11-12 06:27:19'),
(82, 6, 'Pet spa', 'pet', 56.00, 'Pet-spa.webp', '2025-11-12 06:27:54'),
(83, 6, 'Pikler', 'kids', 450.00, 'Pikler.webp', '2025-11-12 06:28:31'),
(84, 6, 'Rabbit', 'kids', 89.99, 'Rabbit.webp', '2025-11-12 06:29:10'),
(85, 6, 'Reusable baby', 'kids', 140.00, 'Reusable-baby.webp', '2025-11-12 06:29:57'),
(86, 6, 'Ring stacker', 'kids', 250.00, 'Ring-stacker.webp', '2025-11-12 06:30:41'),
(87, 6, 'Plastic', 'pet', 56.00, 'Plastic.jpg', '2025-11-12 06:31:33'),
(88, 6, 'Trailer toy', 'kids', 239.00, 'Trailer-toy.webp', '2025-11-12 06:32:33'),
(89, 6, 'Wooden car', 'kids', 189.00, 'wooden-car.webp', '2025-11-12 06:33:14'),
(90, 6, 'Wood toy', 'kids', 45.00, 'Wood-toy.jpg', '2025-11-12 06:33:51'),
(91, 2, 'Beeswax wrap', 'kitchen', 56.00, 'Beeswaxwrap.webp', '2025-11-12 06:52:26'),
(92, 2, 'Bump nappy', 'kids', 450.00, 'Bump-nappy.webp', '2025-11-12 06:53:31'),
(93, 2, 'Consol round glass', 'kitchen', 560.00, 'ConsolRoundglass.webp', '2025-11-12 06:54:16'),
(94, 2, 'Cutting board', 'kitchen', 350.00, 'Cutting-boards.jpg', '2025-11-12 06:55:04'),
(95, 2, 'Essentials towel', 'kitchen', 360.00, 'Essentials-towel.webp', '2025-11-12 06:55:59'),
(96, 2, 'Food stotage', 'kitchen', 240.00, 'Food-storage.webp', '2025-11-12 06:56:42'),
(97, 2, 'Heart board ', 'kitchen', 89.00, 'Heart-board.webp', '2025-11-12 06:57:23'),
(98, 2, 'Towel', 'kitchen', 34.00, 'Kitchen-towel.jpg', '2025-11-12 06:58:07'),
(99, 2, 'Cutting board', 'kitchen', 56.99, 'Kitchen-Utensil.webp', '2025-11-12 06:58:53'),
(101, 2, 'Reusable', 'kitchen', 178.00, 'Reusable.jpg', '2025-11-12 07:00:50'),
(102, 2, 'Ribbed glass', 'kitchen', 349.00, 'Ribbedglass.webp', '2025-11-12 07:02:09'),
(103, 2, 'Silicone milk storage', 'kitchen', 230.00, 'Silicone-Milkstorage.webp', '2025-11-12 07:03:14'),
(104, 2, 'Plastic', 'kitchen', 67.00, 'Plastic.jpg', '2025-11-12 07:03:53'),
(105, 2, 'Sponge', 'kitchen', 89.00, 'Sponge.webp', '2025-11-12 07:04:53'),
(106, 2, 'Sponges', 'kitchen', 98.00, 'Sponges.jpg', '2025-11-12 07:05:49'),
(107, 2, 'Stainless', 'kitchen', 167.00, 'Stainless.webp', '2025-11-12 07:06:37'),
(108, 2, 'Stainless straw', 'kitchen', 167.00, 'Stainlessstraw.webp', '2025-11-12 07:07:33'),
(109, 2, 'Storage jars', 'kitchen', 790.00, 'Storage-jars.jpg', '2025-11-12 07:08:23'),
(110, 2, 'Tim holtz', 'kitchen', 567.00, 'Timholtz.webp', '2025-11-12 07:09:23'),
(111, 2, 'Organic towel', 'kitchen', 156.00, 'Organictowel.webp', '2025-11-12 07:10:17'),
(112, 5, 'Aroma green', 'lifestyle', 345.00, 'Aroma.webp', '2025-11-12 07:53:05'),
(113, 5, 'Bottle', 'lifestyle', 56.00, 'Bottle.jpg', '2025-11-12 07:53:51'),
(114, 5, 'Botte recycled', 'lifestyle', 230.00, 'Bottle-recycled.webp', '2025-11-12 07:54:44'),
(115, 5, 'Diary', 'lifestyle', 68.00, 'Diary.jpg', '2025-11-12 07:56:05'),
(116, 5, 'Carmien tea', 'lifestyle', 345.00, 'Carmien-tea.webp', '2025-11-12 07:57:09'),
(117, 5, 'Consol solar jar', 'lifestyle', 450.00, 'Consol-solarjar.webp', '2025-11-12 07:58:09'),
(118, 5, 'Divine incenses stick', 'life', 678.00, 'Divine-incensestick.web', '2025-11-12 07:59:33'),
(119, 5, 'Markes', 'lifestyle', 56.00, 'Markes.webp', '2025-11-12 08:00:29'),
(120, 5, 'Yoga mat', 'lifestyle', 300.00, 'yoga-mat.jpg', '2025-11-12 08:01:37'),
(121, 5, 'Notebook', 'lifestyle', 48.00, 'Notebook.webp', '2025-11-12 08:02:24'),
(122, 5, 'Notebook and pen', 'lifestyle', 176.00, 'Notebook-pen.webp', '2025-11-12 08:03:19'),
(123, 5, 'Pakka tea', 'lifestyle', 567.00, 'Pakka.webp', '2025-11-12 08:03:56'),
(124, 5, 'Plastic bottle', 'lifestyle', 34.00, 'Plastic-bottle.webp', '2025-11-12 08:04:34'),
(125, 5, 'Rooibos tea', 'lifestyle', 78.00, 'Rooibos-tea.webp', '2025-11-12 08:05:11'),
(126, 5, 'Sela tea', 'lifestyle', 90.00, 'Sela-tea.webp', '2025-11-12 08:05:53'),
(127, 5, 'solar', 'lifestyle', 400.00, 'Solar.jpg', '2025-11-12 08:06:44'),
(128, 5, 'Solar bank', 'lifestyle', 569.00, 'SolarBank.webp', '2025-11-12 08:07:24'),
(129, 5, 'Solar radio', 'lifestyle', 600.00, 'Solar-radio.webp', '2025-11-12 08:08:22'),
(130, 5, 'Sports bags', 'lifestyle', 458.00, 'Sportsbags.webp', '2025-11-12 08:09:07'),
(131, 5, 'Yogi matt', 'lifestyle', 500.00, 'yogimat.webp', '2025-11-12 08:16:37'),
(132, 7, 'Addis pot', 'outdoor', 199.99, 'Addis-venus.webp', '2025-11-12 08:57:21'),
(133, 7, 'Biogrow', 'outdoor', 78.00, 'Biogrow.webp', '2025-11-12 08:58:33'),
(134, 7, 'Garden ', 'outdoor', 499.99, 'Garden.jpg', '2025-11-12 08:59:40'),
(135, 7, 'Earthworm bin', 'outdoor', 300.00, 'Earthworm-bin.webp', '2025-11-12 09:00:41'),
(136, 7, 'Garden solar', 'outdoor', 689.00, 'Gardensolar.webp', '2025-11-12 09:01:43'),
(137, 4, 'Guanoboost', 'outdoor', 500.00, 'Guanoboost.webp', '2025-11-12 09:03:03'),
(138, 7, 'Lamp', 'outdoor', 350.00, 'Lamp.webp', '2025-11-12 09:05:08'),
(139, 7, 'Light plant', 'outdoor', 400.00, 'Lightsplant.webp', '2025-11-12 09:05:49'),
(140, 7, 'Plant', 'outdoor', 789.00, 'plant.webp', '2025-11-12 09:07:07'),
(141, 7, 'Plant pot', 'lifestyle', 250.00, 'plantpot.webp', '2025-11-12 09:08:01'),
(142, 7, 'Rain water', 'outdoor', 1000.00, 'RainWater-tank.webp', '2025-11-12 09:08:48'),
(143, 7, 'Seed', 'outdoor', 78.00, 'seed.webp', '2025-11-12 09:09:40'),
(144, 7, 'Seed light', 'outdoor', 390.00, 'Seedlight-plant.webp', '2025-11-12 09:10:37'),
(146, 7, 'Seed jar', 'outdoor', 300.00, 'Seed-starter.webp', '2025-11-12 09:12:34'),
(147, 7, 'Slad seed', 'garden', 567.00, 'Slad-seed.webp', '2025-11-12 09:13:32'),
(148, 7, 'Worm bin', 'outdoor', 240.00, 'Worm-bin.webp', '2025-11-12 09:14:51'),
(149, 7, 'Worm', 'garden', 589.00, 'Worm-bins.jpg', '2025-11-12 09:15:30'),
(150, 7, 'Water tank', 'outdoor', 2600.00, 'Water-tank.webp', '2025-11-12 09:16:31');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int NOT NULL,
  `username` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `created_at`) VALUES
(1, 'admin', 'admin@dragonstone.com', '240be518fabd2724ddb6f04eeb1da5967448d7e831c08c8fa822809f74c720a9', '2025-10-21 15:49:09'),
(2, 'john_doe', 'john@dragonstone.com', 'b4b597c714a8f49103da4dab0266af0ee0ae4f8575250a84855c3d76941cd422', '2025-10-21 15:49:09'),
(3, 'jane_smith', 'jane@dragonstone.com', '27545b395a8e5915b48557d0e26ef3e05e368d0f65ae786a806df38f9f4e3bc5', '2025-10-21 15:49:09'),
(4, 'atilia', 'atilia@dragonstone.com', '$2y$10$qbycdBGnDNV/wo76LZxe5urVnYLg.HhfYLIUfhvBSwXufCqK3BzVS', '2025-10-28 01:40:27'),
(5, 'Nathan', 'nath@drangonstone.com', '$2y$10$Ea3OxeXvMlej/MuCEci/IujP93SNfI2nTtKU54tbp1i/rQmZiQake', '2025-10-28 01:42:17');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=151;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `cart`
--
ALTER TABLE `cart`
  ADD CONSTRAINT `cart_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `cart_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

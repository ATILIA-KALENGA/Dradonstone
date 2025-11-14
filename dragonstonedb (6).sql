-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 14, 2025 at 01:51 AM
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
-- Database: `dragonstonedb`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `admis` varchar(255) DEFAULT NULL,
  `username` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `email`, `password`, `admis`, `username`) VALUES
(1, 'admin@dragonstone.com', 'admin123', NULL, '');

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `co2_saved_per_item` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
-- Table structure for table `community_posts`
--

CREATE TABLE `community_posts` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `post_content` text NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `status` varchar(50) DEFAULT 'Pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `total_amount` decimal(10,2) DEFAULT NULL,
  `payment_method` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `status`, `created_at`, `total_amount`, `payment_method`) VALUES
(1, 2, 'Pending', '2025-10-21 15:49:09', 0.00, NULL),
(2, 3, 'Delivered', '2025-10-21 15:49:09', 0.00, NULL),
(3, 2, 'pending', '2025-10-21 15:51:39', 0.00, NULL),
(5, 4, 'pending', '2025-10-28 22:00:10', 0.00, NULL),
(6, 4, 'pending', '2025-10-28 23:09:39', 0.00, NULL),
(7, 4, 'pending', '2025-10-28 23:16:42', 0.00, NULL),
(8, 4, 'pending', '2025-10-28 23:21:53', 0.00, NULL),
(9, 4, 'Pending', '2025-11-12 20:21:28', 589.00, 'cod'),
(10, 6, 'pending', '2025-11-13 16:14:30', 2600.00, 'cod'),
(11, 4, 'Pending', '2025-11-13 19:54:08', 600.00, 'cod'),
(12, 4, 'Pending', '2025-11-13 23:39:44', 48.00, 'cod'),
(13, 4, 'Pending', '2025-11-13 23:40:18', 300.00, 'payfast'),
(14, 4, 'Pending', '2025-11-13 23:40:36', 300.00, 'card');

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `product_id`, `quantity`, `price`) VALUES
(33, 9, 149, 1, 589.00),
(34, 10, 150, 1, 2600.00),
(35, 11, 59, 1, 600.00),
(37, 13, 146, 1, 300.00),
(38, 14, 146, 1, 300.00);

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `name` varchar(150) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `carbon_footprint` decimal(5,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `category_id`, `name`, `description`, `price`, `image`, `created_at`, `carbon_footprint`) VALUES
(1, 1, 'Bingo', 'cleaning', 67.00, 'Bingo.jpg', '2025-11-11 22:16:50', 1.25),
(2, 2, 'Beeswax food', 'kitchen', 67.00, 'Beeswaxfood.webp', '2025-11-12 06:50:06', 0.85),
(4, 4, 'Toothpaste', 'Bathroom', 12.99, 'Toothpaste.jpg', '2025-11-11 21:28:44', 1.25),
(5, 5, 'Aluminum bottle', 'lifestyle', 200.00, 'Aluminium-bottle.webp', '2025-11-12 07:52:28', 1.75),
(6, 6, 'Baby clearance', 'kids', 45.00, 'Baby-clearance.webp', '2025-11-12 06:19:38', 0.90),
(7, 7, 'Acapulco pot', 'outdoor', 567.00, 'Acapulco-pot.webp', '2025-11-12 08:56:39', 2.15),
(30, 4, 'toothbrush', 'bathroom', 10.00, 'toothbrush.jpg', '2025-11-11 21:42:52', 0.65),
(32, 4, 'Vaseline', 'personal care', 78.00, 'Vaseline.jpg', '2025-11-11 21:44:00', 1.10),
(34, 4, 'Vaseline jelly', 'personal care ', 48.99, 'Jelly.jpg', '2025-11-11 21:45:08', 1.25),
(36, 4, 'Jelly Vaseline', 'personal care', 38.99, 'jelly-Vaseline.jpg', '2025-11-11 21:46:27', 1.05),
(38, 4, 'Glove', 'bathroom', 24.00, 'Glove.jpg', '2025-11-11 21:47:22', 0.80),
(40, 4, 'Floss', 'bathroom', 120.00, 'Floss-picks.jpg', '2025-11-11 21:48:20', 0.95),
(42, 4, 'Dove', 'bathroom', 38.99, 'Dove.jpg', '2025-11-11 21:50:43', 1.20),
(43, 4, 'Cerave', 'personal care', 200.00, 'Cerave.jpg', '2025-11-11 21:53:19', 1.00),
(45, 1, 'Clorox bleach', 'cleaning', 67.00, 'Clorox-Bleach.jpg', '2025-11-11 22:17:45', 1.35),
(46, 1, 'Drain', 'cleaning', 124.00, 'Drain-clog.jpg', '2025-11-11 22:18:32', 0.70),
(47, 1, 'Glass cleans', 'cleaning', 59.00, 'Glass-clean.jpg', '2025-11-11 22:20:12', 0.90),
(48, 1, 'Couch clean', 'cleaning', 250.00, 'Couchclean.jpg', '2025-11-11 22:22:02', 1.40),
(49, 1, 'Tissue paper', 'supplies', 130.00, 'Tissues.jpg', '2025-11-11 22:25:49', 1.10),
(50, 1, 'Produto', 'cleaning', 45.00, 'Produto.jpg', '2025-11-11 22:27:16', 1.05),
(51, 1, 'Purex', 'Cleaning', 67.99, 'Purex.jpg', '2025-11-11 22:27:59', 0.95),
(52, 1, 'Domestos', 'cleaning', 35.00, 'Domestos.jpg', '2025-11-12 04:42:01', 1.00),
(53, 1, 'Pine gel', 'cleaning', 45.99, 'Pine-gel.jpg', '2025-11-12 04:43:35', 0.75),
(54, 1, 'Broom', 'cleaning', 83.00, 'Broom.webp', '2025-11-12 04:52:01', 0.80),
(55, 1, 'Handy andy', 'cleaning', 35.00, 'Handy.webp', '2025-11-12 04:52:47', 0.90),
(56, 1, 'Jik', 'cleaning', 35.00, 'jik.webp', '2025-11-12 04:53:28', NULL),
(57, 1, 'Omo', 'cleaning', 45.00, 'Omo.webp', '2025-11-12 04:54:51', NULL),
(58, 3, 'Home decor', 'Home', 1500.00, 'home-decor.avif', '2025-11-12 05:02:46', NULL),
(59, 3, 'Decor', 'home', 600.00, 'decor (3).jpg', '2025-11-12 05:03:48', NULL),
(60, 3, 'Ceramic-vase', 'heme decor', 850.00, 'Ceramic-vase.webp', '2025-11-12 05:04:30', NULL),
(61, 3, 'Ceramic balls', 'home decor', 1590.00, 'Ceramic-balls.webp', '2025-11-12 05:09:27', NULL),
(62, 3, 'Ornaments for living', 'Home decor', 1200.00, 'Hornement.webp', '2025-11-12 05:11:17', NULL),
(63, 3, 'Horn sculpture', 'Home decor', 2500.00, 'Horn-sculpture.webp', '2025-11-12 05:13:01', NULL),
(64, 3, 'Living decor', 'home decor', 650.99, 'homedeco.jpg', '2025-11-12 05:13:55', NULL),
(65, 3, 'Mirror', 'home decor', 350.00, 'Mirror.jpg', '2025-11-12 05:14:37', NULL),
(66, 3, 'Room plant', 'home decor', 670.00, 'Room-plant.webp', '2025-11-12 05:15:30', NULL),
(67, 3, 'Wood frame', 'home decor', 68.00, 'Wood-frame.jpg', '2025-11-12 05:16:21', NULL),
(68, 3, 'Wall decor', 'home decor', 349.99, 'Wall-decor.jpg', '2025-11-12 05:17:06', NULL),
(71, 3, 'Jars glass', 'home decor', 78.99, 'Decor.jpg', '2025-11-12 05:59:05', NULL),
(72, 3, 'Home light ', 'home decor', 2300.00, 'home-ligth.avif', '2025-11-12 06:01:06', NULL),
(73, 6, 'Baby nest', 'kids', 545.00, 'Baby-net.webp', '2025-11-12 06:20:32', NULL),
(74, 6, 'Baby cotton', 'kids', 34.00, 'Cotton-kids.webp', '2025-11-12 06:21:10', NULL),
(75, 6, 'Fisher toy', 'kids', 400.00, 'Fisher-toy.webp', '2025-11-12 06:21:52', NULL),
(76, 6, 'Giraffe toy', 'kids', 78.99, 'Giraffe.webp', '2025-11-12 06:22:36', NULL),
(77, 6, 'Long sleeve', 'kids', 159.00, 'Long-sleeve.webp', '2025-11-12 06:23:54', NULL),
(78, 6, 'Montessori', 'kids', 178.00, 'Montessori.webp', '2025-11-12 06:24:33', NULL),
(79, 6, 'Newborn', 'kids', 239.00, 'Newborn.webp', '2025-11-12 06:25:42', NULL),
(80, 6, 'Reusable pack', 'kids', 350.00, 'Pack-flower.webp', '2025-11-12 06:26:37', NULL),
(81, 6, 'Pet shampoo', 'pet', 190.99, 'Pet-shampoo.webp', '2025-11-12 06:27:19', NULL),
(82, 6, 'Pet spa', 'pet', 56.00, 'Pet-spa.webp', '2025-11-12 06:27:54', NULL),
(83, 6, 'Pikler', 'kids', 450.00, 'Pikler.webp', '2025-11-12 06:28:31', NULL),
(84, 6, 'Rabbit', 'kids', 89.99, 'Rabbit.webp', '2025-11-12 06:29:10', NULL),
(85, 6, 'Reusable baby', 'kids', 140.00, 'Reusable-baby.webp', '2025-11-12 06:29:57', NULL),
(86, 6, 'Ring stacker', 'kids', 250.00, 'Ring-stacker.webp', '2025-11-12 06:30:41', NULL),
(87, 6, 'Plastic', 'pet', 56.00, 'Plastic.jpg', '2025-11-12 06:31:33', NULL),
(88, 6, 'Trailer toy', 'kids', 239.00, 'Trailer-toy.webp', '2025-11-12 06:32:33', NULL),
(89, 6, 'Wooden car', 'kids', 189.00, 'wooden-car.webp', '2025-11-12 06:33:14', NULL),
(90, 6, 'Wood toy', 'kids', 45.00, 'Wood-toy.jpg', '2025-11-12 06:33:51', NULL),
(91, 2, 'Beeswax wrap', 'kitchen', 56.00, 'Beeswaxwrap.webp', '2025-11-12 06:52:26', NULL),
(92, 2, 'Bump nappy', 'kids', 450.00, 'Bump-nappy.webp', '2025-11-12 06:53:31', NULL),
(93, 2, 'Consol round glass', 'kitchen', 560.00, 'ConsolRoundglass.webp', '2025-11-12 06:54:16', NULL),
(94, 2, 'Cutting board', 'kitchen', 350.00, 'Cutting-boards.jpg', '2025-11-12 06:55:04', NULL),
(95, 2, 'Essentials towel', 'kitchen', 360.00, 'Essentials-towel.webp', '2025-11-12 06:55:59', NULL),
(96, 2, 'Food stotage', 'kitchen', 240.00, 'Food-storage.webp', '2025-11-12 06:56:42', NULL),
(97, 2, 'Heart board ', 'kitchen', 89.00, 'Heart-board.webp', '2025-11-12 06:57:23', NULL),
(98, 2, 'Towel', 'kitchen', 34.00, 'Kitchen-towel.jpg', '2025-11-12 06:58:07', NULL),
(99, 2, 'Cutting board', 'kitchen', 56.99, 'Kitchen-Utensil.webp', '2025-11-12 06:58:53', NULL),
(101, 2, 'Reusable', 'kitchen', 178.00, 'Reusable.jpg', '2025-11-12 07:00:50', NULL),
(102, 2, 'Ribbed glass', 'kitchen', 349.00, 'Ribbedglass.webp', '2025-11-12 07:02:09', NULL),
(103, 2, 'Silicone milk storage', 'kitchen', 230.00, 'Silicone-Milkstorage.webp', '2025-11-12 07:03:14', NULL),
(104, 2, 'Plastic', 'kitchen', 67.00, 'Plastic.jpg', '2025-11-12 07:03:53', NULL),
(105, 2, 'Sponge', 'kitchen', 89.00, 'Sponge.webp', '2025-11-12 07:04:53', NULL),
(106, 2, 'Sponges', 'kitchen', 98.00, 'Sponges.jpg', '2025-11-12 07:05:49', NULL),
(107, 2, 'Stainless', 'kitchen', 167.00, 'Stainless.webp', '2025-11-12 07:06:37', NULL),
(108, 2, 'Stainless straw', 'kitchen', 167.00, 'Stainlessstraw.webp', '2025-11-12 07:07:33', NULL),
(109, 2, 'Storage jars', 'kitchen', 790.00, 'Storage-jars.jpg', '2025-11-12 07:08:23', NULL),
(110, 2, 'Tim holtz', 'kitchen', 567.00, 'Timholtz.webp', '2025-11-12 07:09:23', NULL),
(111, 2, 'Organic towel', 'kitchen', 156.00, 'Organictowel.webp', '2025-11-12 07:10:17', NULL),
(112, 5, 'Aroma green', 'lifestyle', 345.00, 'Aroma.webp', '2025-11-12 07:53:05', NULL),
(113, 5, 'Bottle', 'lifestyle', 56.00, 'Bottle.jpg', '2025-11-12 07:53:51', NULL),
(114, 5, 'Botte recycled', 'lifestyle', 230.00, 'Bottle-recycled.webp', '2025-11-12 07:54:44', NULL),
(115, 5, 'Diary', 'lifestyle', 68.00, 'Diary.jpg', '2025-11-12 07:56:05', NULL),
(116, 5, 'Carmien tea', 'lifestyle', 345.00, 'Carmien-tea.webp', '2025-11-12 07:57:09', NULL),
(117, 5, 'Consol solar jar', 'lifestyle', 450.00, 'Consol-solarjar.webp', '2025-11-12 07:58:09', NULL),
(118, 5, 'Divine incenses stick', 'life', 678.00, 'Divine-incensestick.web', '2025-11-12 07:59:33', NULL),
(119, 5, 'Markes', 'lifestyle', 56.00, 'Markes.webp', '2025-11-12 08:00:29', NULL),
(120, 5, 'Yoga mat', 'lifestyle', 300.00, 'yoga-mat.jpg', '2025-11-12 08:01:37', NULL),
(121, 5, 'Notebook', 'lifestyle', 48.00, 'Notebook.webp', '2025-11-12 08:02:24', NULL),
(122, 5, 'Notebook and pen', 'lifestyle', 176.00, 'Notebook-pen.webp', '2025-11-12 08:03:19', NULL),
(123, 5, 'Pakka tea', 'lifestyle', 567.00, 'Pakka.webp', '2025-11-12 08:03:56', NULL),
(124, 5, 'Plastic bottle', 'lifestyle', 34.00, 'Plastic-bottle.webp', '2025-11-12 08:04:34', NULL),
(125, 5, 'Rooibos tea', 'lifestyle', 78.00, 'Rooibos-tea.webp', '2025-11-12 08:05:11', NULL),
(126, 5, 'Sela tea', 'lifestyle', 90.00, 'Sela-tea.webp', '2025-11-12 08:05:53', NULL),
(127, 5, 'solar', 'lifestyle', 400.00, 'Solar.jpg', '2025-11-12 08:06:44', NULL),
(128, 5, 'Solar bank', 'lifestyle', 569.00, 'SolarBank.webp', '2025-11-12 08:07:24', NULL),
(129, 5, 'Solar radio', 'lifestyle', 600.00, 'Solar-radio.webp', '2025-11-12 08:08:22', NULL),
(130, 5, 'Sports bags', 'lifestyle', 458.00, 'Sportsbags.webp', '2025-11-12 08:09:07', NULL),
(131, 5, 'Yogi matt', 'lifestyle', 500.00, 'yogimat.webp', '2025-11-12 08:16:37', NULL),
(132, 7, 'Addis pot', 'outdoor', 199.99, 'Addis-venus.webp', '2025-11-12 08:57:21', NULL),
(133, 7, 'Biogrow', 'outdoor', 78.00, 'Biogrow.webp', '2025-11-12 08:58:33', NULL),
(134, 7, 'Garden ', 'outdoor', 499.99, 'Garden.jpg', '2025-11-12 08:59:40', 2.10),
(135, 7, 'Earthworm bin', 'outdoor', 300.00, 'Earthworm-bin.webp', '2025-11-12 09:00:41', 1.85),
(136, 7, 'Garden solar', 'outdoor', 689.00, 'Gardensolar.webp', '2025-11-12 09:01:43', 2.50),
(137, 4, 'Guanoboost', 'outdoor', 500.00, 'Guanoboost.webp', '2025-11-12 09:03:03', 2.00),
(138, 7, 'Lamp', 'outdoor', 350.00, 'Lamp.webp', '2025-11-12 09:05:08', 1.40),
(139, 7, 'Light plant', 'outdoor', 400.00, 'Lightsplant.webp', '2025-11-12 09:05:49', 1.65),
(140, 7, 'Plant', 'outdoor', 789.00, 'plant.webp', '2025-11-12 09:07:07', 1.20),
(141, 7, 'Plant pot', 'lifestyle', 250.00, 'plantpot.webp', '2025-11-12 09:08:01', 1.10),
(142, 7, 'Rain water', 'outdoor', 1000.00, 'RainWater-tank.webp', '2025-11-12 09:08:48', 2.80),
(143, 7, 'Seed', 'outdoor', 78.00, 'seed.webp', '2025-11-12 09:09:40', 1.30),
(144, 7, 'Seed light', 'outdoor', 390.00, 'Seedlight-plant.webp', '2025-11-12 09:10:37', 1.50),
(146, 7, 'Seed jar', 'outdoor', 300.00, 'Seed-starter.webp', '2025-11-12 09:12:34', 1.10),
(147, 7, 'Slad seed', 'garden', 567.00, 'Slad-seed.webp', '2025-11-12 09:13:32', 2.00),
(148, 7, 'Worm bin', 'outdoor', 240.00, 'Worm-bin.webp', '2025-11-12 09:14:51', 1.60),
(149, 7, 'Worm', 'garden', 589.00, 'Worm-bins.jpg', '2025-11-12 09:15:30', 1.25),
(150, 7, 'Water tank', 'outdoor', 2600.00, 'Water-tank.webp', '2025-11-12 09:16:31', 2.70);

-- --------------------------------------------------------

--
-- Table structure for table `subscriptions`
--

CREATE TABLE `subscriptions` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `stripe_sub_id` varchar(255) DEFAULT NULL,
  `frequency` varchar(50) DEFAULT NULL,
  `start_date` date NOT NULL,
  `next_billing_date` date NOT NULL,
  `next_bill` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `status` varchar(20) NOT NULL DEFAULT 'active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `subscriptions`
--

INSERT INTO `subscriptions` (`id`, `user_id`, `product_id`, `stripe_sub_id`, `frequency`, `start_date`, `next_billing_date`, `next_bill`, `created_at`, `status`) VALUES
(1, 4, 150, NULL, 'monthly', '2025-11-14', '2025-12-14', NULL, '2025-11-13 23:18:38', 'active'),
(2, 4, 150, NULL, 'monthly', '2025-11-14', '2025-12-14', NULL, '2025-11-13 23:18:58', 'active'),
(3, 4, 150, NULL, 'monthly', '2025-11-14', '2025-12-14', NULL, '2025-11-13 23:30:01', 'active'),
(4, 4, 150, NULL, 'quarterly', '2025-11-14', '2025-12-14', NULL, '2025-11-13 23:31:01', 'active'),
(5, 4, 150, NULL, 'quarterly', '2025-11-14', '2025-12-14', NULL, '2025-11-13 23:32:39', 'active'),
(6, 4, 150, NULL, 'quarterly', '2025-11-14', '2025-12-14', NULL, '2025-11-13 23:33:45', 'active'),
(7, 4, 150, NULL, 'quarterly', '2025-11-14', '2025-12-14', NULL, '2025-11-13 23:34:04', 'active'),
(8, 4, 150, NULL, 'quarterly', '2025-11-14', '2025-12-14', NULL, '2025-11-13 23:35:54', 'active'),
(9, 4, 150, NULL, 'monthly', '2025-11-14', '2025-12-14', NULL, '2025-11-13 23:37:01', 'active');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `ecopoints` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `created_at`, `ecopoints`) VALUES
(2, 'john_doe', 'john@dragonstone.com', 'b4b597c714a8f49103da4dab0266af0ee0ae4f8575250a84855c3d76941cd422', '2025-10-21 15:49:09', 0),
(3, 'jane_smith', 'jane@dragonstone.com', '27545b395a8e5915b48557d0e26ef3e05e368d0f65ae786a806df38f9f4e3bc5', '2025-10-21 15:49:09', 0),
(4, 'atilia', 'atilia@dragonstone.com', '$2y$10$qbycdBGnDNV/wo76LZxe5urVnYLg.HhfYLIUfhvBSwXufCqK3BzVS', '2025-10-28 01:40:27', 0),
(5, 'Nathan', 'nath@drangonstone.com', '$2y$10$Ea3OxeXvMlej/MuCEci/IujP93SNfI2nTtKU54tbp1i/rQmZiQake', '2025-10-28 01:42:17', 0),
(6, 'happy', 'happymushiya@gmail.com', '$2y$10$dnXpdG8FDi1vKHVeFqdU0.xjyjyF30jsLb/E3DQGFC2wSf3CoCR1.', '2025-11-13 16:14:08', 0),
(7, 'Admin Stone', 'adminstone@gmail.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '2025-11-13 16:34:30', 0),
(10, 'Admin', 'admin@dragonstone.com', 'a665a45920422f9d417e4867efdc4fb8a04a1f3fff1fa07e998e86f7f7a27ae3', '2025-11-13 17:47:49', 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`);

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
-- Indexes for table `community_posts`
--
ALTER TABLE `community_posts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

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
-- Indexes for table `subscriptions`
--
ALTER TABLE `subscriptions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `product_id` (`product_id`);

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
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `community_posts`
--
ALTER TABLE `community_posts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=153;

--
-- AUTO_INCREMENT for table `subscriptions`
--
ALTER TABLE `subscriptions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

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
-- Constraints for table `community_posts`
--
ALTER TABLE `community_posts`
  ADD CONSTRAINT `community_posts_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

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

--
-- Constraints for table `subscriptions`
--
ALTER TABLE `subscriptions`
  ADD CONSTRAINT `subscriptions_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `subscriptions_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

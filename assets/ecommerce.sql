-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 15, 2025 at 11:32 AM
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
-- Database: `ecommerce`
--

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `tittle` varchar(256) NOT NULL,
  `details` varchar(256) NOT NULL,
  `price` int(11) NOT NULL,
  `image` varchar(256) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `tittle`, `details`, `price`, `image`) VALUES
(31, 'Orange', 'Organic oranges', 4, '5605_best-product-1.jpg'),
(32, 'Banana', 'Organic Banana', 1200, '5092_best-product-3.jpg'),
(33, 'Apple', 'Organic Apples', 1, '8886_best-product-6.jpg'),
(34, 'Apple', 'Organic Apples', 1, '4919_best-product-6.jpg'),
(35, 'Apple', 'Organic Apples', 1, '3440_best-product-6.jpg'),
(36, 'Apple', 'Organic Apples', 1, '6487_best-product-6.jpg'),
(37, 'Apple', 'Organic Apples', 1, '4885_best-product-6.jpg'),
(38, 'Apple', 'Organic Apples', 1, '6255_best-product-6.jpg'),
(39, 'Apple', 'Organic Apples', 1, '2092_best-product-6.jpg'),
(40, 'Apple', 'Organic Apples', 1, '9496_best-product-6.jpg');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

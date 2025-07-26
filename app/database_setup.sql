-- E-commerce Database Setup
-- Run this SQL file in your MySQL database

-- Create database if not exists
CREATE DATABASE IF NOT EXISTS ecommerce;
USE ecommerce;

-- Products table
CREATE TABLE IF NOT EXISTS `products` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tittle` varchar(255) NOT NULL,
  `details` text,
  `price` decimal(10,2) NOT NULL,
  `category` varchar(100) NOT NULL,
  `image` varchar(255) NOT NULL,
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Comments table
CREATE TABLE IF NOT EXISTS `comments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `review` text NOT NULL,
  `rating` int(1) DEFAULT 5,
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Orders table
CREATE TABLE IF NOT EXISTS `orders` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `first_name` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `address` text,
  `city` varchar(100) NOT NULL,
  `country` varchar(100) DEFAULT 'Pakistan',
  `postal_code` varchar(20) NOT NULL,
  `company_name` varchar(255),
  `notes` text,
  `total_amount` decimal(10,2) NOT NULL,
  `shipping_cost` decimal(10,2) DEFAULT 3.00,
  `status` enum('pending','processing','completed','cancelled') DEFAULT 'pending',
  `order_date` timestamp DEFAULT CURRENT_TIMESTAMP,
  `payment_method` varchar(50) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `bank_account_name` varchar(255) DEFAULT NULL,
  `bank_account_number` varchar(255) DEFAULT NULL,
  `bank_name` varchar(255) DEFAULT NULL,
  `check_number` varchar(255) DEFAULT NULL,
  `check_bank_name` varchar(255) DEFAULT NULL,
  `paypal_email` varchar(255) DEFAULT NULL,
  `paypal_card_number` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Order items table
CREATE TABLE IF NOT EXISTS `order_items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `total` decimal(10,2) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `order_id` (`order_id`),
  KEY `product_id` (`product_id`),
  FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Carousel table
CREATE TABLE IF NOT EXISTS `carousel` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255),
  `description` text,
  `image` varchar(255) NOT NULL,
  `active` tinyint(1) DEFAULT 1,
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Admin users table
CREATE TABLE IF NOT EXISTS `admin_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL UNIQUE,
  `password` varchar(255) NOT NULL,
  `image` varchar(255) DEFAULT 'default.jpg',
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Insert sample admin user (password: admin123)
INSERT INTO `admin_users` (`name`, `email`, `password`) VALUES 
('Admin', 'admin@fruitables.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi');

-- Insert sample products
INSERT INTO `products` (`tittle`, `details`, `price`, `category`, `image`) VALUES
('Fresh Apples', 'Organic red apples, sweet and juicy', 2.99, 'Fruit', 'best-product-1.jpg'),
('Organic Bananas', 'Fresh yellow bananas, rich in potassium', 1.99, 'Fruit', 'best-product-2.jpg'),
('Fresh Broccoli', 'Green broccoli heads, perfect for cooking', 3.49, 'Vegetable', 'vegetable-item-1.jpg'),
('Sweet Oranges', 'Juicy oranges, high in vitamin C', 2.49, 'Fruit', 'best-product-3.jpg'),
('Fresh Tomatoes', 'Red ripe tomatoes, perfect for salads', 1.99, 'Vegetable', 'vegetable-item-2.jpg'),
('Green Spinach', 'Fresh spinach leaves, rich in iron', 2.29, 'Vegetable', 'vegetable-item-3.png');

-- Insert sample carousel images
INSERT INTO `carousel` (`title`, `description`, `image`) VALUES
('Fresh Fruits', 'Get the best organic fruits delivered to your door', 'hero-img-1.png'),
('Organic Vegetables', 'Fresh vegetables from local farms', 'hero-img-2.jpg'),
('Healthy Living', 'Start your healthy journey with us', 'hero-img.jpg'); 
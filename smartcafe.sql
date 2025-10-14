-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 12, 2025 at 08:17 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `smartcafe`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `admin_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`admin_id`) VALUES
(1004),
(1008);

-- --------------------------------------------------------

--
-- Table structure for table `coupons`
--

CREATE TABLE `coupons` (
  `coupon_id` int(11) NOT NULL,
  `code` varchar(50) NOT NULL,
  `description` text DEFAULT NULL,
  `discount` decimal(5,2) NOT NULL,
  `valid_from` date DEFAULT NULL,
  `valid_to` date DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `customercoupons`
--

CREATE TABLE `customercoupons` (
  `customer_coupon_id` int(11) NOT NULL,
  `customer_id` int(11) DEFAULT NULL,
  `coupon_id` int(11) DEFAULT NULL,
  `applied_order_id` int(11) DEFAULT NULL,
  `status` enum('NotUsed','Applied','Expired') DEFAULT 'NotUsed',
  `applied_date` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `customerpreferences`
--

CREATE TABLE `customerpreferences` (
  `id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `item_name` varchar(120) NOT NULL,
  `spice_level` enum('mild','medium','hot') DEFAULT 'medium',
  `is_favorite` tinyint(1) DEFAULT 0,
  `notes` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `customers`
--

CREATE TABLE `customers` (
  `customer_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `customers`
--

INSERT INTO `customers` (`customer_id`) VALUES
(1000),
(1001),
(1002),
(1003);

-- --------------------------------------------------------

--
-- Table structure for table `inventory`
--

CREATE TABLE `inventory` (
  `inventory_id` int(11) NOT NULL,
  `menu_item_id` int(11) DEFAULT NULL,
  `stock_quantity` int(11) DEFAULT 0,
  `last_restock_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `managers`
--

CREATE TABLE `managers` (
  `manager_id` int(11) NOT NULL,
  `salary` decimal(10,2) DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `mealpreferences`
--

CREATE TABLE `mealpreferences` (
  `preference_id` int(11) NOT NULL,
  `customer_id` int(11) DEFAULT NULL,
  `preference_type` varchar(100) DEFAULT NULL,
  `details` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `mealpreferences`
--

INSERT INTO `mealpreferences` (`preference_id`, `customer_id`, `preference_type`, `details`, `created_at`) VALUES
(3, 1000, 'pizza', 'bad', '2025-10-12 13:44:35'),
(4, 1000, 'cappicino', 'good', '2025-10-12 13:44:55'),
(5, 1000, 'pizza', 'good', '2025-10-12 15:35:50'),
(6, 1000, 'pizza', 'good', '2025-10-12 17:49:23');

-- --------------------------------------------------------

--
-- Table structure for table `menuitems`
--

CREATE TABLE `menuitems` (
  `menu_item_id` int(11) NOT NULL,
  `vendor_id` int(11) DEFAULT NULL,
  `managed_by` int(11) DEFAULT NULL,
  `name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `image_url` varchar(255) DEFAULT NULL,
  `available` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `menuitems`
--

INSERT INTO `menuitems` (`menu_item_id`, `vendor_id`, `managed_by`, `name`, `description`, `price`, `image_url`, `available`, `created_at`) VALUES
(1, NULL, NULL, 'Pizza', 'Cheesy pizza slice', 150.00, 'pizza.jpg', 1, '2025-09-24 19:07:49'),
(2, NULL, NULL, 'Burger', 'Juicy beef burger', 180.00, 'burger.jpg', 1, '2025-09-24 19:07:49'),
(3, NULL, NULL, 'Shawarma', 'Chicken shawarma roll', 160.00, 'shawarma.jpg', 1, '2025-09-24 19:07:49'),
(4, NULL, NULL, 'Cold Coffee', 'Iced cold coffee', 120.00, 'cold_coffee.jpg', 1, '2025-09-24 19:07:49'),
(5, NULL, NULL, 'Hot Coffee', 'Fresh hot coffee', 100.00, 'hot_coffee.jpg', 1, '2025-09-24 19:07:49'),
(6, NULL, NULL, 'Fried Rice', 'Egg fried rice', 140.00, 'fried_rice.jpg', 1, '2025-09-24 19:07:49'),
(7, NULL, NULL, 'Chicken Fry', 'Crispy chicken fry', 170.00, 'chicken_fry.jpg', 1, '2025-09-24 19:07:49'),
(8, NULL, NULL, 'Vegetable', 'Mixed veg bowl', 110.00, 'vegetable.jpg', 1, '2025-09-24 19:07:49'),
(9, NULL, NULL, 'Pasta', 'Creamy chicken pasta', 160.00, 'pasta.jpg', 1, '2025-09-24 19:07:49'),
(10, NULL, NULL, 'Noodles', 'Hakka noodles', 140.00, 'noodles.jpg', 1, '2025-09-24 19:07:49'),
(11, NULL, NULL, 'Momo', 'Steamed chicken momo', 150.00, 'momo.jpg', 1, '2025-09-24 19:07:49'),
(12, NULL, NULL, 'Biryani', 'Chicken biryani', 180.00, 'biryani.jpg', 1, '2025-09-24 19:07:49'),
(13, NULL, NULL, 'Kebav', 'Beef seekh kebab', 190.00, 'kebab.jpg', 1, '2025-09-24 19:07:49'),
(14, NULL, NULL, 'Sandwich', 'Club sandwich', 120.00, 'sandwich.jpg', 1, '2025-09-24 19:07:49'),
(15, NULL, NULL, 'Soup', 'Chicken corn soup', 100.00, 'soup.jpg', 1, '2025-09-24 19:07:49'),
(16, NULL, NULL, 'Salad', 'Garden fresh salad', 90.00, 'salad.jpg', 1, '2025-09-24 19:07:49'),
(17, NULL, NULL, 'Steak', 'Grilled beef steak', 350.00, 'steak.jpg', 1, '2025-09-24 19:07:49'),
(18, NULL, NULL, 'Juice', 'Fresh mango juice', 80.00, 'juice.jpg', 1, '2025-09-24 19:07:49'),
(19, NULL, NULL, 'Tea', 'Milk tea', 50.00, 'tea.jpg', 1, '2025-09-24 19:07:49'),
(20, NULL, NULL, 'Brownie', 'Chocolate brownie', 90.00, 'brownie.jpg', 1, '2025-09-24 19:07:49'),
(21, NULL, NULL, 'Cheesecake', 'NY cheesecake slice', 140.00, 'cheesecake.jpg', 1, '2025-09-24 19:07:49'),
(22, NULL, NULL, 'Ice Cream', 'Vanilla ice cream scoop', 60.00, 'icecream.jpg', 1, '2025-09-24 19:07:49'),
(23, NULL, NULL, 'Pizza', 'Cheesy pizza slice', 150.00, 'pizza.jpg', 1, '2025-09-25 05:30:50'),
(24, NULL, NULL, 'Burger', 'Juicy beef burger', 180.00, 'burger.jpg', 1, '2025-09-25 05:30:50'),
(25, NULL, NULL, 'Shawarma', 'Chicken shawarma roll', 160.00, 'shawarma.jpg', 1, '2025-09-25 05:30:50'),
(26, NULL, NULL, 'Cold Coffee', 'Iced cold coffee', 120.00, 'cold_coffee.jpg', 1, '2025-09-25 05:30:50'),
(27, NULL, NULL, 'Hot Coffee', 'Fresh hot coffee', 100.00, 'hot_coffee.jpg', 1, '2025-09-25 05:30:50');

-- --------------------------------------------------------

--
-- Table structure for table `orderitems`
--

CREATE TABLE `orderitems` (
  `order_item_id` int(11) NOT NULL,
  `order_id` int(11) DEFAULT NULL,
  `menu_item_id` int(11) DEFAULT NULL,
  `quantity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orderitems`
--

INSERT INTO `orderitems` (`order_item_id`, `order_id`, `menu_item_id`, `quantity`) VALUES
(1, 1, 1, 2),
(2, 1, 3, 1),
(3, 1, 4, 2),
(4, 1, 5, 1),
(5, 1, 6, 1),
(6, 1, 22, 1),
(7, 2, 1, 1),
(8, 3, 1, 8),
(9, 3, 2, 4),
(10, 4, 1, 1),
(11, 5, 1, 1),
(12, 6, 1, 1),
(13, 6, 2, 1),
(14, 6, 5, 3),
(15, 7, 1, 1),
(16, 7, 11, 3),
(17, 8, 1, 1),
(18, 9, 2, 1),
(19, 10, 1, 1),
(20, 10, 2, 1),
(21, 11, 1, 2),
(22, 11, 2, 1),
(23, 12, 1, 2),
(24, 12, 2, 1),
(25, 13, 1, 2),
(26, 13, 2, 1),
(27, 14, 1, 2),
(28, 14, 2, 1),
(29, 15, 1, 3),
(30, 15, 2, 1),
(31, 16, 1, 1),
(32, 17, 1, 1),
(33, 18, 1, 1),
(34, 18, 2, 1),
(35, 19, 1, 1),
(36, 20, 1, 4),
(37, 20, 3, 1),
(38, 21, 1, 1),
(39, 21, 4, 2),
(40, 21, 3, 1),
(41, 22, 1, 1),
(42, 23, 1, 1),
(43, 24, 1, 1),
(44, 25, 1, 1),
(45, 26, 1, 1),
(46, 27, 1, 1),
(47, 28, 1, 1),
(48, 29, 1, 1),
(49, 30, 1, 1),
(50, 31, 1, 1),
(51, 32, 1, 1),
(52, 33, 1, 3),
(53, 34, 1, 1),
(54, 35, 1, 1),
(55, 36, 1, 1),
(56, 37, 1, 1),
(57, 38, 11, 3),
(58, 39, 1, 2);

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `order_id` int(11) NOT NULL,
  `customer_id` int(11) DEFAULT NULL,
  `order_status` enum('Pending','Completed','Cancelled') DEFAULT 'Pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`order_id`, `customer_id`, `order_status`, `created_at`) VALUES
(1, 1002, 'Cancelled', '2025-09-25 09:32:15'),
(2, 1002, 'Cancelled', '2025-09-25 09:32:43'),
(3, 1002, 'Cancelled', '2025-09-25 09:34:16'),
(4, 1002, 'Pending', '2025-09-25 09:34:34'),
(5, 1002, 'Pending', '2025-09-25 09:34:46'),
(6, 1000, 'Cancelled', '2025-10-07 19:04:57'),
(7, 1000, 'Cancelled', '2025-10-08 10:43:56'),
(8, 1000, 'Cancelled', '2025-10-08 10:44:11'),
(9, 1000, 'Cancelled', '2025-10-08 18:21:04'),
(10, 1000, 'Cancelled', '2025-10-08 19:37:13'),
(11, 1000, 'Cancelled', '2025-10-08 19:40:14'),
(12, 1000, 'Cancelled', '2025-10-08 19:40:18'),
(13, 1000, 'Cancelled', '2025-10-08 19:40:22'),
(14, 1000, 'Cancelled', '2025-10-08 19:40:25'),
(15, 1000, 'Cancelled', '2025-10-08 19:40:55'),
(16, 1000, 'Cancelled', '2025-10-08 19:42:13'),
(17, 1000, 'Cancelled', '2025-10-08 19:43:01'),
(18, 1000, 'Cancelled', '2025-10-08 20:01:48'),
(19, 1000, 'Cancelled', '2025-10-08 20:02:17'),
(20, 1000, 'Cancelled', '2025-10-09 19:52:55'),
(21, 1000, 'Cancelled', '2025-10-10 13:13:07'),
(22, 1000, 'Cancelled', '2025-10-10 16:51:25'),
(23, 1000, 'Cancelled', '2025-10-10 16:52:54'),
(24, 1000, 'Cancelled', '2025-10-10 18:14:46'),
(25, 1000, 'Cancelled', '2025-10-10 19:22:24'),
(26, 1000, 'Cancelled', '2025-10-10 19:52:00'),
(27, 1000, 'Cancelled', '2025-10-11 04:40:33'),
(28, 1000, 'Cancelled', '2025-10-11 13:05:44'),
(29, 1000, 'Cancelled', '2025-10-11 13:06:16'),
(30, 1000, 'Cancelled', '2025-10-11 13:58:08'),
(31, 1000, 'Cancelled', '2025-10-11 14:15:25'),
(32, 1000, 'Cancelled', '2025-10-11 14:33:08'),
(33, 1000, 'Cancelled', '2025-10-12 05:49:42'),
(34, 1000, 'Pending', '2025-10-12 05:56:02'),
(35, 1000, 'Pending', '2025-10-12 08:01:31'),
(36, 1000, 'Pending', '2025-10-12 08:01:47'),
(37, 1000, 'Pending', '2025-10-12 10:57:05'),
(38, 1000, 'Cancelled', '2025-10-12 10:57:42'),
(39, 1000, 'Pending', '2025-10-12 15:35:20');

-- --------------------------------------------------------

--
-- Table structure for table `paymentmethods`
--

CREATE TABLE `paymentmethods` (
  `method_id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `method_type` enum('Card','bKash') NOT NULL,
  `label` varchar(100) DEFAULT NULL,
  `account` varchar(50) DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `payment_id` int(11) NOT NULL,
  `order_id` int(11) DEFAULT NULL,
  `amount` decimal(10,2) NOT NULL,
  `payment_method` enum('Cash','Card','MobilePayment') NOT NULL,
  `payment_status` enum('Pending','Paid','Failed') DEFAULT 'Pending',
  `paid_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `payments`
--

INSERT INTO `payments` (`payment_id`, `order_id`, `amount`, `payment_method`, `payment_status`, `paid_at`) VALUES
(1, 1, 1000.00, 'MobilePayment', 'Paid', '2025-09-25 09:32:15'),
(2, 2, 150.00, 'MobilePayment', 'Paid', '2025-09-25 09:32:43'),
(3, 3, 1920.00, 'Card', 'Paid', '2025-09-25 09:34:16'),
(4, 4, 150.00, 'MobilePayment', 'Paid', '2025-09-25 09:34:34'),
(5, 5, 150.00, 'Cash', 'Paid', '2025-09-25 09:34:46'),
(6, 6, 630.00, 'Card', 'Paid', '2025-10-07 19:04:57'),
(7, 7, 600.00, 'Cash', 'Paid', '2025-10-08 10:43:56'),
(8, 8, 150.00, 'Card', 'Paid', '2025-10-08 10:44:11'),
(9, 9, 180.00, 'Card', 'Paid', '2025-10-08 18:21:04'),
(10, 18, 330.00, 'Card', 'Paid', '2025-10-08 20:01:48'),
(11, 19, 150.00, 'MobilePayment', 'Paid', '2025-10-08 20:02:17'),
(12, 20, 760.00, 'Card', 'Paid', '2025-10-09 19:52:55'),
(13, 21, 550.00, 'Card', 'Paid', '2025-10-10 13:13:07'),
(14, 22, 150.00, 'Card', 'Paid', '2025-10-10 16:51:25'),
(15, 23, 150.00, 'Card', 'Paid', '2025-10-10 16:52:54'),
(16, 24, 150.00, 'Card', 'Paid', '2025-10-10 18:14:46'),
(17, 25, 150.00, 'Cash', 'Paid', '2025-10-10 19:22:24'),
(18, 26, 150.00, 'Card', 'Paid', '2025-10-10 19:52:00'),
(19, 27, 150.00, 'Card', 'Paid', '2025-10-11 04:40:33'),
(20, 28, 150.00, 'Cash', 'Paid', '2025-10-11 13:05:44'),
(21, 29, 150.00, 'MobilePayment', 'Paid', '2025-10-11 13:06:16'),
(22, 30, 150.00, 'MobilePayment', 'Paid', '2025-10-11 13:58:08'),
(23, 31, 150.00, 'Card', 'Paid', '2025-10-11 14:15:25'),
(24, 32, 150.00, 'Cash', 'Paid', '2025-10-11 14:33:08'),
(25, 33, 450.00, 'Cash', 'Paid', '2025-10-12 05:49:42'),
(26, 34, 150.00, 'Card', 'Paid', '2025-10-12 05:56:02'),
(27, 35, 150.00, 'Card', 'Paid', '2025-10-12 08:01:31'),
(28, 36, 150.00, 'Card', 'Paid', '2025-10-12 08:01:47'),
(29, 37, 150.00, 'Card', 'Paid', '2025-10-12 10:57:05'),
(30, 38, 450.00, 'Cash', 'Paid', '2025-10-12 10:57:42'),
(31, 39, 300.00, 'Card', 'Paid', '2025-10-12 15:35:20');

-- --------------------------------------------------------

--
-- Table structure for table `reports`
--

CREATE TABLE `reports` (
  `report_id` int(11) NOT NULL,
  `generated_by` int(11) DEFAULT NULL,
  `report_type` varchar(100) DEFAULT NULL,
  `generated_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `content` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('Admin','Manager','Customer') NOT NULL,
  `security_question` varchar(255) DEFAULT NULL,
  `security_answer` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `username`, `email`, `password`, `role`, `security_question`, `security_answer`, `created_at`) VALUES
(1000, 'sad', 'sad@gmail.com', '1234', 'Customer', 'who', 'me', '2025-09-24 05:29:20'),
(1001, 's', 'sa@gmail.com', '1234', 'Customer', '1', '1', '2025-09-24 06:46:58'),
(1002, 'sa', 's@gmail.com', '1234', 'Customer', 'What was the name of your first pet?', 'dog', '2025-09-24 10:55:45'),
(1003, 'sadman', 'sad@gamil.com', '1234', 'Customer', 'What city were you born in?', 'dhaka', '2025-10-04 06:26:03'),
(1004, 'testadmin', 'testadmin@gmail.com', 'admin123', 'Admin', 'Are you admin?', 'Yes', '2025-10-12 15:38:12'),
(1008, 'admin', 'admin@gmail.com', 'admin123', 'Admin', 'What city were you born in?', 'dhaka', '2025-10-12 15:41:46');

--
-- Triggers `users`
--
DELIMITER $$
CREATE TRIGGER `after_user_insert` AFTER INSERT ON `users` FOR EACH ROW BEGIN
    IF NEW.role = 'Admin' THEN
        INSERT INTO Admins (admin_id) VALUES (NEW.user_id);
    ELSEIF NEW.role = 'Manager' THEN
        INSERT INTO Managers (manager_id, salary) VALUES (NEW.user_id, 0);
    ELSEIF NEW.role = 'Customer' THEN
        INSERT INTO Customers (customer_id) VALUES (NEW.user_id);
    END IF;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `after_user_update` AFTER UPDATE ON `users` FOR EACH ROW BEGIN
    IF OLD.role <> NEW.role THEN
        IF OLD.role = 'Admin' THEN
            DELETE FROM Admins WHERE admin_id = OLD.user_id;
        ELSEIF OLD.role = 'Manager' THEN
            DELETE FROM Managers WHERE manager_id = OLD.user_id;
        ELSEIF OLD.role = 'Customer' THEN
            DELETE FROM Customers WHERE customer_id = OLD.user_id;
        END IF;

        IF NEW.role = 'Admin' THEN
            INSERT INTO Admins (admin_id) VALUES (NEW.user_id);
        ELSEIF NEW.role = 'Manager' THEN
            INSERT INTO Managers (manager_id, salary) VALUES (NEW.user_id, 0);
        ELSEIF NEW.role = 'Customer' THEN
            INSERT INTO Customers (customer_id) VALUES (NEW.user_id);
        END IF;
    END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `vendors`
--

CREATE TABLE `vendors` (
  `vendor_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `contact_info` text DEFAULT NULL,
  `contract_details` text DEFAULT NULL,
  `managed_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `wallets`
--

CREATE TABLE `wallets` (
  `customer_id` int(11) NOT NULL,
  `balance` decimal(10,2) NOT NULL DEFAULT 0.00,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `wallettransactions`
--

CREATE TABLE `wallettransactions` (
  `txn_id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `txn_type` enum('ADD','DEDUCT') NOT NULL,
  `note` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`admin_id`);

--
-- Indexes for table `coupons`
--
ALTER TABLE `coupons`
  ADD PRIMARY KEY (`coupon_id`),
  ADD UNIQUE KEY `code` (`code`),
  ADD KEY `created_by` (`created_by`);

--
-- Indexes for table `customercoupons`
--
ALTER TABLE `customercoupons`
  ADD PRIMARY KEY (`customer_coupon_id`),
  ADD KEY `customer_id` (`customer_id`),
  ADD KEY `coupon_id` (`coupon_id`),
  ADD KEY `applied_order_id` (`applied_order_id`);

--
-- Indexes for table `customerpreferences`
--
ALTER TABLE `customerpreferences`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_pref_per_customer_item` (`customer_id`,`item_name`);

--
-- Indexes for table `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`customer_id`);

--
-- Indexes for table `inventory`
--
ALTER TABLE `inventory`
  ADD PRIMARY KEY (`inventory_id`),
  ADD KEY `menu_item_id` (`menu_item_id`);

--
-- Indexes for table `managers`
--
ALTER TABLE `managers`
  ADD PRIMARY KEY (`manager_id`);

--
-- Indexes for table `mealpreferences`
--
ALTER TABLE `mealpreferences`
  ADD PRIMARY KEY (`preference_id`),
  ADD KEY `customer_id` (`customer_id`);

--
-- Indexes for table `menuitems`
--
ALTER TABLE `menuitems`
  ADD PRIMARY KEY (`menu_item_id`),
  ADD KEY `vendor_id` (`vendor_id`),
  ADD KEY `managed_by` (`managed_by`);

--
-- Indexes for table `orderitems`
--
ALTER TABLE `orderitems`
  ADD PRIMARY KEY (`order_item_id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `menu_item_id` (`menu_item_id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`order_id`),
  ADD KEY `customer_id` (`customer_id`);

--
-- Indexes for table `paymentmethods`
--
ALTER TABLE `paymentmethods`
  ADD PRIMARY KEY (`method_id`),
  ADD KEY `customer_id` (`customer_id`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`payment_id`),
  ADD KEY `order_id` (`order_id`);

--
-- Indexes for table `reports`
--
ALTER TABLE `reports`
  ADD PRIMARY KEY (`report_id`),
  ADD KEY `generated_by` (`generated_by`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `vendors`
--
ALTER TABLE `vendors`
  ADD PRIMARY KEY (`vendor_id`),
  ADD KEY `managed_by` (`managed_by`);

--
-- Indexes for table `wallets`
--
ALTER TABLE `wallets`
  ADD PRIMARY KEY (`customer_id`);

--
-- Indexes for table `wallettransactions`
--
ALTER TABLE `wallettransactions`
  ADD PRIMARY KEY (`txn_id`),
  ADD KEY `customer_id` (`customer_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `coupons`
--
ALTER TABLE `coupons`
  MODIFY `coupon_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `customercoupons`
--
ALTER TABLE `customercoupons`
  MODIFY `customer_coupon_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `customerpreferences`
--
ALTER TABLE `customerpreferences`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `inventory`
--
ALTER TABLE `inventory`
  MODIFY `inventory_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `mealpreferences`
--
ALTER TABLE `mealpreferences`
  MODIFY `preference_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `menuitems`
--
ALTER TABLE `menuitems`
  MODIFY `menu_item_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `orderitems`
--
ALTER TABLE `orderitems`
  MODIFY `order_item_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=59;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `order_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT for table `paymentmethods`
--
ALTER TABLE `paymentmethods`
  MODIFY `method_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `payment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT for table `reports`
--
ALTER TABLE `reports`
  MODIFY `report_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1009;

--
-- AUTO_INCREMENT for table `vendors`
--
ALTER TABLE `vendors`
  MODIFY `vendor_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `wallettransactions`
--
ALTER TABLE `wallettransactions`
  MODIFY `txn_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `admins`
--
ALTER TABLE `admins`
  ADD CONSTRAINT `admins_ibfk_1` FOREIGN KEY (`admin_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `coupons`
--
ALTER TABLE `coupons`
  ADD CONSTRAINT `coupons_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `managers` (`manager_id`) ON DELETE SET NULL;

--
-- Constraints for table `customercoupons`
--
ALTER TABLE `customercoupons`
  ADD CONSTRAINT `customercoupons_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`customer_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `customercoupons_ibfk_2` FOREIGN KEY (`coupon_id`) REFERENCES `coupons` (`coupon_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `customercoupons_ibfk_3` FOREIGN KEY (`applied_order_id`) REFERENCES `orders` (`order_id`) ON DELETE SET NULL;

--
-- Constraints for table `customers`
--
ALTER TABLE `customers`
  ADD CONSTRAINT `customers_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `inventory`
--
ALTER TABLE `inventory`
  ADD CONSTRAINT `inventory_ibfk_1` FOREIGN KEY (`menu_item_id`) REFERENCES `menuitems` (`menu_item_id`) ON DELETE CASCADE;

--
-- Constraints for table `managers`
--
ALTER TABLE `managers`
  ADD CONSTRAINT `managers_ibfk_1` FOREIGN KEY (`manager_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `mealpreferences`
--
ALTER TABLE `mealpreferences`
  ADD CONSTRAINT `mealpreferences_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`customer_id`) ON DELETE CASCADE;

--
-- Constraints for table `menuitems`
--
ALTER TABLE `menuitems`
  ADD CONSTRAINT `menuitems_ibfk_1` FOREIGN KEY (`vendor_id`) REFERENCES `vendors` (`vendor_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `menuitems_ibfk_2` FOREIGN KEY (`managed_by`) REFERENCES `managers` (`manager_id`) ON DELETE SET NULL;

--
-- Constraints for table `orderitems`
--
ALTER TABLE `orderitems`
  ADD CONSTRAINT `orderitems_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `orderitems_ibfk_2` FOREIGN KEY (`menu_item_id`) REFERENCES `menuitems` (`menu_item_id`) ON DELETE CASCADE;

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`customer_id`) ON DELETE CASCADE;

--
-- Constraints for table `paymentmethods`
--
ALTER TABLE `paymentmethods`
  ADD CONSTRAINT `paymentmethods_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`customer_id`) ON DELETE CASCADE;

--
-- Constraints for table `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `payments_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`) ON DELETE CASCADE;

--
-- Constraints for table `reports`
--
ALTER TABLE `reports`
  ADD CONSTRAINT `reports_ibfk_1` FOREIGN KEY (`generated_by`) REFERENCES `users` (`user_id`) ON DELETE SET NULL;

--
-- Constraints for table `vendors`
--
ALTER TABLE `vendors`
  ADD CONSTRAINT `vendors_ibfk_1` FOREIGN KEY (`managed_by`) REFERENCES `managers` (`manager_id`) ON DELETE SET NULL;

--
-- Constraints for table `wallets`
--
ALTER TABLE `wallets`
  ADD CONSTRAINT `wallets_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`customer_id`) ON DELETE CASCADE;

--
-- Constraints for table `wallettransactions`
--
ALTER TABLE `wallettransactions`
  ADD CONSTRAINT `wallettransactions_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`customer_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

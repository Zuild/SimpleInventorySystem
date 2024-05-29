-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 29, 2024 at 03:34 AM
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
-- Database: `supplyinventorydb`
--

-- --------------------------------------------------------

--
-- Table structure for table `product`
--

CREATE TABLE `product` (
  `p_id` int(11) NOT NULL,
  `p_image` varchar(256) NOT NULL,
  `p_name` varchar(255) NOT NULL,
  `p_price` decimal(20,2) NOT NULL,
  `p_stocks` int(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `product`
--

INSERT INTO `product` (`p_id`, `p_image`, `p_name`, `p_price`, `p_stocks`) VALUES
(67, 'images/GPBXHFX7/download.jpg', 'bondpaper ream', 290.00, 30),
(69, 'images/9kms8f0p/eraser.jpg', 'eraser', 8.00, 12342),
(70, 'images/xh5npm9I/ruler.jpg', 'ruler', 30.20, 12441),
(71, 'images/QOanVKuC/protrator.jpg', 'protractor', 25.00, 2156),
(72, 'images/r6W4hx7z/constraction paper.jpg', 'constraction paper', 2.50, 7382),
(73, 'images/4CQWPlXw/glue.jpg', 'Glue', 40.00, 212),
(74, 'images/FsrGp1aT/tape.jpg', 'scotch tape', 23.00, 4212),
(75, 'images/ry5HKTd0/stapler.jpg', 'stapler', 65.00, 121);

--
-- Triggers `product`
--
DELIMITER $$
CREATE TRIGGER `before_product_delete` BEFORE DELETE ON `product` FOR EACH ROW BEGIN
    DECLARE user_id INT;
    DECLARE user_name VARCHAR(255);

    -- Get the current user details from the session
    SET user_id = @current_user_id;
    SET user_name = @current_user_name;

    INSERT INTO product_audit_log (user_id, user_name, action, table_name, column_name, old_value, new_value)
    VALUES (user_id, user_name, 'DELETE', 'product', 'p_name', OLD.p_name, NULL);

    INSERT INTO product_audit_log (user_id, user_name, action, table_name, column_name, old_value, new_value)
    VALUES (user_id, user_name, 'DELETE', 'product', 'p_price', OLD.p_price, NULL);

    INSERT INTO product_audit_log (user_id, user_name, action, table_name, column_name, old_value, new_value)
    VALUES (user_id, user_name, 'DELETE', 'product', 'p_stocks', OLD.p_stocks, NULL);

END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `before_product_insert` BEFORE INSERT ON `product` FOR EACH ROW BEGIN
    DECLARE user_id INT;
    DECLARE user_name VARCHAR(255);

    -- Get the current user details from the session
    SET user_id = @current_user_id;
    SET user_name = @current_user_name;

    INSERT INTO product_audit_log (user_id, user_name, action, table_name, column_name, old_value, new_value)
    VALUES (user_id, user_name, 'INSERT', 'product', 'p_name', NULL, NEW.p_name);

    INSERT INTO product_audit_log (user_id, user_name, action, table_name, column_name, old_value, new_value)
    VALUES (user_id, user_name, 'INSERT', 'product', 'p_price', NULL, NEW.p_price);

    INSERT INTO product_audit_log (user_id, user_name, action, table_name, column_name, old_value, new_value)
    VALUES (user_id, user_name, 'INSERT', 'product', 'p_stocks', NULL, NEW.p_stocks);

END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `before_product_update` BEFORE UPDATE ON `product` FOR EACH ROW BEGIN
    DECLARE user_id INT;
    DECLARE user_name VARCHAR(255);

    -- Get the current user details from the session
    SET user_id = @current_user_id;
    SET user_name = @current_user_name;

    IF OLD.p_name <> NEW.p_name THEN
        INSERT INTO product_audit_log (user_id, user_name, action, table_name, column_name, old_value, new_value)
        VALUES (user_id, user_name, 'UPDATE', 'product', 'p_name', OLD.p_name, NEW.p_name);
    END IF;

    IF OLD.p_price <> NEW.p_price THEN
        INSERT INTO product_audit_log (user_id, user_name, action, table_name, column_name, old_value, new_value)
        VALUES (user_id, user_name, 'UPDATE', 'product', 'p_price', OLD.p_price, NEW.p_price);
    END IF;

    IF OLD.p_stocks <> NEW.p_stocks THEN
        INSERT INTO product_audit_log (user_id, user_name, action, table_name, column_name, old_value, new_value)
        VALUES (user_id, user_name, 'UPDATE', 'product', 'p_stocks', OLD.p_stocks, NEW.p_stocks);
    END IF;

END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `product_audit_log`
--

CREATE TABLE `product_audit_log` (
  `audit_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `user_name` varchar(255) DEFAULT NULL,
  `action` varchar(50) DEFAULT NULL,
  `table_name` varchar(50) DEFAULT NULL,
  `column_name` varchar(50) DEFAULT NULL,
  `old_value` text DEFAULT NULL,
  `new_value` text DEFAULT NULL,
  `change_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `product_audit_log`
--

INSERT INTO `product_audit_log` (`audit_id`, `user_id`, `user_name`, `action`, `table_name`, `column_name`, `old_value`, `new_value`, `change_date`) VALUES
(1, NULL, NULL, 'INSERT', 'product', 'p_name', NULL, 'asdfadsf', '2024-05-28 00:37:57'),
(2, NULL, NULL, 'INSERT', 'product', 'p_price', NULL, '6.30', '2024-05-28 00:37:57'),
(3, NULL, NULL, 'INSERT', 'product', 'p_stocks', NULL, '232323', '2024-05-28 00:37:57'),
(4, 25, 'admin22', 'UPDATE', 'product', 'p_price', '250.75', '1000.60', '2024-05-28 00:42:00'),
(5, 25, 'admin22', 'UPDATE', 'product', 'p_stocks', '100', '102', '2024-05-28 00:42:00'),
(6, NULL, NULL, 'INSERT', 'product', 'p_name', NULL, 'dfasdfasdfadsf', '2024-05-28 00:42:45'),
(7, NULL, NULL, 'INSERT', 'product', 'p_price', NULL, '233232.00', '2024-05-28 00:42:45'),
(8, NULL, NULL, 'INSERT', 'product', 'p_stocks', NULL, '23253535', '2024-05-28 00:42:45'),
(9, NULL, NULL, 'INSERT', 'product', 'p_name', NULL, 'asdfasdf', '2024-05-28 00:45:01'),
(10, NULL, NULL, 'INSERT', 'product', 'p_price', NULL, '34234235.00', '2024-05-28 00:45:01'),
(11, NULL, NULL, 'INSERT', 'product', 'p_stocks', NULL, '234324234', '2024-05-28 00:45:01'),
(12, NULL, NULL, 'DELETE', 'product', 'p_name', 'bondpaper ream', NULL, '2024-05-28 00:47:43'),
(13, NULL, NULL, 'DELETE', 'product', 'p_price', '1000.60', NULL, '2024-05-28 00:47:43'),
(14, NULL, NULL, 'DELETE', 'product', 'p_stocks', '102', NULL, '2024-05-28 00:47:43'),
(15, NULL, NULL, 'DELETE', 'product', 'p_name', 'asdfadsf', NULL, '2024-05-28 00:52:02'),
(16, NULL, NULL, 'DELETE', 'product', 'p_price', '6.30', NULL, '2024-05-28 00:52:02'),
(17, NULL, NULL, 'DELETE', 'product', 'p_stocks', '232323', NULL, '2024-05-28 00:52:02'),
(18, NULL, NULL, 'DELETE', 'product', 'p_name', 'dfasdfasdfadsf', NULL, '2024-05-28 04:58:41'),
(19, NULL, NULL, 'DELETE', 'product', 'p_price', '233232.00', NULL, '2024-05-28 04:58:41'),
(20, NULL, NULL, 'DELETE', 'product', 'p_stocks', '23253535', NULL, '2024-05-28 04:58:41'),
(21, NULL, NULL, 'INSERT', 'product', 'p_name', NULL, 'asdfasdfadf', '2024-05-28 05:00:51'),
(22, NULL, NULL, 'INSERT', 'product', 'p_price', NULL, '32323.00', '2024-05-28 05:00:51'),
(23, NULL, NULL, 'INSERT', 'product', 'p_stocks', NULL, '22323', '2024-05-28 05:00:51'),
(24, 25, 'admin22', 'DELETE', 'product', 'p_name', 'asdfasdfadf', NULL, '2024-05-28 05:01:06'),
(25, 25, 'admin22', 'DELETE', 'product', 'p_price', '32323.00', NULL, '2024-05-28 05:01:06'),
(26, 25, 'admin22', 'DELETE', 'product', 'p_stocks', '22323', NULL, '2024-05-28 05:01:06'),
(27, 25, 'admin22', 'INSERT', 'product', 'p_name', NULL, 'asdfadf', '2024-05-28 05:04:07'),
(28, 25, 'admin22', 'INSERT', 'product', 'p_price', NULL, '12121212.00', '2024-05-28 05:04:07'),
(29, 25, 'admin22', 'INSERT', 'product', 'p_stocks', NULL, '12131313', '2024-05-28 05:04:07'),
(30, 25, 'admin22', 'UPDATE', 'product', 'p_name', 'asdfasdf', 'adsfadsfasdf', '2024-05-28 05:06:48'),
(31, 25, 'admin22', 'INSERT', 'product', 'p_name', NULL, 'sfgdsfdg', '2024-05-28 05:19:14'),
(32, 25, 'admin22', 'INSERT', 'product', 'p_price', NULL, '43454.00', '2024-05-28 05:19:14'),
(33, 25, 'admin22', 'INSERT', 'product', 'p_stocks', NULL, '324234', '2024-05-28 05:19:14'),
(34, 25, 'admin22', 'DELETE', 'product', 'p_name', 'adsfadsfasdf', NULL, '2024-05-28 05:54:31'),
(35, 25, 'admin22', 'DELETE', 'product', 'p_price', '34234235.00', NULL, '2024-05-28 05:54:31'),
(36, 25, 'admin22', 'DELETE', 'product', 'p_stocks', '234324234', NULL, '2024-05-28 05:54:31'),
(37, 25, 'admin22', 'UPDATE', 'product', 'p_name', 'asdfadf', 'ggggg', '2024-05-28 05:54:56'),
(38, 25, 'admin22', 'DELETE', 'product', 'p_name', 'ggggg', NULL, '2024-05-28 05:55:06'),
(39, 25, 'admin22', 'DELETE', 'product', 'p_price', '12121212.00', NULL, '2024-05-28 05:55:06'),
(40, 25, 'admin22', 'DELETE', 'product', 'p_stocks', '12131313', NULL, '2024-05-28 05:55:06'),
(41, 25, 'admin22', 'DELETE', 'product', 'p_name', 'sfgdsfdg', NULL, '2024-05-28 05:55:08'),
(42, 25, 'admin22', 'DELETE', 'product', 'p_price', '43454.00', NULL, '2024-05-28 05:55:08'),
(43, 25, 'admin22', 'DELETE', 'product', 'p_stocks', '324234', NULL, '2024-05-28 05:55:08'),
(44, 25, 'admin22', 'INSERT', 'product', 'p_name', NULL, 'bondpaper ream', '2024-05-28 05:55:56'),
(45, 25, 'admin22', 'INSERT', 'product', 'p_price', NULL, '250.75', '2024-05-28 05:55:56'),
(46, 25, 'admin22', 'INSERT', 'product', 'p_stocks', NULL, '30', '2024-05-28 05:55:56'),
(47, 27, 'admin', 'UPDATE', 'product', 'p_price', '250.75', '250.80', '2024-05-28 05:57:08'),
(48, 27, 'zuild', 'UPDATE', 'product', 'p_price', '250.80', '230.80', '2024-05-28 08:17:40'),
(49, 27, 'zuild', 'UPDATE', 'product', 'p_price', '230.80', '290.80', '2024-05-28 08:18:05'),
(50, 27, 'zuild', 'UPDATE', 'product', 'p_name', 'Bondpaper Ream', ',jhg,jhgjhg', '2024-05-28 08:20:12'),
(51, 27, 'zuild', 'UPDATE', 'product', 'p_name', ',jhg,jhgjhg', 'Bondpaper Ream', '2024-05-28 08:20:44'),
(52, 27, 'zuild', 'DELETE', 'product', 'p_name', 'Bondpaper Ream', NULL, '2024-05-28 08:30:19'),
(53, 27, 'zuild', 'DELETE', 'product', 'p_price', '290.80', NULL, '2024-05-28 08:30:19'),
(54, 27, 'zuild', 'DELETE', 'product', 'p_stocks', '30', NULL, '2024-05-28 08:30:19'),
(55, 27, 'zuild', 'INSERT', 'product', 'p_name', NULL, 'bondpaper ream', '2024-05-28 08:30:40'),
(56, 27, 'zuild', 'INSERT', 'product', 'p_price', NULL, '250.75', '2024-05-28 08:30:40'),
(57, 27, 'zuild', 'INSERT', 'product', 'p_stocks', NULL, '30', '2024-05-28 08:30:40'),
(58, 27, 'zuild', 'INSERT', 'product', 'p_name', NULL, 'Pencil', '2024-05-28 08:59:05'),
(59, 27, 'zuild', 'INSERT', 'product', 'p_price', NULL, '10.30', '2024-05-28 08:59:05'),
(60, 27, 'zuild', 'INSERT', 'product', 'p_stocks', NULL, '1000', '2024-05-28 08:59:05'),
(61, 27, 'zuild', 'INSERT', 'product', 'p_name', NULL, 'eraser', '2024-05-28 09:00:06'),
(62, 27, 'zuild', 'INSERT', 'product', 'p_price', NULL, '8.00', '2024-05-28 09:00:06'),
(63, 27, 'zuild', 'INSERT', 'product', 'p_stocks', NULL, '12342', '2024-05-28 09:00:06'),
(64, 27, 'zuild', 'INSERT', 'product', 'p_name', NULL, 'ruler', '2024-05-28 09:00:48'),
(65, 27, 'zuild', 'INSERT', 'product', 'p_price', NULL, '30.20', '2024-05-28 09:00:48'),
(66, 27, 'zuild', 'INSERT', 'product', 'p_stocks', NULL, '12441', '2024-05-28 09:00:48'),
(67, 27, 'zuild', 'INSERT', 'product', 'p_name', NULL, 'protractor', '2024-05-28 09:02:02'),
(68, 27, 'zuild', 'INSERT', 'product', 'p_price', NULL, '27.00', '2024-05-28 09:02:02'),
(69, 27, 'zuild', 'INSERT', 'product', 'p_stocks', NULL, '2156', '2024-05-28 09:02:02'),
(70, 27, 'zuild', 'INSERT', 'product', 'p_name', NULL, 'constraction paper', '2024-05-28 09:02:54'),
(71, 27, 'zuild', 'INSERT', 'product', 'p_price', NULL, '2.50', '2024-05-28 09:02:54'),
(72, 27, 'zuild', 'INSERT', 'product', 'p_stocks', NULL, '7382', '2024-05-28 09:02:54'),
(73, 27, 'zuild', 'INSERT', 'product', 'p_name', NULL, 'Glue', '2024-05-28 09:03:50'),
(74, 27, 'zuild', 'INSERT', 'product', 'p_price', NULL, '40.00', '2024-05-28 09:03:50'),
(75, 27, 'zuild', 'INSERT', 'product', 'p_stocks', NULL, '212', '2024-05-28 09:03:50'),
(76, 27, 'zuild', 'INSERT', 'product', 'p_name', NULL, 'scotch tape', '2024-05-28 09:04:40'),
(77, 27, 'zuild', 'INSERT', 'product', 'p_price', NULL, '23.00', '2024-05-28 09:04:40'),
(78, 27, 'zuild', 'INSERT', 'product', 'p_stocks', NULL, '4212', '2024-05-28 09:04:40'),
(79, 27, 'zuild', 'UPDATE', 'product', 'p_price', '250.75', '250.00', '2024-05-28 09:05:19'),
(80, 27, 'zuild', 'INSERT', 'product', 'p_name', NULL, 'stapler', '2024-05-28 10:40:39'),
(81, 27, 'zuild', 'INSERT', 'product', 'p_price', NULL, '65.00', '2024-05-28 10:40:39'),
(82, 27, 'zuild', 'INSERT', 'product', 'p_stocks', NULL, '121', '2024-05-28 10:40:39'),
(83, 27, 'zuild', 'UPDATE', 'product', 'p_price', '27.00', '25.00', '2024-05-28 10:41:19'),
(84, 27, 'zuild', 'UPDATE', 'product', 'p_price', '250.00', '260.00', '2024-05-28 13:57:12'),
(85, 27, 'zuild', 'UPDATE', 'product', 'p_price', '260.00', '290.00', '2024-05-28 13:57:56'),
(86, 27, 'zuild', 'DELETE', 'product', 'p_name', 'Pencil', NULL, '2024-05-28 13:58:22'),
(87, 27, 'zuild', 'DELETE', 'product', 'p_price', '10.30', NULL, '2024-05-28 13:58:22'),
(88, 27, 'zuild', 'DELETE', 'product', 'p_stocks', '1000', NULL, '2024-05-28 13:58:22');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(20) NOT NULL,
  `user_name` varchar(50) NOT NULL,
  `user_password` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `user_name`, `user_password`) VALUES
(25, 'admin22', '$2y$10$9kgE.nN6jWuGY4L4fq5WSu.Z0e5W5PB7GIiZ7krK.NrDxSZ.YUOVm'),
(26, 'admin1', '$2y$10$jndqdhgLoVMvv9TL4LcbfOxAhGi1ssvk1iY/gJQswVw4EpUXeQ1fa'),
(27, 'zuild', '$2y$10$MrNlwPhISTaspN7O/E4BSubwcf.cebRwyJNvXMKWrtvqjgz.JVfiu');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `product`
--
ALTER TABLE `product`
  ADD PRIMARY KEY (`p_id`);

--
-- Indexes for table `product_audit_log`
--
ALTER TABLE `product_audit_log`
  ADD PRIMARY KEY (`audit_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `product`
--
ALTER TABLE `product`
  MODIFY `p_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=76;

--
-- AUTO_INCREMENT for table `product_audit_log`
--
ALTER TABLE `product_audit_log`
  MODIFY `audit_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=89;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

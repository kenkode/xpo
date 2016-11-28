-- phpMyAdmin SQL Dump
-- version 4.5.4.1deb2ubuntu2
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Nov 28, 2016 at 10:37 AM
-- Server version: 10.1.13-MariaDB-1~wily
-- PHP Version: 5.6.28-1+deb.sury.org~xenial+1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `xara`
--

-- --------------------------------------------------------

--
-- Table structure for table `erporderitems`
--

DROP TABLE IF EXISTS `erporderitems`;
CREATE TABLE `erporderitems` (
  `id` int(10) UNSIGNED NOT NULL,
  `item_id` int(10) UNSIGNED NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT '1',
  `issued_by` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `date_of_issue` date DEFAULT NULL,
  `date_of_return` date DEFAULT NULL,
  `status` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `is_returned` tinyint(1) NOT NULL DEFAULT '0',
  `rate` double NOT NULL DEFAULT '0',
  `duration` int(11) DEFAULT NULL,
  `erporder_id` int(10) UNSIGNED NOT NULL,
  `price` double DEFAULT NULL,
  `client_discount` double NOT NULL DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

DROP TABLE IF EXISTS `payments`;
CREATE TABLE `payments` (
  `id` int(10) UNSIGNED NOT NULL,
  `client_id` int(10) UNSIGNED NOT NULL,
  `account_id` int(10) UNSIGNED NOT NULL,
  `erporder_id` int(10) UNSIGNED NOT NULL,
  `date` date DEFAULT NULL,
  `amount_paid` double NOT NULL DEFAULT '0',
  `received_by` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `paymentmethod_id` int(10) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ------------------------------------------------------------

--
-- Table structure for table `erporders`
--

DROP TABLE IF EXISTS `erporders`;
CREATE TABLE `erporders` (
  `id` int(10) UNSIGNED NOT NULL,
  `client_id` int(10) UNSIGNED NOT NULL,
  `date` date DEFAULT NULL,
  `status` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'new',
  `comment` text COLLATE utf8_unicode_ci NOT NULL,
  `total_amount` double DEFAULT NULL,
  `discount_amount` double DEFAULT NULL,
  `type` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `is_lease` tinyint(1) NOT NULL DEFAULT '0',
  `payment_type` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `order_number` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `inv_number` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `due_date` date DEFAULT NULL,
  `ordered_by` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Indexes for dumped tables
--

--
-- Indexes for table `erporderitems`
--
ALTER TABLE `erporderitems`
  ADD PRIMARY KEY (`id`),
  ADD KEY `erporderitems_item_id_foreign` (`item_id`),
  ADD KEY `erporderitems_erporder_id_foreign` (`erporder_id`);

--
-- Indexes for table `erporders`
--
ALTER TABLE `erporders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `erporders_client_id_foreign` (`client_id`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `payments_erporder_id_foreign` (`erporder_id`),
  ADD KEY `payments_paymentmethod_id_foreign` (`paymentmethod_id`),
  ADD KEY `payments_client_id_foreign` (`client_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `erporderitems`
--
ALTER TABLE `erporderitems`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=0;
--
-- AUTO_INCREMENT for table `erporders`
--
ALTER TABLE `erporders`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=0;
--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=0;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `erporderitems`
--
ALTER TABLE `erporderitems`
  ADD CONSTRAINT `erporderitems_erporder_id_foreign` FOREIGN KEY (`erporder_id`) REFERENCES `erporders` (`id`),
  ADD CONSTRAINT `erporderitems_item_id_foreign` FOREIGN KEY (`item_id`) REFERENCES `items` (`id`);

--
-- Constraints for table `erporders`
--
ALTER TABLE `erporders`
  ADD CONSTRAINT `erporders_client_id_foreign` FOREIGN KEY (`client_id`) REFERENCES `clients` (`id`);

--
-- Constraints for table `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `payments_client_id_foreign` FOREIGN KEY (`client_id`) REFERENCES `clients` (`id`),
  ADD CONSTRAINT `payments_erporder_id_foreign` FOREIGN KEY (`erporder_id`) REFERENCES `erporders` (`id`),
  ADD CONSTRAINT `payments_paymentmethod_id_foreign` FOREIGN KEY (`paymentmethod_id`) REFERENCES `paymentmethods` (`id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

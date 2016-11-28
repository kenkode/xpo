-- phpMyAdmin SQL Dump
-- version 4.5.4.1deb2ubuntu2
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Nov 27, 2016 at 08:02 PM
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
-- Table structure for table `claim_receipts`
--

-- DROP TABLE IF EXISTS `claim_receipts`;
CREATE TABLE `claim_receipts` (
  `id` int(10) UNSIGNED NOT NULL,
  `claim_id` int(10) UNSIGNED DEFAULT NULL,
  `receipt_from` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `transaction_date` date NOT NULL,
  `status` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `claim_receipt_items`
--

-- DROP TABLE IF EXISTS `claim_receipt_items`;
CREATE TABLE `claim_receipt_items` (
  `id` int(10) UNSIGNED NOT NULL,
  `claimReceiptID` int(10) UNSIGNED NOT NULL,
  `description` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `quantity` int(10) UNSIGNED NOT NULL,
  `unit_price` double NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `expense_claims`
--

-- DROP TABLE IF EXISTS `expense_claims`;
CREATE TABLE `expense_claims` (
  `id` int(10) UNSIGNED NOT NULL,
  `claimer` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `date_submitted` date NOT NULL,
  `status` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `claim_receipts`
--
ALTER TABLE `claim_receipts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `claim_receipt_items`
--
ALTER TABLE `claim_receipt_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `claim_receipt_items_claimreceiptid_foreign` (`claimReceiptID`);

--
-- Indexes for table `expense_claims`
--
ALTER TABLE `expense_claims`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `claim_receipts`
--
ALTER TABLE `claim_receipts`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=0;
--
-- AUTO_INCREMENT for table `claim_receipt_items`
--
ALTER TABLE `claim_receipt_items`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=0;
--
-- AUTO_INCREMENT for table `expense_claims`
--
ALTER TABLE `expense_claims`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=0;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `claim_receipt_items`
--
ALTER TABLE `claim_receipt_items`
  ADD CONSTRAINT `claim_receipt_items_claimreceiptid_foreign` FOREIGN KEY (`claimReceiptID`) REFERENCES `claim_receipts` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

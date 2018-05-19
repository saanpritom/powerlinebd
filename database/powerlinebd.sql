-- phpMyAdmin SQL Dump
-- version 4.7.9
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: May 19, 2018 at 07:26 PM
-- Server version: 10.1.31-MariaDB
-- PHP Version: 7.2.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `powerlinebd`
--

-- --------------------------------------------------------

--
-- Table structure for table `creation_details`
--

DROP TABLE IF EXISTS `creation_details`;
CREATE TABLE `creation_details` (
  `sl_num` int(11) NOT NULL,
  `timer_id` int(11) DEFAULT NULL,
  `creation_date` text COLLATE utf8_unicode_ci NOT NULL,
  `creation_time` text COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `creation_details`
--

INSERT INTO `creation_details` (`sl_num`, `timer_id`, `creation_date`, `creation_time`) VALUES
(1, 0, '2018/05/19', '10:02:12pm'),
(2, 1631707538, '2018/05/19', '10:03:14pm'),
(3, 139050527, '2018/05/19', '10:14:42pm'),
(4, 1037202936, '2018/05/19', '10:15:27pm'),
(5, 1887119786, '2018/05/19', '11:08:01pm'),
(6, 1308733153, '2018/05/19', '11:15:27pm'),
(7, 858683037, '2018/05/19', '11:16:06pm'),
(8, 433860748, '2018/05/19', '11:16:53pm'),
(9, 605584841, '2018/05/19', '11:20:32pm'),
(10, 2142371357, '2018/05/19', '11:21:29pm'),
(11, 1321687697, '2018/05/19', '11:24:58pm');

-- --------------------------------------------------------

--
-- Table structure for table `office_branch`
--

DROP TABLE IF EXISTS `office_branch`;
CREATE TABLE `office_branch` (
  `sl_num` bigint(20) UNSIGNED NOT NULL,
  `branch_id` int(11) DEFAULT NULL,
  `name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `address` text COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `contact_number` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `timer_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `office_branch`
--

INSERT INTO `office_branch` (`sl_num`, `branch_id`, `name`, `address`, `email`, `contact_number`, `timer_id`) VALUES
(1, 118750197, 'Collabo', 'road#08, house no#04(1st floor), Kallayanpur', 'pritom.saan@gmail.com', '433445', 1321687697);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `creation_details`
--
ALTER TABLE `creation_details`
  ADD PRIMARY KEY (`sl_num`);

--
-- Indexes for table `office_branch`
--
ALTER TABLE `office_branch`
  ADD UNIQUE KEY `sl_num` (`sl_num`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `creation_details`
--
ALTER TABLE `creation_details`
  MODIFY `sl_num` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `office_branch`
--
ALTER TABLE `office_branch`
  MODIFY `sl_num` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

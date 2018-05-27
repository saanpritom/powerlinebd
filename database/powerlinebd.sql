-- phpMyAdmin SQL Dump
-- version 4.7.9
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: May 27, 2018 at 11:48 AM
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
-- Table structure for table `admin_details`
--

DROP TABLE IF EXISTS `admin_details`;
CREATE TABLE `admin_details` (
  `sl_num` bigint(20) UNSIGNED NOT NULL,
  `admin_id` int(11) DEFAULT NULL,
  `branch_id` int(11) DEFAULT NULL,
  `admin_type` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `gender` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `designation` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `department` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `contact_number` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `timer_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `admin_details`
--

INSERT INTO `admin_details` (`sl_num`, `admin_id`, `branch_id`, `admin_type`, `name`, `gender`, `designation`, `department`, `contact_number`, `timer_id`) VALUES
(1, 898134556, 1568829822, 'finance_user', 'Test2', 'Male', 'Managing Director', 'Test Dept', '4554', 586380511),
(2, 1109885108, 118750197, 'finance_user', 'Test3', 'Male', 'Test Desig', 'Test Dept', '8778778', 555376270),
(3, 719546423, 118750197, 'finance_user', 'Test4', 'Male', 'Test Desig', 'Test Dept', '4444', 1301017427),
(7, 707485416, 118750197, 'finance_user', 'Test5', 'Male', 'Test Desig', 'Test Dept', '433445', 2085795140),
(11, 1754315326, 118750197, 'business_user', 'Test6', 'Female', 'Test Desig', 'Test Dept', '433445', 188365342),
(14, 1980363788, 118750197, 'finance_user', 'Pritom', 'Female', 'Administrator', 'IT', '01732844253', 2027745267),
(20, 802124940, 118750197, 'super_admin', 'Sam Bilings', 'Male', 'Executive', 'Communication', '01732844253', 271175730);

-- --------------------------------------------------------

--
-- Table structure for table `awb_details`
--

DROP TABLE IF EXISTS `awb_details`;
CREATE TABLE `awb_details` (
  `sl_num` bigint(20) UNSIGNED NOT NULL,
  `awb_id` int(11) NOT NULL,
  `mawb_id` int(11) NOT NULL,
  `timer_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

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
(11, 1321687697, '2018/05/19', '11:24:58pm'),
(12, 1421439282, '2018/05/20', '11:06:41am'),
(13, 1484654581, '2018/05/20', '12:31:51pm'),
(14, 367599021, '2018/05/20', '12:45:44pm'),
(15, 586380511, '2018/05/20', '12:52:12pm'),
(16, 555376270, '2018/05/20', '12:59:55pm'),
(17, 1918734750, '2018/05/20', '01:01:05pm'),
(18, 1301017427, '2018/05/20', '01:24:52pm'),
(19, 1445309191, '2018/05/20', '01:32:26pm'),
(20, 2026871876, '2018/05/20', '01:37:55pm'),
(21, 985514545, '2018/05/20', '01:41:06pm'),
(22, 866058002, '2018/05/20', '01:51:21pm'),
(23, 2145812881, '2018/05/20', '01:53:13pm'),
(24, 312287833, '2018/05/20', '02:05:42pm'),
(25, 1787788589, '2018/05/20', '02:11:24pm'),
(26, 1723131247, '2018/05/20', '02:12:36pm'),
(27, 1633394449, '2018/05/20', '02:16:05pm'),
(28, 1522018961, '2018/05/20', '02:53:23pm'),
(29, 803599642, '2018/05/20', '02:54:21pm'),
(30, 509233017, '2018/05/20', '02:57:18pm'),
(31, 2085795140, '2018/05/20', '02:58:18pm'),
(32, 292226647, '2018/05/20', '02:58:49pm'),
(33, 2097147760, '2018/05/20', '03:02:03pm'),
(34, 562118127, '2018/05/20', '03:02:40pm'),
(35, 554028150, '2018/05/20', '03:04:09pm'),
(36, 188365342, '2018/05/20', '03:05:24pm'),
(37, 571084362, '2018/05/20', '03:36:21pm'),
(38, 1701784671, '2018/05/20', '03:37:55pm'),
(39, 2027745267, '2018/05/20', '03:39:34pm'),
(40, 1830725635, '2018/05/20', '07:38:40pm'),
(41, 1245604994, '2018/05/20', '07:44:46pm'),
(42, 304374587, '2018/05/20', '07:55:43pm'),
(43, 1458050650, '2018/05/21', '12:00:31pm'),
(44, 1133780913, '2018/05/21', '12:18:18pm'),
(45, 1177349736, '2018/05/21', '12:21:42pm'),
(46, 271175730, '2018/05/21', '12:33:08pm'),
(47, 1146670803, '2018/05/21', '12:33:21pm'),
(48, 799062154, '2018/05/21', '12:59:57pm'),
(49, 790548326, '2018/05/21', '01:58:20pm'),
(50, 792891264, '2018/05/21', '02:00:25pm'),
(51, 2068944084, '2018/05/21', '02:01:10pm'),
(52, 1749755394, '2018/05/21', '02:04:41pm'),
(53, 1902291485, '2018/05/21', '02:13:39pm'),
(54, 598361139, '2018/05/21', '02:18:40pm'),
(55, 354349276, '2018/05/21', '02:26:17pm'),
(56, 664833858, '2018/05/21', '03:04:19pm'),
(57, 134753319, '2018/05/21', '03:05:31pm'),
(58, 2044480621, '2018/05/21', '03:06:34pm'),
(59, 1930386403, '2018/05/21', '03:33:15pm'),
(60, 1442475147, '2018/05/21', '03:33:21pm'),
(61, 2138774395, '2018/05/21', '03:33:29pm'),
(62, 215174318, '2018/05/21', '03:33:34pm'),
(63, 1767717756, '2018/05/21', '03:36:16pm'),
(64, 1168860784, '2018/05/21', '03:36:52pm'),
(65, 2085953132, '2018/05/21', '03:36:57pm'),
(66, 38635514, '2018/05/21', '03:39:59pm'),
(67, 395631740, '2018/05/21', '03:42:47pm'),
(68, 251259148, '2018/05/21', '03:45:21pm'),
(69, 10029548, '2018/05/21', '03:47:34pm'),
(70, 746002526, '2018/05/21', '06:41:52pm'),
(71, 1720653143, '2018/05/21', '07:09:57pm'),
(72, 845434693, '2018/05/22', '10:32:38am'),
(73, 1748674145, '2018/05/22', '03:37:58pm'),
(74, 2071377696, '2018/05/22', '03:39:45pm'),
(75, 117048446, '2018/05/22', '03:40:57pm'),
(76, 369165964, '2018/05/22', '03:43:14pm'),
(77, 1531602248, '2018/05/22', '03:44:22pm'),
(78, 788610104, '2018/05/22', '04:54:40pm'),
(79, 1276362415, '2018/05/22', '04:54:42pm'),
(80, 1175845374, '2018/05/22', '05:00:58pm'),
(81, 713333057, '2018/05/22', '05:01:10pm'),
(82, 455965985, '2018/05/22', '05:01:14pm'),
(83, 1995186924, '2018/05/22', '05:01:22pm'),
(84, 1850788022, '2018/05/22', '05:01:43pm'),
(85, 639599145, '2018/05/23', '09:11:52am'),
(86, 819251882, '2018/05/23', '09:12:56am'),
(87, 2140484956, '2018/05/23', '09:13:21am'),
(88, 1858644361, '2018/05/23', '01:30:26pm'),
(89, 2122586965, '2018/05/23', '05:04:51pm'),
(90, 1382251671, '2018/05/23', '05:05:16pm'),
(91, 335793212, '2018/05/23', '05:05:36pm'),
(92, 1841161016, '2018/05/23', '05:06:13pm'),
(93, 1833348794, '2018/05/23', '05:10:52pm'),
(94, 1395252202, '2018/05/24', '12:37:18pm'),
(95, 262767047, '2018/05/24', '02:26:55pm'),
(96, 9021298, '2018/05/24', '02:34:50pm'),
(97, 1097309706, '2018/05/24', '02:48:33pm'),
(98, 1219410633, '2018/05/24', '02:52:40pm'),
(99, 1940840930, '2018/05/24', '02:54:11pm'),
(100, 1387192553, '2018/05/24', '02:57:24pm'),
(101, 1104982099, '2018/05/24', '02:58:38pm'),
(102, 1094568949, '2018/05/24', '03:00:50pm'),
(103, 1164614997, '2018/05/24', '03:05:38pm'),
(104, 1685920936, '2018/05/24', '03:08:35pm'),
(105, 1183670899, '2018/05/24', '03:11:33pm'),
(106, 2130242835, '2018/05/24', '03:15:19pm'),
(107, 867404782, '2018/05/24', '03:17:13pm'),
(108, 1434665752, '2018/05/24', '03:18:47pm'),
(109, 1522252993, '2018/05/24', '03:22:23pm'),
(110, 32363201, '2018/05/24', '03:23:41pm'),
(111, 541394992, '2018/05/24', '03:24:57pm'),
(112, 1977976527, '2018/05/24', '03:26:02pm'),
(113, 1729784774, '2018/05/24', '03:27:53pm'),
(114, 465471830, '2018/05/24', '03:28:54pm'),
(115, 632792819, '2018/05/24', '04:26:34pm'),
(116, 1710325542, '2018/05/25', '04:11:36pm'),
(117, 2134612643, '2018/05/25', '05:01:48pm'),
(118, 1069449770, '2018/05/25', '05:11:14pm'),
(119, 1237659562, '2018/05/25', '05:13:52pm'),
(120, 2062217614, '2018/05/25', '05:15:15pm'),
(121, 1377784436, '2018/05/25', '05:16:14pm'),
(122, 1844716715, '2018/05/25', '10:31:39pm'),
(123, 274613471, '2018/05/27', '10:58:01am'),
(124, 2088566979, '2018/05/27', '11:25:33am'),
(125, 1666575281, '2018/05/27', '11:26:08am'),
(126, 955022688, '2018/05/27', '12:57:47pm'),
(127, 1725144227, '2018/05/27', '01:00:16pm'),
(128, 1501689331, '2018/05/27', '01:04:55pm'),
(129, 1029244925, '2018/05/27', '01:05:25pm'),
(130, 779947002, '2018/05/27', '01:05:55pm'),
(131, 234220200, '2018/05/27', '01:07:16pm'),
(132, 178678955, '2018/05/27', '01:07:27pm'),
(133, 152014375, '2018/05/27', '01:08:04pm'),
(134, 2064383634, '2018/05/27', '01:08:14pm'),
(135, 1355563348, '2018/05/27', '01:08:25pm'),
(136, 1205353701, '2018/05/27', '01:08:48pm'),
(137, 15040059, '2018/05/27', '03:07:56pm'),
(138, 1441400741, '2018/05/27', '03:08:35pm'),
(139, 695784981, '2018/05/27', '03:09:04pm'),
(140, 981273444, '2018/05/27', '03:47:10pm');

-- --------------------------------------------------------

--
-- Table structure for table `flight_awb_relation`
--

DROP TABLE IF EXISTS `flight_awb_relation`;
CREATE TABLE `flight_awb_relation` (
  `sl_num` bigint(20) UNSIGNED NOT NULL,
  `flight_id` int(11) NOT NULL,
  `awb_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `flight_details`
--

DROP TABLE IF EXISTS `flight_details`;
CREATE TABLE `flight_details` (
  `sl_num` bigint(20) UNSIGNED NOT NULL,
  `flight_number` text COLLATE utf8_unicode_ci NOT NULL,
  `flight_date` text COLLATE utf8_unicode_ci NOT NULL,
  `flight_time` text COLLATE utf8_unicode_ci NOT NULL,
  `timer_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `flight_details`
--

INSERT INTO `flight_details` (`sl_num`, `flight_number`, `flight_date`, `flight_time`, `timer_id`) VALUES
(1, 'MH3908898', '30/5/2018', '12:30', 2134612643),
(2, 'BG7899098', '30/5/2018', '12:30', 1069449770),
(3, 'PK4567889', '30/5/2018', '12:30', 1237659562),
(4, 'SL4556789', '30/5/2018', '12:30', 2062217614),
(5, 'UK3455678', '30/6/2018', '12:30', 1377784436);

-- --------------------------------------------------------

--
-- Table structure for table `freight_transport_relation`
--

DROP TABLE IF EXISTS `freight_transport_relation`;
CREATE TABLE `freight_transport_relation` (
  `sl_num` bigint(20) UNSIGNED NOT NULL,
  `trans_id` int(11) NOT NULL,
  `awb_id` int(11) NOT NULL,
  `shipper_id` int(11) NOT NULL,
  `consignee_id` int(11) NOT NULL,
  `origin_d_id` int(11) NOT NULL,
  `destination_d_id` int(11) NOT NULL,
  `bag_id` int(11) NOT NULL,
  `country_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `login_info`
--

DROP TABLE IF EXISTS `login_info`;
CREATE TABLE `login_info` (
  `sl_num` bigint(20) UNSIGNED NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `email` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `password` text COLLATE utf8_unicode_ci NOT NULL,
  `rememberme_token` text COLLATE utf8_unicode_ci NOT NULL,
  `user_type` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `login_status` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `login_info`
--

INSERT INTO `login_info` (`sl_num`, `user_id`, `email`, `password`, `rememberme_token`, `user_type`, `login_status`) VALUES
(1, 69725902, 'test@gmail.com', '$2y$10$sowG5bp2iX87smdZnJRp1ewgoIixhPMWw8g7qGcUXgMlyppnP59ba', '0', 'admin', 0),
(2, 719546423, 'test4@gmail.com', '$2y$10$2UcEjAF4vHhfyV9CYoQvCO./gk3dC1l4rzLYiOyT4/bQlPxcYutm.', '0', 'admin', 0),
(7, 707485416, 'test5@gmail.com', '$2y$10$vjk2smI3tPw/KGtMRAPRDOLTYQQcWYbbcTSeaJFaCt7cKinBCkV3y', '0', 'admin', 0),
(11, 1754315326, 'test6@gmail.com', '$2y$10$31abRq/GjBGm.wfCaMfeh.5Sm6FYlgFP.UHW.gOXo3lc7HXLpd8oe', '0', 'admin', 0),
(14, 1980363788, 'pritom@bylc.org', '$2y$10$mbqdbl8k97JXRkVhi6J6ReHACKQlCrRYRIqpq3si7e3hz5WeW9ER6', '0', 'admin', 0),
(20, 802124940, 'sam@gmail.com', '03ac674216f3e15c761ee1a5e255f067953623c8b388b4459e13f978d7c846f4', '0', 'admin', 0);

-- --------------------------------------------------------

--
-- Table structure for table `login_log`
--

DROP TABLE IF EXISTS `login_log`;
CREATE TABLE `login_log` (
  `sl_num` bigint(20) UNSIGNED NOT NULL,
  `user_id` int(11) NOT NULL,
  `public_ip` text COLLATE utf8_unicode_ci NOT NULL,
  `timer_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `login_log`
--

INSERT INTO `login_log` (`sl_num`, `user_id`, `public_ip`, `timer_id`) VALUES
(1, 802124940, '192.168.64.1', 799062154),
(2, 802124940, '192.168.64.1', 790548326),
(3, 802124940, '192.168.64.1', 792891264),
(4, 802124940, '192.168.64.1', 2068944084),
(5, 802124940, '192.168.64.1', 1749755394),
(6, 802124940, '192.168.64.1', 1902291485),
(7, 802124940, '192.168.64.1', 598361139),
(8, 802124940, '192.168.64.1', 354349276),
(9, 802124940, '192.168.64.1', 664833858),
(10, 802124940, '192.168.64.1', 134753319),
(11, 802124940, '192.168.64.1', 2044480621),
(12, 802124940, '192.168.64.1', 1442475147),
(13, 802124940, '192.168.64.1', 215174318),
(14, 802124940, '192.168.64.1', 1168860784),
(15, 802124940, '192.168.64.1', 38635514),
(16, 802124940, '192.168.64.1', 10029548),
(17, 802124940, '192.168.64.1', 1720653143),
(18, 802124940, '192.168.64.1', 845434693),
(19, 802124940, '192.168.64.1', 1276362415),
(20, 802124940, '192.168.64.1', 713333057),
(21, 802124940, '192.168.64.1', 1995186924),
(22, 802124940, '192.168.64.1', 639599145),
(23, 802124940, '192.168.64.1', 2140484956),
(24, 802124940, '192.168.64.1', 1395252202),
(25, 802124940, '192.168.64.1', 1710325542),
(26, 802124940, '192.168.64.1', 274613471);

-- --------------------------------------------------------

--
-- Table structure for table `log_report`
--

DROP TABLE IF EXISTS `log_report`;
CREATE TABLE `log_report` (
  `sl_num` bigint(20) UNSIGNED NOT NULL,
  `user_id` int(11) NOT NULL,
  `log_report` text COLLATE utf8_unicode_ci NOT NULL,
  `timer_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `log_report`
--

INSERT INTO `log_report` (`sl_num`, `user_id`, `log_report`, `timer_id`) VALUES
(1, 802124940, 'User logged In', 799062154),
(2, 802124940, 'User logged In', 790548326),
(3, 802124940, 'User logged In', 792891264),
(4, 802124940, 'User logged In', 2068944084),
(5, 802124940, 'User logged In', 1749755394),
(6, 802124940, 'User logged In', 1902291485),
(7, 802124940, 'User logged In', 598361139),
(8, 802124940, 'User logged In', 354349276),
(9, 802124940, 'User logged In', 664833858),
(10, 802124940, 'User logged In', 134753319),
(11, 802124940, 'User logged In', 2044480621),
(12, 0, 'User logged Out', 1930386403),
(13, 802124940, 'User logged In', 1442475147),
(14, 0, 'User logged Out', 2138774395),
(15, 802124940, 'User logged In', 215174318),
(16, 802124940, 'User logged Out', 1767717756),
(17, 802124940, 'User logged In', 1168860784),
(18, 802124940, 'User logged Out', 2085953132),
(19, 802124940, 'User logged In', 38635514),
(20, 802124940, 'User logged Out', 251259148),
(21, 802124940, 'User logged In', 10029548),
(22, 802124940, 'User logged Out', 746002526),
(23, 802124940, 'User logged In', 1720653143),
(24, 802124940, 'User logged In', 845434693),
(25, 0, ' updated by User', 2071377696),
(26, 802124940, 'Collabo updated by User', 117048446),
(27, 802124940, 'Collabo updated by User', 369165964),
(28, 802124940, 'Collabo updated by User', 1531602248),
(29, 802124940, 'User logged Out', 788610104),
(30, 802124940, 'User logged In', 1276362415),
(31, 802124940, 'User logged Out', 1175845374),
(32, 802124940, 'User logged In', 713333057),
(33, 802124940, 'User logged Out', 455965985),
(34, 802124940, 'User logged In', 1995186924),
(35, 802124940, 'User logged Out', 1850788022),
(36, 802124940, 'User logged In', 639599145),
(37, 802124940, 'User logged Out', 819251882),
(38, 802124940, 'User logged In', 2140484956),
(39, 802124940, 'Collabo updated by User', 1858644361),
(40, 802124940, 'User downloades activity_report spreadsheet', 1382251671),
(41, 802124940, 'User downloades activity_report spreadsheet', 335793212),
(42, 802124940, 'User downloades user_login spreadsheet', 1841161016),
(43, 802124940, 'User logged Out', 1833348794),
(44, 802124940, 'User logged In', 1395252202),
(46, 802124940, 'Collabo updated by User', 1522252993),
(47, 802124940, 'Pritom is updated by User', 465471830),
(48, 802124940, 'User logged Out', 632792819),
(49, 802124940, 'User logged In', 1710325542),
(50, 802124940, 'SL4556789 created by User', 0),
(51, 802124940, 'UK3455678 created by User', 1377784436),
(52, 802124940, 'User logged Out', 1844716715),
(53, 802124940, 'User logged In', 274613471),
(54, 802124940, 'UK3455678 updated by User', 2088566979),
(55, 802124940, 'UK3455678 updated by User', 1666575281),
(56, 802124940, 'Place Hong Kong created by User', 955022688),
(57, 802124940, 'Place Singapore created by User', 1725144227),
(58, 802124940, 'Place China created by User', 1501689331),
(59, 802124940, 'Place Canberra created by User', 779947002),
(60, 802124940, 'Place Dhaka created by User', 234220200),
(61, 802124940, 'Place Mumbai created by User', 178678955),
(62, 802124940, 'Place Bangalore created by User', 152014375),
(63, 802124940, 'Place India created by User', 2064383634),
(64, 802124940, 'Place Chittagong created by User', 1355563348),
(65, 802124940, 'Place Bangladesh created by User', 1205353701),
(66, 802124940, 'Place Bangladesh updated by User', 15040059),
(67, 802124940, 'Place Bangladesh updated by User', 1441400741),
(68, 802124940, 'User logged Out', 981273444);

-- --------------------------------------------------------

--
-- Table structure for table `mawb_details`
--

DROP TABLE IF EXISTS `mawb_details`;
CREATE TABLE `mawb_details` (
  `sl_num` bigint(20) UNSIGNED NOT NULL,
  `mawb_id` int(11) NOT NULL,
  `mawb_number` text COLLATE utf8_unicode_ci NOT NULL,
  `timer_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

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
(1, 118750197, 'Collabo', 'Green Park, Boston, United States', 'pritom.saan@gmail.com', '+8801732844253', 1321687697),
(2, 1568829822, 'DBShine', '44/2 Derbyshire, London, UK', 'info@dbshine.com', '8997688876', 1421439282),
(3, 404137689, 'Barca Brother\'s', 'Monte Carlo, Barcelona, Spain', 'barca@spaintransport.com', '3445444322', 395631740);

-- --------------------------------------------------------

--
-- Table structure for table `origin_destination_details`
--

DROP TABLE IF EXISTS `origin_destination_details`;
CREATE TABLE `origin_destination_details` (
  `sl_num` bigint(20) UNSIGNED NOT NULL,
  `o_id_id` int(11) NOT NULL,
  `full_name` text COLLATE utf8_unicode_ci NOT NULL,
  `short_form` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `type` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `timer_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `origin_destination_details`
--

INSERT INTO `origin_destination_details` (`sl_num`, `o_id_id`, `full_name`, `short_form`, `type`, `timer_id`) VALUES
(1, 1844140912, 'Hong Kong', 'HK', 'origin', 955022688),
(2, 788742545, 'Singapore', 'SN', 'destination', 1725144227),
(3, 60481767, 'China', 'CN', 'country', 1501689331),
(4, 2050934909, 'Canberra', 'CN', 'origin', 779947002),
(5, 1725951133, 'Dhaka', 'DHK', 'origin', 234220200),
(6, 934171028, 'Mumbai', 'MB', 'destination', 178678955),
(7, 1429313512, 'Bangalore', 'BGL', 'destination', 152014375),
(8, 264690165, 'India', 'IN', 'country', 2064383634),
(9, 1282026063, 'Chittagong', 'CTG', 'origin', 1355563348),
(10, 1812560163, 'Bangladesh', 'BD', 'country', 1205353701);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin_details`
--
ALTER TABLE `admin_details`
  ADD PRIMARY KEY (`sl_num`),
  ADD UNIQUE KEY `sl_num` (`sl_num`);

--
-- Indexes for table `awb_details`
--
ALTER TABLE `awb_details`
  ADD PRIMARY KEY (`sl_num`),
  ADD UNIQUE KEY `sl_num` (`sl_num`);

--
-- Indexes for table `creation_details`
--
ALTER TABLE `creation_details`
  ADD PRIMARY KEY (`sl_num`);

--
-- Indexes for table `flight_awb_relation`
--
ALTER TABLE `flight_awb_relation`
  ADD PRIMARY KEY (`sl_num`),
  ADD UNIQUE KEY `sl_num` (`sl_num`);

--
-- Indexes for table `flight_details`
--
ALTER TABLE `flight_details`
  ADD PRIMARY KEY (`sl_num`),
  ADD UNIQUE KEY `sl_num` (`sl_num`);

--
-- Indexes for table `freight_transport_relation`
--
ALTER TABLE `freight_transport_relation`
  ADD PRIMARY KEY (`sl_num`),
  ADD UNIQUE KEY `sl_num` (`sl_num`);

--
-- Indexes for table `login_info`
--
ALTER TABLE `login_info`
  ADD PRIMARY KEY (`sl_num`),
  ADD UNIQUE KEY `sl_num` (`sl_num`);

--
-- Indexes for table `login_log`
--
ALTER TABLE `login_log`
  ADD PRIMARY KEY (`sl_num`),
  ADD UNIQUE KEY `sl_num` (`sl_num`);

--
-- Indexes for table `log_report`
--
ALTER TABLE `log_report`
  ADD PRIMARY KEY (`sl_num`),
  ADD UNIQUE KEY `sl_num` (`sl_num`);

--
-- Indexes for table `mawb_details`
--
ALTER TABLE `mawb_details`
  ADD PRIMARY KEY (`sl_num`),
  ADD UNIQUE KEY `sl_num` (`sl_num`);

--
-- Indexes for table `office_branch`
--
ALTER TABLE `office_branch`
  ADD UNIQUE KEY `sl_num` (`sl_num`);

--
-- Indexes for table `origin_destination_details`
--
ALTER TABLE `origin_destination_details`
  ADD PRIMARY KEY (`sl_num`),
  ADD UNIQUE KEY `sl_num` (`sl_num`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin_details`
--
ALTER TABLE `admin_details`
  MODIFY `sl_num` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `awb_details`
--
ALTER TABLE `awb_details`
  MODIFY `sl_num` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `creation_details`
--
ALTER TABLE `creation_details`
  MODIFY `sl_num` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=141;

--
-- AUTO_INCREMENT for table `flight_awb_relation`
--
ALTER TABLE `flight_awb_relation`
  MODIFY `sl_num` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `flight_details`
--
ALTER TABLE `flight_details`
  MODIFY `sl_num` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `freight_transport_relation`
--
ALTER TABLE `freight_transport_relation`
  MODIFY `sl_num` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `login_info`
--
ALTER TABLE `login_info`
  MODIFY `sl_num` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `login_log`
--
ALTER TABLE `login_log`
  MODIFY `sl_num` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `log_report`
--
ALTER TABLE `log_report`
  MODIFY `sl_num` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=69;

--
-- AUTO_INCREMENT for table `mawb_details`
--
ALTER TABLE `mawb_details`
  MODIFY `sl_num` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `office_branch`
--
ALTER TABLE `office_branch`
  MODIFY `sl_num` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `origin_destination_details`
--
ALTER TABLE `origin_destination_details`
  MODIFY `sl_num` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

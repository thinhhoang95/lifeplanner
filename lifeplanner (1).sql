-- phpMyAdmin SQL Dump
-- version 5.0.3
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 04, 2020 at 08:49 PM
-- Server version: 10.4.14-MariaDB
-- PHP Version: 7.4.11

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `lifeplanner`
--

-- --------------------------------------------------------

--
-- Table structure for table `inbox`
--

CREATE TABLE `inbox` (
  `id` int(10) UNSIGNED NOT NULL,
  `workload_id` int(10) UNSIGNED NOT NULL,
  `time_units` smallint(5) UNSIGNED NOT NULL,
  `registration_date` datetime NOT NULL,
  `completion_status` tinyint(1) NOT NULL,
  `completion_date` datetime NOT NULL,
  `mission` mediumtext COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `inbox`
--

INSERT INTO `inbox` (`id`, `workload_id`, `time_units`, `registration_date`, `completion_status`, `completion_date`, `mission`) VALUES
(22, 5, 4, '2020-11-04 13:52:10', 0, '0000-00-00 00:00:00', 'Modeling the problem'),
(23, 2, 3, '2020-11-04 13:52:20', 0, '0000-00-00 00:00:00', 'Lecture 2'),
(24, 4, 3, '2020-11-04 13:54:07', 0, '0000-00-00 00:00:00', 'Auction algorithm');

-- --------------------------------------------------------

--
-- Table structure for table `punchcards`
--

CREATE TABLE `punchcards` (
  `time` datetime NOT NULL,
  `duration` text COLLATE utf8_unicode_ci NOT NULL,
  `paid` double NOT NULL,
  `taskname` text COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `punchcards`
--

INSERT INTO `punchcards` (`time`, `duration`, `paid`, `taskname`) VALUES
('2020-11-04 12:20:00', '00:17', 0.33716668287913, 'ANITI Research');

-- --------------------------------------------------------

--
-- Table structure for table `tokens`
--

CREATE TABLE `tokens` (
  `token` varchar(192) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `workload`
--

CREATE TABLE `workload` (
  `id` int(10) UNSIGNED NOT NULL,
  `work_name` text COLLATE utf8_unicode_ci NOT NULL,
  `work_description` text COLLATE utf8_unicode_ci NOT NULL,
  `total_time_units` smallint(5) UNSIGNED NOT NULL,
  `units_per_week` smallint(5) UNSIGNED NOT NULL,
  `active` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `workload`
--

INSERT INTO `workload` (`id`, `work_name`, `work_description`, `total_time_units`, `units_per_week`, `active`) VALUES
(1, 'Markov Chains and Martingales', 'Course by Prof Hao Wu', 9999, 16, 1),
(2, 'Advanced Probability Theory', 'Course by Olivier', 9999, 16, 1),
(3, 'Multi-target Tracking', 'Course by Svensson', 9999, 12, 1),
(4, 'Literature Review (CP)', 'Collective Perception', 9999, 8, 1),
(5, 'Algorithm Design', 'The real problem', 9999, 16, 1),
(6, 'Literature Review (Tracking)', 'Advances in the field', 9999, 8, 1),
(7, 'Experimental Logistics', 'Installing software, following tutorials', 9999, 9, 1),
(8, 'French Practice', 'Communication in French', 9999, 7, 1);

-- --------------------------------------------------------

--
-- Table structure for table `workplan`
--

CREATE TABLE `workplan` (
  `id` int(10) UNSIGNED NOT NULL,
  `workload_id` int(10) UNSIGNED NOT NULL,
  `time_units` smallint(5) UNSIGNED NOT NULL,
  `registration_date` datetime NOT NULL,
  `completion_status` tinyint(1) NOT NULL,
  `completion_date` datetime NOT NULL,
  `mission` mediumtext COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `workplan`
--

INSERT INTO `workplan` (`id`, `workload_id`, `time_units`, `registration_date`, `completion_status`, `completion_date`, `mission`) VALUES
(1, 1, 1, '2020-10-30 23:22:25', 1, '2020-10-31 15:52:21', 'Lecture 2 https://tinyurl.com/y2j6j4ac'),
(2, 1, 1, '2020-10-30 23:22:25', 1, '2020-10-31 18:04:23', 'Lecture 2 https://tinyurl.com/y2j6j4ac'),
(3, 1, 1, '2020-10-30 23:22:25', 1, '2020-10-31 18:31:06', 'Lecture 2 https://tinyurl.com/y2j6j4ac'),
(8, 1, 1, '2020-10-30 23:22:25', 1, '2020-10-31 20:48:39', 'Lecture 2 https://tinyurl.com/y2j6j4ac'),
(9, 2, 1, '2020-10-31 20:46:41', 1, '2020-11-01 11:11:08', 'Recitation of old materials'),
(10, 2, 1, '2020-10-31 20:46:41', 1, '2020-11-01 11:44:00', 'Recitation and Homework'),
(11, 2, 1, '2020-10-31 20:46:41', 1, '2020-11-01 12:11:34', 'Recitation and Homework'),
(12, 2, 1, '2020-10-31 20:46:41', 1, '2020-11-01 21:20:10', 'Recitation and Homework'),
(13, 2, 1, '2020-11-01 22:24:25', 1, '2020-11-01 22:24:29', 'Homework'),
(14, 7, 1, '2020-10-31 23:19:31', 1, '2020-11-01 23:08:04', 'Prepare for the simulations');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `inbox`
--
ALTER TABLE `inbox`
  ADD PRIMARY KEY (`id`),
  ADD KEY `workload_id` (`workload_id`);

--
-- Indexes for table `tokens`
--
ALTER TABLE `tokens`
  ADD UNIQUE KEY `token` (`token`);

--
-- Indexes for table `workload`
--
ALTER TABLE `workload`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `workplan`
--
ALTER TABLE `workplan`
  ADD PRIMARY KEY (`id`),
  ADD KEY `workload_id` (`workload_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `inbox`
--
ALTER TABLE `inbox`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `workload`
--
ALTER TABLE `workload`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `workplan`
--
ALTER TABLE `workplan`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

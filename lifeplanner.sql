-- phpMyAdmin SQL Dump
-- version 5.0.3
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 30, 2020 at 11:36 PM
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
(9, 1, 4, '2020-10-30 23:22:25', 0, '0000-00-00 00:00:00', 'Lecture 2 https://tinyurl.com/y2j6j4ac'),
(10, 3, 3, '2020-10-30 23:22:48', 0, '0000-00-00 00:00:00', 'Chapter 2 YouTube videos'),
(11, 2, 4, '2020-10-30 23:23:14', 0, '0000-00-00 00:00:00', 'Recitation and Homework for Chapter 1');

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
(5, 'Algorithm Design', 'The real problem', 9999, 16, 0),
(6, 'Literature Review (Tracking)', 'Advances in the field', 9999, 8, 1),
(7, 'Experimental Logistics', 'Installing software, following tutorials', 9999, 9, 1);

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
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `workload`
--
ALTER TABLE `workload`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `workplan`
--
ALTER TABLE `workplan`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

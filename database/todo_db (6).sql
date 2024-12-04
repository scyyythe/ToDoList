-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 04, 2024 at 04:25 PM
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
-- Database: `todo_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `accounts`
--

CREATE TABLE `accounts` (
  `u_id` int(100) NOT NULL,
  `u_name` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(100) NOT NULL,
  `u_type` varchar(50) NOT NULL,
  `plan_id` int(50) NOT NULL,
  `u_status` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `accounts`
--

INSERT INTO `accounts` (`u_id`, `u_name`, `email`, `username`, `password`, `u_type`, `plan_id`, `u_status`) VALUES
(69, 'Jamaica Anuba', 'jamaicaanuba@gmail.com', 'jai', '$2y$10$bPSZ8VHbqBPtdam0lSO4sOjG0kpbS63Q1joEZNb9DFp/o1jy/mhpi', 'Admin', 1002, 'Active');

-- --------------------------------------------------------

--
-- Table structure for table `folder_tbl`
--

CREATE TABLE `folder_tbl` (
  `folder_id` int(100) NOT NULL,
  `u_id` int(100) NOT NULL,
  `folder_name` varchar(50) NOT NULL,
  `folder_status` varchar(20) NOT NULL DEFAULT 'Active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `note`
--

CREATE TABLE `note` (
  `note_id` int(100) NOT NULL,
  `u_id` int(100) NOT NULL,
  `title` varchar(50) NOT NULL,
  `note` text NOT NULL,
  `folder_id` int(100) DEFAULT NULL,
  `image` varchar(255) NOT NULL,
  `deadline` time NOT NULL,
  `status` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payment_tbl`
--

CREATE TABLE `payment_tbl` (
  `p_id` int(100) NOT NULL,
  `subscription_id` int(100) NOT NULL,
  `date_payment` date NOT NULL,
  `status` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `plan_tbl`
--

CREATE TABLE `plan_tbl` (
  `plan_id` int(100) NOT NULL,
  `plan_name` varchar(50) NOT NULL,
  `price` decimal(65,0) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `plan_tbl`
--

INSERT INTO `plan_tbl` (`plan_id`, `plan_name`, `price`) VALUES
(1001, 'Basic', 0),
(1002, 'Premuim', 100);

-- --------------------------------------------------------

--
-- Table structure for table `subscription_tbl`
--

CREATE TABLE `subscription_tbl` (
  `subscription_id` int(100) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `plan_id` int(100) NOT NULL,
  `u_id` int(100) NOT NULL,
  `subscription_status` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `accounts`
--
ALTER TABLE `accounts`
  ADD PRIMARY KEY (`u_id`),
  ADD KEY `plan_id` (`plan_id`);

--
-- Indexes for table `folder_tbl`
--
ALTER TABLE `folder_tbl`
  ADD PRIMARY KEY (`folder_id`),
  ADD KEY `u_id` (`u_id`);

--
-- Indexes for table `note`
--
ALTER TABLE `note`
  ADD PRIMARY KEY (`note_id`),
  ADD KEY `u_id` (`u_id`),
  ADD KEY `folder_id` (`folder_id`);

--
-- Indexes for table `payment_tbl`
--
ALTER TABLE `payment_tbl`
  ADD PRIMARY KEY (`p_id`),
  ADD KEY `subscription_id` (`subscription_id`);

--
-- Indexes for table `plan_tbl`
--
ALTER TABLE `plan_tbl`
  ADD PRIMARY KEY (`plan_id`);

--
-- Indexes for table `subscription_tbl`
--
ALTER TABLE `subscription_tbl`
  ADD PRIMARY KEY (`subscription_id`),
  ADD KEY `plan_id` (`plan_id`),
  ADD KEY `u_id` (`u_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `accounts`
--
ALTER TABLE `accounts`
  MODIFY `u_id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=72;

--
-- AUTO_INCREMENT for table `folder_tbl`
--
ALTER TABLE `folder_tbl`
  MODIFY `folder_id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=47;

--
-- AUTO_INCREMENT for table `note`
--
ALTER TABLE `note`
  MODIFY `note_id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=168;

--
-- AUTO_INCREMENT for table `payment_tbl`
--
ALTER TABLE `payment_tbl`
  MODIFY `p_id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `plan_tbl`
--
ALTER TABLE `plan_tbl`
  MODIFY `plan_id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1005;

--
-- AUTO_INCREMENT for table `subscription_tbl`
--
ALTER TABLE `subscription_tbl`
  MODIFY `subscription_id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `accounts`
--
ALTER TABLE `accounts`
  ADD CONSTRAINT `accounts_ibfk_1` FOREIGN KEY (`plan_id`) REFERENCES `plan_tbl` (`plan_id`);

--
-- Constraints for table `folder_tbl`
--
ALTER TABLE `folder_tbl`
  ADD CONSTRAINT `folder_tbl_ibfk_1` FOREIGN KEY (`u_id`) REFERENCES `accounts` (`u_id`);

--
-- Constraints for table `note`
--
ALTER TABLE `note`
  ADD CONSTRAINT `note_ibfk_1` FOREIGN KEY (`u_id`) REFERENCES `accounts` (`u_id`),
  ADD CONSTRAINT `note_ibfk_2` FOREIGN KEY (`folder_id`) REFERENCES `folder_tbl` (`folder_id`);

--
-- Constraints for table `payment_tbl`
--
ALTER TABLE `payment_tbl`
  ADD CONSTRAINT `payment_tbl_ibfk_1` FOREIGN KEY (`subscription_id`) REFERENCES `subscription_tbl` (`subscription_id`);

--
-- Constraints for table `subscription_tbl`
--
ALTER TABLE `subscription_tbl`
  ADD CONSTRAINT `subscription_tbl_ibfk_1` FOREIGN KEY (`plan_id`) REFERENCES `plan_tbl` (`plan_id`),
  ADD CONSTRAINT `subscription_tbl_ibfk_2` FOREIGN KEY (`u_id`) REFERENCES `accounts` (`u_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 07, 2024 at 02:57 AM
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
  `plan` varchar(50) NOT NULL,
  `u_status` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `accounts`
--

INSERT INTO `accounts` (`u_id`, `u_name`, `email`, `username`, `password`, `u_type`, `plan`, `u_status`) VALUES
(41, 'Angel Canete', 'caneteangel@yahoo.com', 'angel', '$2y$10$k8Eg6ezWxJbG2uiIC/MDs.ugP6oB5AF45NRvAk51LHmAkBkP8VZCq', 'Admin', 'Basic', 'Active'),
(42, 'Kyle Canete', 'kylecanete@gmail.om', 'kyle', '$2y$10$/Rf9jkKsandh.NO2DqT.4eKuqdHRcLpchDPcMi/9VY/xJPDgOqoUe', 'User', 'Basic', 'Active'),
(43, 'Jamaica Anuba', 'jamaicaanuba@gmail.com', 'jai', '$2y$10$o5owZ8y3h07Vu23SW467tuxkVr14Z9jT7xLA4MTB0CXrEq2mXGC.O', 'User', 'Basic', 'Pending'),
(44, 'Kyle Canete', 'lorencanete@yahoo.com', 'kyle', '$2y$10$GQ8XK9hmlL696.ZDrWZo0.KVddTDZQm8.DUK9tomqeC7bN/1/x5q2', 'User', 'Basic', 'Pending');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `accounts`
--
ALTER TABLE `accounts`
  ADD PRIMARY KEY (`u_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `accounts`
--
ALTER TABLE `accounts`
  MODIFY `u_id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=45;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

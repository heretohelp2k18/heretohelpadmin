-- phpMyAdmin SQL Dump
-- version 4.5.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Jul 21, 2017 at 04:40 PM
-- Server version: 10.1.19-MariaDB
-- PHP Version: 5.5.38

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `icrime`
--

-- --------------------------------------------------------

--
-- Table structure for table `region`
--

CREATE TABLE `region` (
  `id` int(2) NOT NULL,
  `region` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `region`
--

INSERT INTO `region` (`id`, `region`) VALUES
(1, 'NIR - Negros Island Region'),
(2, 'NCR - National Capital Region'),
(3, 'CAR - Cordillera Administrative Region'),
(4, 'REGION I (Ilocos Region)'),
(5, 'REGION II (Cagayan Valley)'),
(6, 'REGION III (Central Luzon)'),
(7, 'REGION IV-A (CALABARZON)'),
(8, 'REGION IV-B MIMAROPA Region'),
(9, 'REGION V (Bicol Region)'),
(10, 'REGION VI (Western Visayas)'),
(11, 'REGION VII (Central Visayas)'),
(12, 'REGION VIII (Eastern Visayas)'),
(13, 'REGION IX (Zamboanga Peninsula)'),
(14, 'REGION X (Northern Mindanao)'),
(15, 'REGION XI (Davao Region)'),
(16, 'REGION XII (Soccsksargen)'),
(17, 'REGION XIII (Caraga)'),
(18, 'ARMM - Autonomous Region in Muslim Mindanao');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `region`
--
ALTER TABLE `region`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `region`
--
ALTER TABLE `region`
  MODIFY `id` int(2) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

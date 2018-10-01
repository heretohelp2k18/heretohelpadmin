-- phpMyAdmin SQL Dump
-- version 4.2.7.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jul 27, 2017 at 03:34 PM
-- Server version: 5.5.39
-- PHP Version: 5.4.31

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `icrime`
--

-- --------------------------------------------------------

--
-- Table structure for table `blotter`
--

CREATE TABLE IF NOT EXISTS `blotter` (
`id` int(11) NOT NULL,
  `entry_number` int(11) NOT NULL,
  `incident` varchar(125) NOT NULL,
  `date_reported` date NOT NULL,
  `time_reported` time NOT NULL,
  `date_of_incident` date NOT NULL,
  `time_of_incident` time NOT NULL,
  `narrative` text NOT NULL,
  `place_of_incident` varchar(125) NOT NULL,
  `g_lat` varchar(125) NOT NULL,
  `g_long` varchar(125) NOT NULL,
  `enabled` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `reporting`
--

CREATE TABLE IF NOT EXISTS `reporting` (
`id` int(11) NOT NULL,
  `blotter_id` int(11) NOT NULL,
  `lname` varchar(25) NOT NULL,
  `fname` varchar(25) NOT NULL,
  `mname` varchar(25) NOT NULL,
  `qualifier` varchar(50) NOT NULL,
  `nickname` varchar(25) NOT NULL,
  `citizenship` varchar(25) NOT NULL,
  `sex` tinyint(1) NOT NULL COMMENT '1 - Male, 2 - Female',
  `status` tinyint(1) NOT NULL COMMENT '1 - Single, 2 - Married, 3 - Separated, 4 - Widow',
  `birth_date` date NOT NULL,
  `age` int(3) NOT NULL,
  `birth_place` varchar(125) NOT NULL,
  `phone` varchar(25) NOT NULL,
  `mobile` varchar(15) NOT NULL,
  `c_address` varchar(125) NOT NULL,
  `c_village` varchar(50) NOT NULL,
  `c_brgy` varchar(50) NOT NULL,
  `c_city` varchar(50) NOT NULL,
  `c_province` varchar(50) NOT NULL,
  `o_address` varchar(125) NOT NULL,
  `o_village` varchar(50) NOT NULL,
  `o_brgy` varchar(50) NOT NULL,
  `o_city` varchar(50) NOT NULL,
  `o_province` varchar(50) NOT NULL,
  `hea` varchar(50) NOT NULL COMMENT 'highest educational attainment',
  `occupation` varchar(50) NOT NULL,
  `id_presented` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `is_victim` tinyint(1) NOT NULL COMMENT '1 - Yes, 2 - No'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `suspect`
--

CREATE TABLE IF NOT EXISTS `suspect` (
`id` int(11) NOT NULL,
  `blotter_id` int(11) NOT NULL,
  `lname` varchar(25) NOT NULL,
  `fname` varchar(25) NOT NULL,
  `mname` varchar(25) NOT NULL,
  `qualifier` varchar(50) NOT NULL,
  `nickname` varchar(25) NOT NULL,
  `citizenship` varchar(25) NOT NULL,
  `sex` tinyint(1) NOT NULL COMMENT '1 - Male, 2 - Female',
  `status` tinyint(1) NOT NULL COMMENT '1 - Single, 2 - Married, 3 - Separated, 4 - Widow',
  `birth_date` date NOT NULL,
  `age` int(3) NOT NULL,
  `birth_place` varchar(125) NOT NULL,
  `phone` varchar(25) NOT NULL,
  `mobile` varchar(15) NOT NULL,
  `c_address` varchar(125) NOT NULL,
  `c_village` varchar(50) NOT NULL,
  `c_brgy` varchar(50) NOT NULL,
  `c_city` varchar(50) NOT NULL,
  `c_province` varchar(50) NOT NULL,
  `o_address` varchar(125) NOT NULL,
  `o_village` varchar(50) NOT NULL,
  `o_brgy` varchar(50) NOT NULL,
  `o_city` varchar(50) NOT NULL,
  `o_province` varchar(50) NOT NULL,
  `hea` varchar(50) NOT NULL COMMENT 'highest educational attainment',
  `occupation` varchar(50) NOT NULL,
  `work_address` varchar(125) NOT NULL,
  `rtv` varchar(50) NOT NULL COMMENT 'Relation To Victim',
  `email` varchar(50) NOT NULL,
  `is_officer` tinyint(1) NOT NULL COMMENT 'if AFP or PNP personnel 1- yes, 0 - no',
  `rank` varchar(50) NOT NULL,
  `unit_assigned` varchar(125) NOT NULL,
  `group` varchar(50) NOT NULL,
  `is_wpcr` tinyint(1) NOT NULL COMMENT 'with previous criminal record 1 - yes, 0 - no',
  `criminal_records` varchar(125) NOT NULL,
  `sopc` varchar(50) NOT NULL COMMENT 'status of previous case',
  `height` varchar(10) NOT NULL,
  `weight` varchar(10) NOT NULL,
  `eye_color` varchar(25) NOT NULL,
  `eye_desc` varchar(50) NOT NULL,
  `hair_color` varchar(25) NOT NULL,
  `hair_desc` varchar(50) NOT NULL,
  `is_uti` int(11) NOT NULL COMMENT 'if under the influence 1 - yes, 0 - No',
  `influence` varchar(125) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `victim`
--

CREATE TABLE IF NOT EXISTS `victim` (
`id` int(11) NOT NULL,
  `blotter_id` int(11) NOT NULL,
  `lname` varchar(25) NOT NULL,
  `fname` varchar(25) NOT NULL,
  `mname` varchar(25) NOT NULL,
  `qualifier` varchar(50) NOT NULL,
  `nickname` varchar(25) NOT NULL,
  `citizenship` varchar(25) NOT NULL,
  `sex` tinyint(1) NOT NULL COMMENT '1 - Male, 2 - Female',
  `status` tinyint(1) NOT NULL COMMENT '1 - Single, 2 - Married, 3 - Separated, 4 - Widow',
  `birth_date` date NOT NULL,
  `age` int(3) NOT NULL,
  `birth_place` varchar(125) NOT NULL,
  `phone` varchar(25) NOT NULL,
  `mobile` varchar(15) NOT NULL,
  `c_address` varchar(125) NOT NULL,
  `c_village` varchar(50) NOT NULL,
  `c_brgy` varchar(50) NOT NULL,
  `c_city` varchar(50) NOT NULL,
  `c_province` varchar(50) NOT NULL,
  `o_address` varchar(125) NOT NULL,
  `o_village` varchar(50) NOT NULL,
  `o_brgy` varchar(50) NOT NULL,
  `o_city` varchar(50) NOT NULL,
  `o_province` varchar(50) NOT NULL,
  `hea` varchar(50) NOT NULL COMMENT 'highest educational attainment',
  `occupation` varchar(50) NOT NULL,
  `work_address` varchar(125) NOT NULL,
  `email` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `blotter`
--
ALTER TABLE `blotter`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `reporting`
--
ALTER TABLE `reporting`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `suspect`
--
ALTER TABLE `suspect`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `victim`
--
ALTER TABLE `victim`
 ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `blotter`
--
ALTER TABLE `blotter`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `reporting`
--
ALTER TABLE `reporting`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `suspect`
--
ALTER TABLE `suspect`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `victim`
--
ALTER TABLE `victim`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

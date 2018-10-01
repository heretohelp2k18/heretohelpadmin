-- phpMyAdmin SQL Dump
-- version 4.2.7.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jul 21, 2017 at 02:26 AM
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
-- Table structure for table `app_users`
--

CREATE TABLE IF NOT EXISTS `app_users` (
`id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL,
  `lastname` varchar(125) NOT NULL,
  `firstname` varchar(125) NOT NULL,
  `position` varchar(50) NOT NULL,
  `enabled` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `app_users`
--

INSERT INTO `app_users` (`id`, `username`, `password`, `lastname`, `firstname`, `position`, `enabled`) VALUES
(1, 'jethro1', '2f659ba6749ce9eb5223b206cc2b4062946d48b9', 'acosta1', 'jethro1', 'Brgy Kagawad', 1),
(2, 'test', 'a94a8fe5ccb19ba61c4c0873d391e987982fbbd3', 'test', 'test', 'Brgy Chairman', 1),
(3, 'test2', '109f4b3c50d7b0df729d299bc6f8e9ef9066971f', 'test2', 'test2', 'Brgy Kagawad', 1),
(4, 'rhein', '9a1baab8f4e4bb48f95d80a8bf045d10bc04b1aa', 'rhein', 'Rhein', 'Brgy Chairman', 0);

-- --------------------------------------------------------

--
-- Table structure for table `stations`
--

CREATE TABLE IF NOT EXISTS `stations` (
`id` int(11) NOT NULL,
  `station` varchar(125) NOT NULL,
  `district` varchar(125) NOT NULL,
  `address` varchar(512) NOT NULL,
  `g_lat` varchar(125) NOT NULL,
  `g_long` varchar(125) NOT NULL,
  `phone` varchar(25) NOT NULL,
  `chief` varchar(125) NOT NULL,
  `chief_phone` varchar(25) NOT NULL,
  `enabled` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `stations`
--

INSERT INTO `stations` (`id`, `station`, `district`, `address`, `g_lat`, `g_long`, `phone`, `chief`, `chief_phone`, `enabled`) VALUES
(1, 'Deparo', 'Deparo', 'Deparo', '14.740462686198459', '121.02459727357177', '4567891', 'Deparo', 'Deparo', 1),
(2, 'Marikina', 'Marikina', 'Marikina', '14.676041', '121.04369999999994', '23123', 'test', '234234', 1),
(3, 'StaLucia', 'Marikina', 'StaLucia', '14.676041', '121.04369999999994', '567890', 'StaLucia', '56789031111', 1),
(4, 'centerpoint', 'centerpoint', 'centerpoint', '14.604629254137874', '121.01908115000003', '4567890', 'test', 'test', 1);

-- --------------------------------------------------------

--
-- Table structure for table `wanted`
--

CREATE TABLE IF NOT EXISTS `wanted` (
  `id` int(11) NOT NULL,
  `lastname` varchar(25) NOT NULL,
  `firstname` varchar(25) NOT NULL,
  `middlename` varchar(25) NOT NULL,
  `region` varchar(50) NOT NULL,
  `alias` varchar(25) NOT NULL,
  `reward` decimal(11,2) NOT NULL,
  `mcn` varchar(50) NOT NULL COMMENT 'Memorandum Circular Number',
  `mcdate` date NOT NULL COMMENT 'Memorandum Circular Date',
  `ccn` varchar(50) NOT NULL COMMENT 'Criminal Case Number',
  `offenses` varchar(225) NOT NULL,
  `court` varchar(125) NOT NULL,
  `synopsis` varchar(512) NOT NULL,
  `sex` varchar(10) NOT NULL,
  `height` varchar(10) NOT NULL,
  `weight` varchar(10) NOT NULL,
  `eyes` varchar(25) NOT NULL,
  `hair` varchar(25) NOT NULL,
  `complexion` varchar(25) NOT NULL,
  `other` varchar(125) NOT NULL,
  `image` varchar(125) NOT NULL,
  `age` int(3) NOT NULL,
  `birthdate` date NOT NULL,
  `birthplace` varchar(125) NOT NULL,
  `citizenship` varchar(50) NOT NULL,
  `father` varchar(75) NOT NULL,
  `mother` varchar(75) NOT NULL,
  `address` varchar(125) NOT NULL,
  `civilstatus` varchar(15) NOT NULL,
  `elementary` varchar(125) NOT NULL,
  `secondary` varchar(125) NOT NULL,
  `college` varchar(125) NOT NULL,
  `sort` int(11) NOT NULL,
  `enabled` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `app_users`
--
ALTER TABLE `app_users`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `stations`
--
ALTER TABLE `stations`
 ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `app_users`
--
ALTER TABLE `app_users`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `stations`
--
ALTER TABLE `stations`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=5;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

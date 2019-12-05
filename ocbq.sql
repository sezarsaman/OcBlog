-- phpMyAdmin SQL Dump
-- version 4.8.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 05, 2019 at 11:37 AM
-- Server version: 10.1.34-MariaDB
-- PHP Version: 7.2.7

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ocbq`
--

-- --------------------------------------------------------

--
-- Table structure for table `oc_post`
--

DROP TABLE IF EXISTS `oc_post`;
CREATE TABLE `oc_post` (
  `post_id` int(11) NOT NULL,
  `sort_order` int(3) NOT NULL DEFAULT '0',
  `date` date NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `oc_post_description`
--

DROP TABLE IF EXISTS `oc_post_description`;
CREATE TABLE `oc_post_description` (
  `post_id` int(11) NOT NULL,
  `language_id` int(11) NOT NULL,
  `title` varchar(256) NOT NULL,
  `description` text NOT NULL,
  `summary` text NOT NULL,
  `meta_title` varchar(256) NOT NULL,
  `meta_description` text NOT NULL,
  `meta_keyword` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `oc_post_image`
--

DROP TABLE IF EXISTS `oc_post_image`;
CREATE TABLE `oc_post_image` (
  `post_image_id` int(11) NOT NULL,
  `post_id` int(11) NOT NULL,
  `image` varchar(255) NOT NULL,
  `image_usage` varchar(255) NOT NULL,
  `sort_order` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `oc_post`
--
ALTER TABLE `oc_post`
  ADD PRIMARY KEY (`post_id`);

--
-- Indexes for table `oc_post_description`
--
ALTER TABLE `oc_post_description`
  ADD PRIMARY KEY (`post_id`,`language_id`);

--
-- Indexes for table `oc_post_image`
--
ALTER TABLE `oc_post_image`
  ADD PRIMARY KEY (`post_image_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `oc_post`
--
ALTER TABLE `oc_post`
  MODIFY `post_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `oc_post_image`
--
ALTER TABLE `oc_post_image`
  MODIFY `post_image_id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

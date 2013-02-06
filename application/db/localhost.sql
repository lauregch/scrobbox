-- phpMyAdmin SQL Dump
-- version 3.3.7deb7
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Sep 03, 2012 at 01:03 PM
-- Server version: 5.1.63
-- PHP Version: 5.3.3-7+squeeze14

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `scrobbox`
--
CREATE DATABASE `scrobbox` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `scrobbox`;

-- --------------------------------------------------------

--
-- Table structure for table `scrobbles`
--

CREATE TABLE IF NOT EXISTS `scrobbles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `artist` varchar(255) NOT NULL,
  `track` varchar(255) NOT NULL,
  `user` varchar(255) NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `scrobbles`
--

INSERT INTO `scrobbles` (`id`, `artist`, `track`, `user`, `date`) VALUES
(1, 'Cheveu', 'C''est ça l''amour', 'Pearly01', '2012-08-27 13:25:51'),
(2, 'Swans', 'Lunacy', 'Pearly01', '2012-08-27 15:12:18'),
(3, 'Cheveu', 'Lola Langusta', 'Pearly01', '2012-08-28 23:37:38');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `name` varchar(255) NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `key` varchar(32) NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`name`, `date`, `key`, `active`) VALUES
('Moote', '2012-08-27 09:15:51', 'd580d57f32848f5dcf574d1ce18d78b2', 0),
('neuneuland', '2012-08-28 13:04:26', '3b5cf425bf2e4d284aeb6547d3210174', 1),
('Pearly01', '2012-08-27 08:54:37', '61e5ea9d5512884d8b3583c71be61830', 1);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`name`) REFERENCES `users` (`name`) ON DELETE NO ACTION ON UPDATE CASCADE;

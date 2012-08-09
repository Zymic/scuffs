-- phpMyAdmin SQL Dump
-- version 3.3.9.2
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Oct 13, 2011 at 07:12 AM
-- Server version: 5.5.9
-- PHP Version: 5.3.6

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `mbg`
--

-- --------------------------------------------------------

--
-- Table structure for table `mbg_v2_albums`
--

CREATE TABLE `mbg_v2_albums` (
  `AlbumID` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `AlbumName` varchar(255) NOT NULL,
  `Description` text NOT NULL,
  `DateCreated` int(20) NOT NULL,
  `Thumbnail1Size` varchar(10) DEFAULT NULL,
  `Thumbnail2Size` varchar(10) DEFAULT NULL,
  `Thumbnail3Size` varchar(10) DEFAULT NULL,
  `OrderID` int(11) NOT NULL DEFAULT '1',
  `AlbumCover` int(11) unsigned DEFAULT NULL,
  `Params` longtext NOT NULL,
  PRIMARY KEY (`AlbumID`),
  KEY `AlbumCover` (`AlbumCover`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `mbg_v2_albums`
--

INSERT INTO `mbg_v2_albums` VALUES(1, 'daasasd', 'adadssad', 1317328256, '215x146x1', '1280x960x', NULL, 0, NULL, '');
INSERT INTO `mbg_v2_albums` VALUES(2, 'tsss', 'sss', 1317839095, '215x146x1', '1280x960x', 'xx', 1, NULL, '');

-- --------------------------------------------------------

--
-- Table structure for table `mbg_v2_categories`
--

CREATE TABLE `mbg_v2_categories` (
  `CategoryID` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `Name` varchar(255) NOT NULL,
  `Description` longtext NOT NULL,
  `OrderID` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`CategoryID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `mbg_v2_categories`
--

INSERT INTO `mbg_v2_categories` VALUES(2, 'First Category', 'Hello World', 1);
INSERT INTO `mbg_v2_categories` VALUES(3, 'Another Category', 'Fitore', 2);
INSERT INTO `mbg_v2_categories` VALUES(4, 'Testing', 'Hello World 2', 3);

-- --------------------------------------------------------

--
-- Table structure for table `mbg_v2_categories_albums`
--

CREATE TABLE `mbg_v2_categories_albums` (
  `CategoryID` int(11) unsigned NOT NULL,
  `AlbumID` int(11) unsigned NOT NULL,
  PRIMARY KEY (`CategoryID`,`AlbumID`),
  KEY `AlbumID` (`AlbumID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `mbg_v2_categories_albums`
--

INSERT INTO `mbg_v2_categories_albums` VALUES(3, 1);
INSERT INTO `mbg_v2_categories_albums` VALUES(4, 1);

-- --------------------------------------------------------

--
-- Table structure for table `mbg_v2_images`
--

CREATE TABLE `mbg_v2_images` (
  `ImageID` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `AlbumID` int(11) unsigned NOT NULL,
  `Type` enum('image','video') NOT NULL DEFAULT 'image',
  `ImagePath` varchar(255) NOT NULL,
  `VideoURL` longtext NOT NULL,
  `Name` varchar(255) NOT NULL,
  `Description` longtext NOT NULL,
  `UploadDate` int(20) NOT NULL,
  `Params` longtext NOT NULL,
  `OrderID` int(20) NOT NULL,
  PRIMARY KEY (`ImageID`),
  KEY `AlbumID` (`AlbumID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `mbg_v2_images`
--


-- --------------------------------------------------------

--
-- Table structure for table `mbg_v2_options`
--

CREATE TABLE `mbg_v2_options` (
  `option_name` varchar(100) NOT NULL,
  `option_value` longtext,
  PRIMARY KEY (`option_name`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `mbg_v2_options`
--

INSERT INTO `mbg_v2_options` VALUES('admin_username', 'admin');
INSERT INTO `mbg_v2_options` VALUES('admin_password', 'admin');
INSERT INTO `mbg_v2_options` VALUES('title', 'Mini Back-end Gallery v2.1');
INSERT INTO `mbg_v2_options` VALUES('images_path', 'uploads/');
INSERT INTO `mbg_v2_options` VALUES('naming', 'hash');
INSERT INTO `mbg_v2_options` VALUES('thumbnail_1_size', '215x146x1');
INSERT INTO `mbg_v2_options` VALUES('thumbnail_2_size', '1280x960x');
INSERT INTO `mbg_v2_options` VALUES('thumbnail_3_size', 'x');
INSERT INTO `mbg_v2_options` VALUES('fe_installed', '1');
INSERT INTO `mbg_v2_options` VALUES('fe_theme_selected', '1');
INSERT INTO `mbg_v2_options` VALUES('fe_url', 'http://localhost/mbg-update/frontend-framework/');
INSERT INTO `mbg_v2_options` VALUES('fe_path', '../frontend-framework/');
INSERT INTO `mbg_v2_options` VALUES('current_theme', 'dark_night');
INSERT INTO `mbg_v2_options` VALUES('mini_backend_gallery_url', 'http://localhost/mbg-update/backend-gallery/');
INSERT INTO `mbg_v2_options` VALUES('mini_backend_gallery_path', '/Applications/MAMP/htdocs/mbg-update/backend-gallery/');
INSERT INTO `mbg_v2_options` VALUES('category_select_type', 'multi');

--
-- Constraints for dumped tables
--

--
-- Constraints for table `mbg_v2_categories_albums`
--
ALTER TABLE `mbg_v2_categories_albums`
  ADD CONSTRAINT `mbg_v2_categories_albums_ibfk_1` FOREIGN KEY (`CategoryID`) REFERENCES `mbg_v2_categories` (`CategoryID`),
  ADD CONSTRAINT `mbg_v2_categories_albums_ibfk_2` FOREIGN KEY (`AlbumID`) REFERENCES `mbg_v2_albums` (`AlbumID`);

--
-- Constraints for table `mbg_v2_images`
--
ALTER TABLE `mbg_v2_images`
  ADD CONSTRAINT `mbg_v2_images_ibfk_1` FOREIGN KEY (`AlbumID`) REFERENCES `mbg_v2_albums` (`AlbumID`);

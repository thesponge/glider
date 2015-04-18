-- phpMyAdmin SQL Dump
-- version 4.0.4.1deb1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jul 31, 2013 at 06:34 PM
-- Server version: 5.5.31-1-log
-- PHP Version: 5.4.4-15.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `blacksea_dev`
--
CREATE DATABASE IF NOT EXISTS `blacksea_dev` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `blacksea_dev`;

-- --------------------------------------------------------

--
-- Table structure for table `auth_classes`
--

CREATE TABLE IF NOT EXISTS `auth_classes` (
  `cid` int(4) NOT NULL AUTO_INCREMENT COMMENT 'Class ID',
  `name` char(20) NOT NULL COMMENT 'Class name',
  `parent` int(4) DEFAULT NULL COMMENT 'Class parent (for subclasses)',
  PRIMARY KEY (`cid`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=9 ;

-- --------------------------------------------------------

--
-- Stand-in structure for view `auth_classes_datatables`
--
CREATE TABLE IF NOT EXISTS `auth_classes_datatables` (
`cid` int(4)
,`name` char(20)
,`groups` text
);
-- --------------------------------------------------------

--
-- Table structure for table `auth_groups`
--

CREATE TABLE IF NOT EXISTS `auth_groups` (
  `gid` int(4) NOT NULL AUTO_INCREMENT COMMENT 'Group ID',
  `name` char(20) NOT NULL COMMENT 'Group name',
  `parent` int(4) DEFAULT NULL COMMENT 'Group parent (if any)',
  `description` varchar(45) DEFAULT NULL COMMENT 'Group description',
  PRIMARY KEY (`gid`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=10 ;

-- --------------------------------------------------------

--
-- Table structure for table `auth_map_classes_groups`
--

CREATE TABLE IF NOT EXISTS `auth_map_classes_groups` (
  `cid` int(4) NOT NULL COMMENT 'Class ID',
  `gid` int(4) NOT NULL COMMENT 'Group ID'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Groups referred by classes';

-- --------------------------------------------------------

--
-- Table structure for table `auth_map_users_groups`
--

CREATE TABLE IF NOT EXISTS `auth_map_users_groups` (
  `uid` int(4) NOT NULL COMMENT 'User ID (FK)',
  `gid` int(4) NOT NULL COMMENT 'Group ID (FK)'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `auth_permissions_blog`
--

CREATE TABLE IF NOT EXISTS `auth_permissions_blog` (
  `gid` int(4) NOT NULL COMMENT 'Group ID',
  `comm_save` tinyint(1) DEFAULT NULL COMMENT 'Add comments',
  `comm_edit` tinyint(1) DEFAULT NULL COMMENT 'Edit comments',
  `comm_pub` tinyint(1) DEFAULT NULL COMMENT 'Publish comments',
  `comm_rm` tinyint(1) DEFAULT NULL COMMENT 'Delete comments',
  `article_save` tinyint(1) DEFAULT NULL COMMENT 'Save drafts',
  `article_edit` tinyint(1) DEFAULT NULL COMMENT 'Edit saved articles',
  `article_pub` tinyint(1) DEFAULT NULL COMMENT '(un)publish drafts/articles',
  `article_rm` tinyint(1) DEFAULT NULL COMMENT 'Delete articles',
  `mute_user` tinyint(1) DEFAULT NULL COMMENT 'Can apply comment bans',
  UNIQUE KEY `permissions_blog_gid_fk` (`gid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `auth_permissions_site`
--

CREATE TABLE IF NOT EXISTS `auth_permissions_site` (
  `gid` int(4) NOT NULL COMMENT 'Group ID',
  `page_add` tinyint(1) DEFAULT NULL COMMENT 'Create pages',
  `page_edit` tinyint(1) DEFAULT NULL COMMENT 'Edit pages',
  `page_pub` tinyint(1) DEFAULT NULL COMMENT '(un)publish pages',
  `page_rm` tinyint(1) DEFAULT NULL COMMENT 'Delete unpublished pages',
  UNIQUE KEY `permissions_site_gid_fk` (`gid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `auth_permissions_sys`
--

CREATE TABLE IF NOT EXISTS `auth_permissions_sys` (
  `gid` int(4) NOT NULL COMMENT 'Group ID',
  `user_add` tinyint(1) DEFAULT NULL COMMENT 'Add users',
  `user_edit` tinyint(1) DEFAULT NULL COMMENT 'Edit users',
  `user_rm` tinyint(1) DEFAULT NULL COMMENT 'Delete users',
  `group_add` tinyint(1) DEFAULT NULL COMMENT 'Add groups',
  `group_edit` tinyint(1) DEFAULT NULL COMMENT 'Edit groups',
  `group_rm` tinyint(1) DEFAULT NULL COMMENT 'Delete groups',
  `perm_manage` tinyint(1) DEFAULT NULL COMMENT 'Manage permissions',
  UNIQUE KEY `permissions_sys_gid_fk` (`gid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `auth_sessions`
--

CREATE TABLE IF NOT EXISTS `auth_sessions` (
  `sid` char(32) NOT NULL COMMENT 'PHP session ID',
  `uid` int(4) NOT NULL COMMENT 'User ID',
  `address` char(39) NOT NULL COMMENT 'IP address',
  `agent` text NOT NULL COMMENT 'User agent',
  `time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP COMMENT 'Time started',
  `expires` int(10) unsigned DEFAULT NULL COMMENT 'Lifetime',
  PRIMARY KEY (`sid`),
  KEY `uid_sid` (`uid`),
  KEY `fk_auth_sessions_1` (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `auth_users`
--

CREATE TABLE IF NOT EXISTS `auth_users` (
  `uid` int(4) NOT NULL AUTO_INCREMENT COMMENT 'User ID',
  `cid` int(4) DEFAULT NULL COMMENT 'User class, if any (FK)',
  `name` char(20) NOT NULL COMMENT 'User name',
  `active` tinyint(1) NOT NULL COMMENT 'Whether is enabled or not',
  `email` varchar(100) NOT NULL COMMENT 'auth_users',
  `password` text,
  PRIMARY KEY (`uid`),
  KEY `cid` (`cid`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=7 ;

-- --------------------------------------------------------

--
-- Stand-in structure for view `auth_users_datatables`
--
CREATE TABLE IF NOT EXISTS `auth_users_datatables` (
`uid` int(4)
,`name` char(20)
,`uclass` char(20)
,`active` tinyint(1)
,`email` varchar(100)
,`first_name` varchar(30)
,`last_name` varchar(50)
,`full_name` varchar(81)
);
-- --------------------------------------------------------

--
-- Stand-in structure for view `auth_users_profile`
--
CREATE TABLE IF NOT EXISTS `auth_users_profile` (
`uid` int(4)
,`first_name` varchar(30)
,`last_name` varchar(50)
,`title` varchar(70)
,`bio` text
,`phone` varchar(15)
,`photo` text
,`site` varchar(100)
,`email` varchar(100)
,`name` char(20)
,`cid` int(4)
,`country` char(4)
,`language` char(5)
);
-- --------------------------------------------------------

--
-- Table structure for table `auth_user_details`
--

CREATE TABLE IF NOT EXISTS `auth_user_details` (
  `uid` int(4) NOT NULL COMMENT 'User ID (FK)',
  `first_name` varchar(30) NOT NULL COMMENT 'First name',
  `last_name` varchar(50) NOT NULL COMMENT 'Last name',
  `language` char(5) DEFAULT NULL COMMENT 'Language code, i.e. en_US',
  `country` char(4) DEFAULT NULL COMMENT 'Country code, i.e. US',
  `city` varchar(100) DEFAULT NULL COMMENT 'City',
  `title` varchar(70) DEFAULT NULL,
  `bio` text,
  `photo` text,
  `phone` varchar(15) DEFAULT NULL,
  `site` varchar(100) DEFAULT NULL,
  `last_ip` varchar(39) NOT NULL COMMENT 'Last IP used to log in',
  `creation` varchar(100) NOT NULL COMMENT 'When was the user created',
  `last_login` char(32) DEFAULT NULL,
  UNIQUE KEY `uid` (`uid`),
  KEY `sid_details` (`last_login`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `auth_user_stats`
--

CREATE TABLE IF NOT EXISTS `auth_user_stats` (
  `uid` int(4) NOT NULL COMMENT 'User id (FK)',
  `age` int(6) DEFAULT NULL COMMENT 'Age on site (days)',
  `failed_logins` tinyint(1) DEFAULT NULL,
  `comments_count` int(6) DEFAULT NULL COMMENT 'Comments counter',
  `articles_count` int(6) DEFAULT NULL COMMENT 'Articles counter',
  `warn_count` int(2) DEFAULT NULL COMMENT 'Warns count',
  `permissions` text,
  UNIQUE KEY `uid` (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure for view `auth_classes_datatables`
--
DROP TABLE IF EXISTS `auth_classes_datatables`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `auth_classes_datatables` AS select `auth_map_classes_groups`.`cid` AS `cid`,`auth_classes`.`name` AS `name`,group_concat(`auth_groups`.`name` separator ', ') AS `groups` from ((`auth_map_classes_groups` join `auth_classes` on((`auth_map_classes_groups`.`cid` = `auth_classes`.`cid`))) join `auth_groups` on((`auth_map_classes_groups`.`gid` = `auth_groups`.`gid`))) group by `auth_map_classes_groups`.`cid`;

-- --------------------------------------------------------

--
-- Structure for view `auth_users_datatables`
--
DROP TABLE IF EXISTS `auth_users_datatables`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `auth_users_datatables` AS select `auth_users`.`uid` AS `uid`,`auth_users`.`name` AS `name`,`auth_classes`.`name` AS `uclass`,`auth_users`.`active` AS `active`,`auth_users`.`email` AS `email`,`auth_user_details`.`first_name` AS `first_name`,`auth_user_details`.`last_name` AS `last_name`,concat(`auth_user_details`.`first_name`,' ',`auth_user_details`.`last_name`) AS `full_name` from ((`auth_users` join `auth_user_details` on((`auth_users`.`uid` = `auth_user_details`.`uid`))) join `auth_classes` on((`auth_users`.`cid` = `auth_classes`.`cid`)));

-- --------------------------------------------------------

--
-- Structure for view `auth_users_profile`
--
DROP TABLE IF EXISTS `auth_users_profile`;

CREATE ALGORITHM=UNDEFINED DEFINER=`ioana`@`localhost` SQL SECURITY DEFINER VIEW `auth_users_profile` AS select `auth_users`.`uid` AS `uid`,`auth_user_details`.`first_name` AS `first_name`,`auth_user_details`.`last_name` AS `last_name`,`auth_user_details`.`title` AS `title`,`auth_user_details`.`bio` AS `bio`,`auth_user_details`.`phone` AS `phone`,`auth_user_details`.`photo` AS `photo`,`auth_user_details`.`site` AS `site`,`auth_users`.`email` AS `email`,`auth_users`.`name` AS `name`,`auth_users`.`cid` AS `cid`,`auth_user_details`.`country` AS `country`,`auth_user_details`.`language` AS `language` from (`auth_users` join `auth_user_details` on((`auth_users`.`uid` = `auth_user_details`.`uid`)));

--
-- Constraints for dumped tables
--

--
-- Constraints for table `auth_permissions_blog`
--
ALTER TABLE `auth_permissions_blog`
  ADD CONSTRAINT `auth_permissions_blog_ibfk_1` FOREIGN KEY (`gid`) REFERENCES `auth_groups` (`gid`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `auth_permissions_site`
--
ALTER TABLE `auth_permissions_site`
  ADD CONSTRAINT `auth_permissions_site_ibfk_1` FOREIGN KEY (`gid`) REFERENCES `auth_groups` (`gid`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `auth_permissions_sys`
--
ALTER TABLE `auth_permissions_sys`
  ADD CONSTRAINT `auth_permissions_sys_ibfk_1` FOREIGN KEY (`gid`) REFERENCES `auth_groups` (`gid`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `auth_sessions`
--
ALTER TABLE `auth_sessions`
  ADD CONSTRAINT `fk_auth_sessions_1` FOREIGN KEY (`uid`) REFERENCES `auth_users` (`uid`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `auth_users`
--
ALTER TABLE `auth_users`
  ADD CONSTRAINT `auth_users_ibfk_1` FOREIGN KEY (`cid`) REFERENCES `auth_classes` (`cid`) ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

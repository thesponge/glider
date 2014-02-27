-- phpMyAdmin SQL Dump
-- version 4.0.10deb1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Feb 11, 2014 at 11:55 AM
-- Server version: 5.5.35-1-log
-- PHP Version: 5.5.8-3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

-- --------------------------------------------------------

--
-- Table structure for table `auth_classes`
--

CREATE TABLE IF NOT EXISTS `auth_classes` (
  `cid` int(4) NOT NULL AUTO_INCREMENT COMMENT 'Class ID',
  `name` char(20) NOT NULL COMMENT 'Class name',
  `parent` int(4) DEFAULT NULL COMMENT 'Class parent (for subclasses)',
  PRIMARY KEY (`cid`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=8 ;

--
-- Dumping data for table `auth_classes`
--

INSERT INTO `auth_classes` (`cid`, `name`, `parent`) VALUES
(1, 'admin', NULL),
(2, 'moderator', NULL),
(3, 'guest', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `auth_invitations`
--

CREATE TABLE IF NOT EXISTS `auth_invitations` (
  `email` varchar(255) NOT NULL,
  `cid` int(3) NOT NULL DEFAULT '3',
  `token` varchar(32) NOT NULL,
  PRIMARY KEY (`email`),
  UNIQUE KEY `email` (`email`),
  UNIQUE KEY `token` (`token`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- --------------------------------------------------------

--
-- Table structure for table `auth_permissions_site`
--

CREATE TABLE IF NOT EXISTS `auth_permissions_site` (
  `cid` int(4) NOT NULL,
  `page_addRm` tinyint(1) DEFAULT NULL COMMENT 'poate adauga si sterge pagini',
  `page_edit` tinyint(1) DEFAULT NULL COMMENT 'poate edita orice pagina',
  `page_pub` tinyint(1) DEFAULT NULL COMMENT 'poate publica pagini',
  UNIQUE KEY `cid` (`cid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `auth_permissions_site`
--

INSERT INTO `auth_permissions_site` (`cid`, `page_addRm`, `page_edit`, `page_pub`) VALUES
(1, 1, 1, 1),
(2, 1, 1, 1),
(3, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `auth_permissions_sys`
--

CREATE TABLE IF NOT EXISTS `auth_permissions_sys` (
  `cid` int(4) NOT NULL,
  `user_addRm` tinyint(1) DEFAULT NULL,
  `user_edit` tinyint(1) DEFAULT NULL,
  `user_mute` tinyint(1) DEFAULT NULL,
  `perm_manage` tinyint(1) DEFAULT NULL,
  `class_manage` tinyint(1) DEFAULT NULL,
  UNIQUE KEY `cid` (`cid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `auth_permissions_sys`
--

INSERT INTO `auth_permissions_sys` (`cid`, `user_addRm`, `user_edit`, `user_mute`, `perm_manage`, `class_manage`) VALUES
(1, 1, 1, 1, 1, 1),
(2, 1, 1, 1, 1, 1),
(3, NULL, NULL, NULL, NULL, NULL);

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
  `password` varchar(32) DEFAULT NULL,
  `token` varchar(32) NOT NULL,
  PRIMARY KEY (`uid`),
  UNIQUE KEY `token_UNIQUE` (`token`),
  KEY `cid` (`cid`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=21 ;

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
-- Table structure for table `captcha_codes`
--

CREATE TABLE IF NOT EXISTS `captcha_codes` (
  `id` varchar(40) NOT NULL,
  `namespace` varchar(32) NOT NULL,
  `code` varchar(32) NOT NULL,
  `code_display` varchar(32) NOT NULL,
  `created` int(11) NOT NULL,
  PRIMARY KEY (`id`,`namespace`),
  KEY `created` (`created`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Stand-in structure for view `cid_permissions`
--
CREATE TABLE IF NOT EXISTS `cid_permissions` (
`cid` int(4)
,`name` char(20)
,`parent` int(4)
,`article_add` tinyint(1)
,`article_edit` tinyint(1)
,`article_tmpl` int(11)
,`article_pub` tinyint(1)
,`comm_edit` tinyint(1)
,`comm_add` tinyint(1)
,`page_addRm` tinyint(1)
,`page_edit` tinyint(1)
,`page_pub` tinyint(1)
,`user_addRm` tinyint(1)
,`user_edit` tinyint(1)
,`user_mute` tinyint(1)
,`perm_manage` tinyint(1)
,`class_manage` tinyint(1)
);
-- --------------------------------------------------------

--
-- Table structure for table `glider_people`
--

CREATE TABLE IF NOT EXISTS `glider_people` (
  `id` int(5) NOT NULL AUTO_INCREMENT,
  `email` varchar(200) CHARACTER SET latin1 NOT NULL,
  `first_name` varchar(50) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL,
  `last_name` varchar(40) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL,
  `role` varchar(50) DEFAULT NULL,
  `url` text,
  `bio` text CHARACTER SET latin1 NOT NULL,
  `misc` text CHARACTER SET latin1 NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=8 ;

-- --------------------------------------------------------

--
-- Table structure for table `glider_people_projects_map`
--

CREATE TABLE IF NOT EXISTS `glider_people_projects_map` (
  `project` int(5) NOT NULL,
  `person` int(5) NOT NULL,
  UNIQUE KEY `person` (`person`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `glider_projects`
--

CREATE TABLE IF NOT EXISTS `glider_projects` (
  `id` int(5) NOT NULL AUTO_INCREMENT,
  `title` varchar(100) NOT NULL,
  `url` text,
  `added` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `leader` int(5) DEFAULT NULL,
  `description` text NOT NULL,
  `short_description` varchar(300) DEFAULT NULL,
  `tech_details` text,
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `misc` text,
  PRIMARY KEY (`id`),
  UNIQUE KEY `title` (`title`),
  KEY `leader` (`leader`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=22 ;

-- --------------------------------------------------------

--
-- Table structure for table `ITEMS`
--

CREATE TABLE IF NOT EXISTS `ITEMS` (
  `id` int(3) NOT NULL AUTO_INCREMENT,
  `type` char(40) NOT NULL,
  `name_ro` text NOT NULL,
  `name_en` text NOT NULL,
  `SEO` text,
  `opt` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

--
-- Dumping data for table `ITEMS`
--

INSERT INTO `ITEMS` (`id`, `type`, `name_ro`, `name_en`, `SEO`, `opt`) VALUES
(1, 'single', 'Home', 'Home', NULL, NULL),
(2, 'single', 'FAQ', 'FAQ', NULL, NULL),
(3, 'gldproject', 'Entry list', 'Entry list', NULL, NULL),
(4, 'single', 'Schedule', 'Schedule', NULL, NULL),
(5, 'single', 'Partners', 'Partners', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `MENUS`
--

CREATE TABLE IF NOT EXISTS `MENUS` (
  `id` int(3) NOT NULL,
  `idM` int(2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `session_data`
--

CREATE TABLE IF NOT EXISTS `session_data` (
  `session_id` varchar(32) NOT NULL DEFAULT '',
  `hash` varchar(32) NOT NULL DEFAULT '',
  `session_data` blob NOT NULL,
  `session_expire` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`session_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `TREE`
--

CREATE TABLE IF NOT EXISTS `TREE` (
  `Pid` int(3) NOT NULL,
  `Cid` int(3) NOT NULL,
  `poz` int(2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `TREE`
--

INSERT INTO `TREE` (`Pid`, `Cid`, `poz`) VALUES
(0, 1, 0),
(0, 2, 0),
(0, 3, 0),
(0, 4, 0),
(0, 5, 0);

-- --------------------------------------------------------

--
-- Structure for view `auth_users_datatables`
--
DROP TABLE IF EXISTS `auth_users_datatables`;

CREATE VIEW `auth_users_datatables` AS select `auth_users`.`uid` AS `uid`,`auth_users`.`name` AS `name`,`auth_classes`.`name` AS `uclass`,`auth_users`.`active` AS `active`,`auth_users`.`email` AS `email`,`auth_user_details`.`first_name` AS `first_name`,`auth_user_details`.`last_name` AS `last_name`,concat(`auth_user_details`.`first_name`,' ',`auth_user_details`.`last_name`) AS `full_name` from ((`auth_users` join `auth_user_details` on((`auth_users`.`uid` = `auth_user_details`.`uid`))) join `auth_classes` on((`auth_users`.`cid` = `auth_classes`.`cid`)));

-- --------------------------------------------------------

--
-- Structure for view `auth_users_profile`
--
DROP TABLE IF EXISTS `auth_users_profile`;

CREATE ALGORITHM=UNDEFINED DEFINER=`odh`@`localhost` SQL SECURITY DEFINER VIEW `auth_users_profile` AS select `auth_users`.`uid` AS `uid`,`auth_user_details`.`first_name` AS `first_name`,`auth_user_details`.`last_name` AS `last_name`,`auth_user_details`.`title` AS `title`,`auth_user_details`.`bio` AS `bio`,`auth_user_details`.`phone` AS `phone`,`auth_user_details`.`photo` AS `photo`,`auth_user_details`.`site` AS `site`,`auth_users`.`email` AS `email`,`auth_users`.`name` AS `name`,`auth_users`.`cid` AS `cid`,`auth_user_details`.`country` AS `country`,`auth_user_details`.`language` AS `language` from (`auth_users` join `auth_user_details` on((`auth_users`.`uid` = `auth_user_details`.`uid`)));

-- --------------------------------------------------------

--
-- Structure for view `cid_permissions`
--
DROP TABLE IF EXISTS `cid_permissions`;

CREATE ALGORITHM=UNDEFINED DEFINER=`odh`@`localhost` SQL SECURITY DEFINER VIEW `cid_permissions` AS select `auth_classes`.`cid` AS `cid`,`auth_classes`.`name` AS `name`,`auth_classes`.`parent` AS `parent`,`auth_permissions_blog`.`article_add` AS `article_add`,`auth_permissions_blog`.`article_edit` AS `article_edit`,`auth_permissions_blog`.`article_tmpl` AS `article_tmpl`,`auth_permissions_blog`.`article_pub` AS `article_pub`,`auth_permissions_blog`.`comm_edit` AS `comm_edit`,`auth_permissions_blog`.`comm_add` AS `comm_add`,`auth_permissions_site`.`page_addRm` AS `page_addRm`,`auth_permissions_site`.`page_edit` AS `page_edit`,`auth_permissions_site`.`page_pub` AS `page_pub`,`auth_permissions_sys`.`user_addRm` AS `user_addRm`,`auth_permissions_sys`.`user_edit` AS `user_edit`,`auth_permissions_sys`.`user_mute` AS `user_mute`,`auth_permissions_sys`.`perm_manage` AS `perm_manage`,`auth_permissions_sys`.`class_manage` AS `class_manage` from (((`auth_classes` join `auth_permissions_blog` on((`auth_classes`.`cid` = `auth_permissions_blog`.`cid`))) join `auth_permissions_site` on((`auth_classes`.`cid` = `auth_permissions_site`.`cid`))) join `auth_permissions_sys` on((`auth_classes`.`cid` = `auth_permissions_sys`.`cid`)));

--
-- Constraints for dumped tables
--

--
-- Constraints for table `auth_permissions_blog`
--
ALTER TABLE `auth_permissions_blog`
  ADD CONSTRAINT `auth_permissions_blog_ibfk_1` FOREIGN KEY (`cid`) REFERENCES `auth_classes` (`cid`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `auth_permissions_site`
--
ALTER TABLE `auth_permissions_site`
  ADD CONSTRAINT `auth_permissions_site_ibfk_1` FOREIGN KEY (`cid`) REFERENCES `auth_classes` (`cid`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `auth_permissions_sys`
--
ALTER TABLE `auth_permissions_sys`
  ADD CONSTRAINT `auth_permissions_sys_ibfk_1` FOREIGN KEY (`cid`) REFERENCES `auth_classes` (`cid`) ON DELETE CASCADE ON UPDATE CASCADE;

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

--
-- Constraints for table `auth_user_details`
--
ALTER TABLE `auth_user_details`
  ADD CONSTRAINT `auth_user_details_ibfk_1` FOREIGN KEY (`uid`) REFERENCES `auth_users` (`uid`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `auth_user_stats`
--
ALTER TABLE `auth_user_stats`
  ADD CONSTRAINT `auth_user_stats_ibfk_1` FOREIGN KEY (`uid`) REFERENCES `auth_users` (`uid`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `glider_people_projects_map`
--
ALTER TABLE `glider_people_projects_map`
  ADD CONSTRAINT `fk_map-people` FOREIGN KEY (`person`) REFERENCES `glider_people` (`id`) ON DELETE NO ACTION ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_map-projects` FOREIGN KEY (`project`) REFERENCES `glider_projects` (`id`) ON DELETE NO ACTION ON UPDATE CASCADE;

--
-- Constraints for table `glider_projects`
--
ALTER TABLE `glider_projects`
  ADD CONSTRAINT `fk_project-people` FOREIGN KEY (`leader`) REFERENCES `glider_people` (`id`) ON DELETE NO ACTION ON UPDATE CASCADE;

ALTER TABLE `glider_projects` ADD `demo` TEXT NULL DEFAULT NULL ,
ADD `source` TEXT NULL DEFAULT NULL ,
ADD `conclusion` TEXT NULL DEFAULT NULL ;

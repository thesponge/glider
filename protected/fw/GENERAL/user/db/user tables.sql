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

--
-- Constraints for dumped tables
--

--
-- Constraints for table `auth_users`
--
ALTER TABLE `auth_users`
  ADD CONSTRAINT `auth_users_ibfk_1` FOREIGN KEY (`cid`) REFERENCES `auth_classes` (`cid`) ON UPDATE CASCADE;


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

--
-- Constraints for dumped tables
--

--
-- Constraints for table `auth_user_details`
--
ALTER TABLE `auth_user_details`
  ADD CONSTRAINT `auth_user_details_ibfk_1` FOREIGN KEY (`uid`) REFERENCES `auth_users` (`uid`) ON DELETE CASCADE ON UPDATE CASCADE;

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

--
-- Constraints for dumped tables
--

--
-- Constraints for table `auth_user_stats`
--
ALTER TABLE `auth_user_stats`
  ADD CONSTRAINT `auth_user_stats_ibfk_1` FOREIGN KEY (`uid`) REFERENCES `auth_users` (`uid`) ON DELETE CASCADE ON UPDATE CASCADE;

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

--
-- Constraints for dumped tables
--

--
-- Constraints for table `auth_sessions`
--
ALTER TABLE `auth_sessions`
  ADD CONSTRAINT `fk_auth_sessions_1` FOREIGN KEY (`uid`) REFERENCES `auth_users` (`uid`) ON DELETE CASCADE ON UPDATE CASCADE;

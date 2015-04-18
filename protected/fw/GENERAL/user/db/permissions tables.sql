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
-- Table structure for table `auth_permissions_blog`
--

CREATE TABLE IF NOT EXISTS `auth_permissions_blog` (
  `cid` int(4) NOT NULL COMMENT 'Class Id',
  `article_add` tinyint(1) DEFAULT NULL COMMENT 'poate adauga articole',
  `article_edit` tinyint(1) DEFAULT NULL COMMENT 'poate edita, sterge articolele altora',
  `article_pub` tinyint(1) DEFAULT NULL COMMENT 'poate publica articlolele lui',
  `comm_edit` tinyint(1) DEFAULT NULL COMMENT 'poate edita comenturile altora',
  `comm_add` tinyint(1) DEFAULT NULL COMMENT 'poate adauga comenturi',
  UNIQUE KEY `cid` (`cid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `auth_permissions_blog`
--
ALTER TABLE `auth_permissions_blog`
  ADD CONSTRAINT `auth_permissions_blog_ibfk_1` FOREIGN KEY (`cid`) REFERENCES `auth_classes` (`cid`) ON DELETE CASCADE ON UPDATE CASCADE;

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
-- Constraints for dumped tables
--

--
-- Constraints for table `auth_permissions_site`
--
ALTER TABLE `auth_permissions_site`
  ADD CONSTRAINT `auth_permissions_site_ibfk_1` FOREIGN KEY (`cid`) REFERENCES `auth_classes` (`cid`) ON DELETE CASCADE ON UPDATE CASCADE;


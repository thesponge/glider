CREATE TABLE IF NOT EXISTS `relatedPics` (
  `idPic` int(5) NOT NULL AUTO_INCREMENT,
  `idExt` int(5) NOT NULL,
  `picUrl` varchar(100) NOT NULL,
  `picTitle` varchar(150) DEFAULT NULL,
  `picCaption` varchar(150) DEFAULT NULL,
  `picPoz` int(2) NOT NULL,
  PRIMARY KEY (`idPic`),
  KEY `idExt` (`idExt`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

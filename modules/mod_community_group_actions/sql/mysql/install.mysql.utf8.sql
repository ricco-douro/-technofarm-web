CREATE TABLE `#__community_group_actions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `groupid` varchar(45) NOT NULL,
  `class` varchar(50) NOT NULL,
  `classtext` varchar(45) NOT NULL,
  `link` varchar(255) NOT NULL,
  `linktext` varchar(45) DEFAULT NULL,
  `type` varchar(45) NOT NULL COMMENT '''parent/modal''',
  `is_admin` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
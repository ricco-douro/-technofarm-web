-- Basic release schema 1.0/2.0 - now empty

-- NO Updates on version 2.1

-- Updates in version 2.2
DROP TABLE IF EXISTS `#__jchat`;
CREATE TABLE `#__jchat` (
	 `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
	 `from` VARCHAR( 255 ) NOT NULL,
	 `to` VARCHAR( 255 ) NOT NULL,
	 `fromuser` int(11) DEFAULT NULL,
	 `touser` int(11) DEFAULT NULL,
	 `message` text NOT NULL,
	 `sent` int(11) NOT NULL,
	 `read` tinyint(4) NOT NULL,
	 `type` varchar(255) NOT NULL DEFAULT 'message',
	 `status` tinyint(4) NOT NULL DEFAULT 0,
	 `clientdeleted` tinyint(4) NOT NULL DEFAULT 0,
	 `actualfrom` VARCHAR( 255 ) NOT NULL,
	 `actualto` VARCHAR( 255 ) NOT NULL,
	 `sentroomid` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  INDEX `to` (`to`),
  INDEX `from` (`from`),
  INDEX `fromuser` (`fromuser`),
  INDEX `touser` (`touser`),
  INDEX `actualfrom` (`actualfrom`),
  INDEX `actualto` (`actualto`)
) ENGINE=InnoDB CHARACTER SET `utf8`;

DROP TABLE IF EXISTS `#__jchat_contacts`;
CREATE TABLE IF NOT EXISTS `#__jchat_public_sessionrelations` (
	`ownerid` VARCHAR( 100 ) NOT NULL, 
	`contactid` VARCHAR( 100 ) NOT NULL,
  PRIMARY KEY (`ownerid`, `contactid`)
) ENGINE=InnoDB CHARACTER SET `utf8`;

DROP TABLE IF EXISTS `#__jchat_wall`;
CREATE TABLE IF NOT EXISTS `#__jchat_public_readmessages` (
	`messageid` int(11) NOT NULL, 
	`sessionid` varchar(100) NOT NULL,
  PRIMARY KEY (`messageid`, `sessionid`)
) ENGINE=InnoDB CHARACTER SET `utf8`;

DROP TABLE IF EXISTS `#__jchat_status`;
CREATE TABLE IF NOT EXISTS `#__jchat_sessionstatus` (
	 `sessionid` varchar(100) NOT NULL,
	 `status` varchar(11) DEFAULT NULL,
	 `override_name` varchar(255) DEFAULT NULL,
	 `email` varchar(255) DEFAULT NULL,
	 `description` text DEFAULT NULL,
	 `skypeid` varchar(255) DEFAULT NULL,
	 `roomid` int(11) DEFAULT NULL,
	PRIMARY KEY (`sessionid`),
	INDEX `overridenameidx` (`override_name`),
	INDEX `roomidx` (`roomid`)
) ENGINE=InnoDB CHARACTER SET `utf8`;

DROP TABLE IF EXISTS `#__jchat_skypeuser`;
CREATE TABLE IF NOT EXISTS `#__jchat_userstatus` (
	`userid` INT NOT NULL ,
	`skypeid` VARCHAR( 255 ) NOT NULL ,
	`roomid` int(11) DEFAULT NULL,
	PRIMARY KEY ( `userid` ),
	INDEX `roomidx` (`roomid`)
) ENGINE = INNODB;

CREATE TABLE IF NOT EXISTS `#__jchat_rooms` (
  `id` int(11) NOT NULL AUTO_INCREMENT, 
  `name` varchar(255) NOT NULL, 
  `description` text NULL , 
  `checked_out` int(11) UNSIGNED NOT NULL DEFAULT '0',
  `checked_out_time` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
  `published` tinyint(4) NOT NULL DEFAULT '1',
  `ordering` int(11) NOT NULL default '0',
  `access` int(11) NOT NULL default '1',
  PRIMARY KEY (`id`),
  INDEX `idxname` (`name`)
) ENGINE=InnoDB CHARACTER SET `utf8`;

CREATE TABLE IF NOT EXISTS `#__jchat_banned_users` (
	`banning` VARCHAR( 100 ) NOT NULL, 
	`banned` VARCHAR( 100 ) NOT NULL,
  PRIMARY KEY (`banning`, `banned`)
) ENGINE=InnoDB CHARACTER SET `utf8`;

-- Exceptions queries in reverse versioning order 10.0 -> 1.0
ALTER TABLE `#__jchat_sessionstatus` ADD  `typing` TINYINT NULL , ADD `typing_to` VARCHAR( 100 ) NULL; -- Added 2.4

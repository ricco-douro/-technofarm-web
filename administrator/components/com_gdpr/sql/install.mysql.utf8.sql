-- Basic release schema 1.0
CREATE TABLE IF NOT EXISTS `#__gdpr_logs` (
	 `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
	 `user_id` int(11) unsigned NOT NULL default '0',
	 `name` varchar(255) NOT NULL DEFAULT '',
  	 `username` varchar(150) NOT NULL DEFAULT '',
  	 `email` varchar(100) NOT NULL DEFAULT '',
	 `change_name` tinyint(4) NOT NULL DEFAULT 0,
	 `change_username` tinyint(4) NOT NULL DEFAULT 0,
	 `change_password` tinyint(4) NOT NULL DEFAULT 0,
	 `change_email` tinyint(4) NOT NULL DEFAULT 0,
	 `change_params` tinyint(4) NOT NULL DEFAULT 0,
	 `change_requirereset` tinyint(4) NOT NULL DEFAULT 0,
	 `change_block` tinyint(4) NOT NULL DEFAULT 0,
	 `change_sendemail` tinyint(4) NOT NULL DEFAULT 0,
	 `change_usergroups` tinyint(4) NOT NULL DEFAULT 0,
	 `change_activation` tinyint(4) NOT NULL DEFAULT 0,
	 `created_user` tinyint(4) NOT NULL DEFAULT 0,
	 `deleted_user` tinyint(4) NOT NULL DEFAULT 0,
	 `privacy_policy` tinyint(4) NOT NULL DEFAULT 1,
	 `editor_user_id` int(11) unsigned NOT NULL default '0',
	 `editor_name` varchar(255) NOT NULL DEFAULT '',
  	 `editor_username` varchar(150) NOT NULL DEFAULT '',
  	 `change_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  	 `changes_structure` TEXT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_name` (`name`),
  KEY `idx_username` (`username`),
  KEY `idx_email` (`email`),
  KEY `idx_editor_name` (`editor_name`),
  KEY `idx_editor_username` (`editor_username`),
  KEY `idx_change_date` (`change_date`)
) ENGINE=InnoDB CHARACTER SET `utf8`;

CREATE TABLE IF NOT EXISTS `#__gdpr_databreach_users` (
	`userid` INT NOT NULL ,
	`violated_user` tinyint(4) NOT NULL DEFAULT 0,
	PRIMARY KEY ( `userid` ),
	INDEX `violateduseridx` (`violated_user`)
) ENGINE=InnoDB CHARACTER SET `utf8`;

CREATE TABLE IF NOT EXISTS `#__gdpr_consent_registry` (
	 `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
	 `url` varchar(255) NOT NULL DEFAULT '',
	 `formid` varchar(255) NOT NULL DEFAULT '',
  	 `formname` varchar(255) NOT NULL DEFAULT '',
  	 `user_id` int(11) unsigned NOT NULL default '0',
	 `session_id` varchar(255) NOT NULL DEFAULT '',
	 `consent_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
	 `formfields` text NULL,
	  PRIMARY KEY (`id`),
	  KEY `idx_url` (`url`),
	  KEY `idx_formid` (`formid`),
	  KEY `idx_formname` (`formname`),
	  KEY `idx_userid` (`user_id`),
	  KEY `idx_sessionid` (`session_id`)
) ENGINE=InnoDB CHARACTER SET `utf8`;

CREATE TABLE IF NOT EXISTS `#__gdpr_record` (
	 `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
	 `fields` text NULL,
	 `checked_out` int(11) unsigned NOT NULL default '0',
  	 `checked_out_time` datetime NOT NULL default '0000-00-00 00:00:00',
  	 `published` tinyint(1) NOT NULL default '0',
  	 `ordering` int(11) NOT NULL default '0',
	  PRIMARY KEY (`id`)
) ENGINE=InnoDB CHARACTER SET `utf8`;

CREATE TABLE IF NOT EXISTS `#__gdpr_checkbox` (
	 `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
	 `placeholder` varchar(255) NOT NULL DEFAULT '',
	 `name` varchar(255) NOT NULL DEFAULT '',
	 `descriptionhtml` text NULL,
	 `formselector` varchar(255) NULL,
	 `required` tinyint(1) NOT NULL default '0',
	 `checked_out` int(11) unsigned NOT NULL default '0',
  	 `checked_out_time` datetime NOT NULL default '0000-00-00 00:00:00',
  	 `published` tinyint(1) NOT NULL default '0',
  	 `access` int(11) NOT NULL default '1',
	  PRIMARY KEY (`id`)
) ENGINE=InnoDB CHARACTER SET `utf8`;

CREATE TABLE IF NOT EXISTS `#__gdpr_cookie_consent_registry` (
	 `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  	 `user_id` int(11) unsigned NOT NULL default '0',
	 `session_id` varchar(255) NOT NULL DEFAULT '',
	 `ipaddress` varchar(255) NULL,
	 `consent_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
	 `generic` tinyint(1) NOT NULL default '0',
	 `category1` tinyint(1) NOT NULL default '0',
	 `category2` tinyint(1) NOT NULL default '0',
	 `category3` tinyint(1) NOT NULL default '0',
	 `category4` tinyint(1) NOT NULL default '0',
	  PRIMARY KEY (`id`),
	  KEY `idx_userid` (`user_id`),
	  KEY `idx_sessionid` (`session_id`)
) ENGINE=InnoDB CHARACTER SET `utf8`;

-- Exceptions queries in reverse versioning order 10.0 -> 1.0
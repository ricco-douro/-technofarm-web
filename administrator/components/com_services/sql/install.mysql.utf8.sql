CREATE TABLE IF NOT EXISTS `#__services_tokens` (
`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,

`asset_id` INT(10) UNSIGNED NOT NULL DEFAULT '0',

`ordering` INT(11)  NOT NULL ,
`created` DATETIME NOT NULL ,
`checked_out` INT(11)  NOT NULL ,
`checked_out_time` DATETIME NOT NULL ,
`created_by` INT(11)  NOT NULL ,
`last_used` DATETIME NOT NULL ,
`state` TINYINT(1)  NOT NULL ,
`userid` INT(11)  NOT NULL ,
`token` VARCHAR(255)  NOT NULL ,
`mode` VARCHAR(255)  NOT NULL ,
`debug` VARCHAR(255)  NOT NULL ,
`log_level` VARCHAR(255)  NOT NULL ,
`log_enabled` VARCHAR(255)  NOT NULL ,
`cookies_encrypt` VARCHAR(255)  NOT NULL ,
`cookies_domain` VARCHAR(255)  NOT NULL ,
`cookies_secure` VARCHAR(255)  NOT NULL ,
`cookies_secret_key` VARCHAR(255)  NOT NULL ,
`http_version` DECIMAL(4,2)  NOT NULL ,
`api_rate_limit` INT(10)  NOT NULL ,
`api_throttle` INT(11)  NOT NULL ,
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT COLLATE=utf8_bin;


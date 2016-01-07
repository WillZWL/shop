CREATE TABLE `country_ext` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `cid` char(2) NOT NULL COMMENT 'Internation Country Code ( 2 characters)',
  `lang_id` varchar(16) NOT NULL COMMENT 'e.g. zh-cn, zh-tw, en and etc.',
  `name` varchar(60) NOT NULL DEFAULT '',
  `create_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `create_at` int(10) unsigned NOT NULL DEFAULT '2130706433' COMMENT 'IP address, default 127.0.0.1',
  `create_by` varchar(32) NOT NULL DEFAULT 'system',
  `modify_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `modify_at` int(10) unsigned NOT NULL DEFAULT '2130706433' COMMENT 'IP address',
  `modify_by` varchar(32) NOT NULL DEFAULT 'system',
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_country_lang` (`cid`,`lang_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8
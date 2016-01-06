CREATE TABLE `pm_alias_values` (
  `id` int(4) NOT NULL AUTO_INCREMENT,
  `pm_alias` varchar(30) NOT NULL,
  `pm_field` varchar(30) NOT NULL,
  `pm_values` varchar(255) NOT NULL,
  `status` tinyint(1) NOT NULL,
  `create_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `create_at` int(10) unsigned NOT NULL DEFAULT '2130706433' COMMENT 'IP address, default 127.0.0.1',
  `create_by` varchar(32) NOT NULL DEFAULT 'system',
  `modify_on` date DEFAULT NULL,
  `modify_at` int(10) unsigned NOT NULL DEFAULT '2130706433' COMMENT 'IP address',
  `modify_by` varchar(32) NOT NULL DEFAULT 'system',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8
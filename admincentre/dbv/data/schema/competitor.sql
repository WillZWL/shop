CREATE TABLE `competitor` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `competitor_name` varchar(255) NOT NULL,
  `country_id` char(2) NOT NULL COMMENT 'e.g. US, HK, AU',
  `status` int(1) NOT NULL COMMENT '0 = inactive, 1 = active',
  `create_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `create_at` int(10) unsigned NOT NULL DEFAULT '2130706433' COMMENT 'IP address, default 127.0.0.1',
  `create_by` varchar(32) NOT NULL DEFAULT 'system',
  `modify_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `modify_at` int(10) unsigned NOT NULL DEFAULT '2130706433' COMMENT 'IP address',
  `modify_by` varchar(32) NOT NULL DEFAULT 'system',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_name_country` (`competitor_name`,`country_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8
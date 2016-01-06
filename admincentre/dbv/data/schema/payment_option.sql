CREATE TABLE `payment_option` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `platform_id` varchar(7) COLLATE utf8_bin NOT NULL DEFAULT '',
  `page` varchar(32) COLLATE utf8_bin NOT NULL,
  `set_id` int(11) NOT NULL,
  `create_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `create_at` int(10) unsigned NOT NULL DEFAULT '2130706433' COMMENT 'IP address, default 127.0.0.1',
  `create_by` varchar(32) COLLATE utf8_bin NOT NULL DEFAULT 'system',
  `modify_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `modify_at` int(10) unsigned NOT NULL DEFAULT '2130706433' COMMENT 'IP address',
  `modify_by` varchar(32) COLLATE utf8_bin NOT NULL DEFAULT 'system',
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_platform_id` (`platform_id`,`page`) USING BTREE,
  KEY `idx_set_id` (`set_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin
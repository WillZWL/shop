CREATE TABLE `product_feed_cat` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `feeder` varchar(32) NOT NULL DEFAULT 'KELKOO',
  `cat` varchar(50) NOT NULL,
  `sub_cat` varchar(100) DEFAULT NULL,
  `sub_sub_cat` varchar(32) DEFAULT NULL,
  `create_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `create_at` int(10) unsigned NOT NULL DEFAULT '2130706433' COMMENT 'IP address, default 127.0.0.1',
  `create_by` varchar(32) NOT NULL DEFAULT 'system',
  `modify_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `modify_at` int(10) unsigned NOT NULL DEFAULT '2130706433' COMMENT 'IP address',
  `modify_by` varchar(32) NOT NULL DEFAULT 'system',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT
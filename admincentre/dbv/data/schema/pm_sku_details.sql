CREATE TABLE `pm_sku_details` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `sku` varchar(20) NOT NULL,
  `alias` varchar(30) DEFAULT NULL,
  `field` varchar(50) DEFAULT NULL,
  `set_value` text,
  `create_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `create_at` int(10) unsigned NOT NULL DEFAULT '2130706433' COMMENT 'IP address, default 127.0.0.1',
  `create_by` varchar(32) NOT NULL DEFAULT 'system',
  `modify_on` datetime DEFAULT NULL,
  `modify_at` int(10) unsigned NOT NULL DEFAULT '2130706433' COMMENT 'IP address',
  `modify_by` varchar(32) NOT NULL DEFAULT 'system',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8
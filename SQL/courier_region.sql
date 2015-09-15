CREATE TABLE `courier_region` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `courier_id` varchar(20) NOT NULL,
  `region_id` bigint(20) unsigned NOT NULL,
  `currency_id` char(3) NOT NULL,
  `create_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `create_at` int(10) unsigned NOT NULL DEFAULT '2130706433' COMMENT 'IP address, default 127.0.0.1',
  `create_by` varchar(32) NOT NULL DEFAULT 'system',
  `modify_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `modify_at` int(10) unsigned NOT NULL DEFAULT '2130706433' COMMENT 'IP address',
  `modify_by` varchar(32) NOT NULL DEFAULT 'system',
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_courier_region_currency` (`courier_id`,`region_id`,`currency_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
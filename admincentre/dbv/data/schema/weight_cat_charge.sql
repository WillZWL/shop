CREATE TABLE `weight_cat_charge` (
  `wcat_id` bigint(20) unsigned NOT NULL,
  `delivery_type` varchar(16) NOT NULL,
  `dest_country` char(2) NOT NULL COMMENT 'International country code (2 characters)',
  `currency_id` char(3) NOT NULL,
  `amount` double(15,2) unsigned NOT NULL,
  `create_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `create_at` int(10) unsigned NOT NULL DEFAULT '2130706433' COMMENT 'IP address, default 127.0.0.1',
  `create_by` varchar(32) NOT NULL DEFAULT 'system',
  `modify_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `modify_at` int(10) unsigned NOT NULL DEFAULT '2130706433' COMMENT 'IP address',
  `modify_by` varchar(32) NOT NULL DEFAULT 'system',
  PRIMARY KEY (`wcat_id`,`delivery_type`,`dest_country`),
  KEY `fk_wcc_region_id` (`dest_country`) USING BTREE,
  KEY `fk_wcc_currency_id` (`currency_id`) USING BTREE,
  KEY `fk_wcc_delivery_type` (`delivery_type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT
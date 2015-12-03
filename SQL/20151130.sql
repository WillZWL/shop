CREATE TABLE `price_margin` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sku` bigint(20) unsigned NOT NULL,
  `platform_id` varchar(7) NOT NULL,
  `selling_price` decimal(15,2) NOT NULL DEFAULT '0.00',
  `total_cost` decimal(15,2) NOT NULL DEFAULT '0.00',
  `profit` decimal(15,2) NOT NULL DEFAULT '0.00',
  `margin` decimal(15,2) NOT NULL DEFAULT '0.00',
  `create_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `create_at` int(10) unsigned NOT NULL DEFAULT '2130706433' COMMENT 'IP address, default 127.0.0.1',
  `create_by` varchar(32) NOT NULL DEFAULT 'system',
  `modify_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `modify_at` int(10) unsigned NOT NULL DEFAULT '2130706433' COMMENT 'IP address',
  `modify_by` varchar(32) NOT NULL DEFAULT 'system',
  PRIMARY KEY (`id`),
  UNIQUE KEY (`sku`,`platform_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

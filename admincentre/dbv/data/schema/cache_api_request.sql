CREATE TABLE `cache_api_request` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `api` char(30) NOT NULL,
  `sku` varchar(15) NOT NULL,
  `platform_id` varchar(7) NOT NULL,
  `stock_update` varchar(20) DEFAULT 'N',
  `price_update` varchar(20) DEFAULT 'N',
  `item_create` char(1) DEFAULT NULL,
  `exec` smallint(6) NOT NULL DEFAULT '1' COMMENT '1 - already executed, 0 - not executed',
  `create_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `create_at` int(10) unsigned NOT NULL DEFAULT '2130706433' COMMENT 'IP address, default 127.0.0.1',
  `create_by` varchar(32) NOT NULL DEFAULT 'system',
  `modify_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `modify_at` int(10) unsigned NOT NULL DEFAULT '2130706433' COMMENT 'IP address',
  `modify_by` varchar(32) NOT NULL DEFAULT 'system',
  PRIMARY KEY (`id`),
  KEY `sku` (`sku`,`platform_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT
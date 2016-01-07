CREATE TABLE `product_history_sync` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `batch_id` int(11) NOT NULL DEFAULT '0',
  `sku` varchar(15) NOT NULL,
  `quantity` int(10) unsigned NOT NULL DEFAULT '0',
  `lang_restricted` int(11) unsigned NOT NULL DEFAULT '0',
  `currency_id` varchar(3) NOT NULL,
  `cost` decimal(10,2) NOT NULL DEFAULT '0.00',
  `lead_day` int(11) NOT NULL DEFAULT '0',
  `moq` int(11) NOT NULL DEFAULT '0',
  `supply_status` varchar(3) NOT NULL DEFAULT 'A',
  `create_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `create_at` int(10) unsigned NOT NULL DEFAULT '2130706433' COMMENT 'IP address, default 127.0.0.1',
  `create_by` varchar(32) NOT NULL DEFAULT 'system',
  `modify_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `modify_at` int(10) unsigned NOT NULL DEFAULT '2130706433' COMMENT 'IP address',
  `modify_by` varchar(32) NOT NULL DEFAULT 'system',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8
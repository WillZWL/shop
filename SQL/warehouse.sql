CREATE TABLE `warehouse` (
  `id` int not null auto_increment,
  `warehouse_id` varchar(20) NOT NULL,
  `name` varchar(50) NOT NULL,
  `fc_id` varchar(20) Not NULL DEFAULT '',
  `address` text,
  `region_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  `currency_id` char(3) NOT NULL,
  `create_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `create_at` int(10) unsigned NOT NULL DEFAULT '2130706433' COMMENT 'IP address, default 127.0.0.1',
  `create_by` varchar(32) NOT NULL DEFAULT 'system',
  `modify_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `modify_at` int(10) unsigned NOT NULL DEFAULT '2130706433' COMMENT 'IP address',
  `modify_by` varchar(32) NOT NULL DEFAULT 'system',
  PRIMARY KEY (`id`),
  unique KEY idx_warehouse_region_currency_fc (`warehouse_id`,`region_id`,`currency_id`,`fc_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
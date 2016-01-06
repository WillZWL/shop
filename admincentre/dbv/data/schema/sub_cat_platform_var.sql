CREATE TABLE `sub_cat_platform_var` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sub_cat_id` bigint(20) unsigned NOT NULL,
  `platform_id` varchar(8) NOT NULL,
  `currency_id` char(3) NOT NULL,
  `platform_commission_percent` decimal(8,2) unsigned NOT NULL DEFAULT '0.00' COMMENT 'Commission is in percentage.',
  `dlvry_chrg` double(8,2) unsigned NOT NULL,
  `custom_class_id` bigint(20) unsigned DEFAULT NULL,
  `fixed_fee` double(8,2) unsigned DEFAULT '0.00' COMMENT 'Insertion fee of product',
  `profit_margin` double(8,2) unsigned DEFAULT '0.00' COMMENT 'Profit Margin(Auto Pricing)',
  `create_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `create_at` int(10) unsigned NOT NULL DEFAULT '2130706433' COMMENT 'IP address, default 127.0.0.1',
  `create_by` varchar(32) NOT NULL DEFAULT 'system',
  `modify_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `modify_at` int(10) unsigned NOT NULL DEFAULT '2130706433' COMMENT 'IP address',
  `modify_by` varchar(32) NOT NULL DEFAULT 'system',
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_sub_cat_platform_currency` (`sub_cat_id`,`platform_id`,`currency_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8
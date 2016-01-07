CREATE TABLE `product_custom_classification` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sku` bigint(20) unsigned NOT NULL,
  `country_id` char(2) NOT NULL COMMENT 'International country code (2 characters)',
  `code` varchar(20) NOT NULL,
  `description` varchar(255) NOT NULL DEFAULT '',
  `duty_pcent` decimal(15,2) unsigned DEFAULT '0.00',
  `create_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `create_at` int(10) unsigned NOT NULL DEFAULT '2130706433' COMMENT 'IP address, default 127.0.0.1',
  `create_by` varchar(32) NOT NULL DEFAULT 'system',
  `modify_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `modify_at` int(10) unsigned NOT NULL DEFAULT '2130706433' COMMENT 'IP address',
  `modify_by` varchar(32) NOT NULL DEFAULT 'system',
  PRIMARY KEY (`id`),
  KEY `idx_sku_country` (`sku`,`country_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8
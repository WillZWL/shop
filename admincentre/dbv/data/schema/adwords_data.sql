CREATE TABLE `adwords_data` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `sku` bigint(20) unsigned NOT NULL,
  `platform_id` varchar(7) NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '1' COMMENT 'enabled = 1, disabled = 0',
  `price` double(15,2) NOT NULL DEFAULT '0.00',
  `api_request_result` tinyint(4) DEFAULT '1',
  `comment` varchar(255) NOT NULL DEFAULT '',
  `create_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `create_at` int(10) unsigned NOT NULL DEFAULT '2130706433' COMMENT 'IP address, default 127.0.0.1',
  `create_by` varchar(32) NOT NULL DEFAULT 'system',
  `modify_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `modify_at` int(10) unsigned NOT NULL DEFAULT '2130706433' COMMENT 'IP address',
  `modify_by` varchar(32) NOT NULL DEFAULT 'system',
  PRIMARY KEY (`id`),
  KEY `idx_sku_platform` (`sku`,`platform_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8
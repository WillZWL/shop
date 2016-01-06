CREATE TABLE `adwords_data_copy` (
  `sku` varchar(15) NOT NULL,
  `platform_id` varchar(7) NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '1' COMMENT 'enabled = 1, disabled = 0',
  `price` double(15,2) DEFAULT NULL,
  `api_request_result` tinyint(4) DEFAULT '1',
  `comment` varchar(255) DEFAULT NULL,
  `create_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `create_at` int(10) unsigned NOT NULL DEFAULT '2130706433' COMMENT 'IP address, default 127.0.0.1',
  `create_by` varchar(32) NOT NULL DEFAULT 'system',
  `modify_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `modify_at` int(10) unsigned NOT NULL DEFAULT '2130706433' COMMENT 'IP address',
  `modify_by` varchar(32) NOT NULL DEFAULT 'system',
  PRIMARY KEY (`sku`,`platform_id`),
  KEY `platform_id` (`platform_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT
CREATE TABLE `product_note` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sku` bigint(20) unsigned NOT NULL,
  `platform_id` varchar(7) NOT NULL,
  `type` varchar(1) NOT NULL DEFAULT 'M' COMMENT 'M = Marketing / S = Sourcing',
  `note` varchar(255) DEFAULT NULL,
  `create_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `create_at` int(10) unsigned NOT NULL DEFAULT '2130706433' COMMENT 'IP address, default 127.0.0.1',
  `create_by` varchar(32) NOT NULL DEFAULT 'system',
  `modify_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `modify_at` int(10) unsigned NOT NULL DEFAULT '2130706433' COMMENT 'IP address',
  `modify_by` varchar(32) NOT NULL DEFAULT 'system',
  PRIMARY KEY (`id`),
  KEY `idx_sku` (`sku`) USING BTREE,
  KEY `idx_platform` (`platform_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8
CREATE TABLE `affiliate_sku_platform` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `affiliate_id` varchar(16) NOT NULL,
  `sku` bigint(20) unsigned NOT NULL,
  `vb_sku` varchar(15) DEFAULT NULL,
  `platform_id` varchar(7) NOT NULL DEFAULT 'WEBSITE',
  `status` tinyint(2) unsigned NOT NULL DEFAULT '1' COMMENT '0 = auto / 1 = exclude / 2 = include.',
  `create_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `create_at` int(10) unsigned NOT NULL DEFAULT '2130706433' COMMENT 'IP address, default 127.0.0.1',
  `create_by` varchar(32) NOT NULL DEFAULT 'system',
  `modify_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `modify_at` int(10) unsigned NOT NULL DEFAULT '2130706433' COMMENT 'IP address',
  `modify_by` varchar(32) NOT NULL DEFAULT 'system',
  PRIMARY KEY (`id`),
  KEY `idx_sku_affiliate` (`sku`,`affiliate_id`,`platform_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8
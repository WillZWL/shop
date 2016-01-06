CREATE TABLE `product_banner` (
  `id` int(20) unsigned NOT NULL AUTO_INCREMENT,
  `sku` varchar(15) NOT NULL,
  `country_id` varchar(15) NOT NULL COMMENT 'e.g. AU, US, HK',
  `image` varchar(50) NOT NULL COMMENT 'image file extension, e.g. jpg, jpeg, png',
  `alt_text` varchar(255) DEFAULT NULL,
  `target_url` varchar(255) DEFAULT NULL COMMENT 'target url for image',
  `target_type` varchar(2) DEFAULT NULL COMMENT 'E: open in new window, I: open in same window',
  `status` int(2) NOT NULL DEFAULT '1' COMMENT '0 = inactive, 1 = active',
  `create_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `create_at` int(10) unsigned NOT NULL DEFAULT '2130706433' COMMENT 'IP address, default 127.0.0.1',
  `create_by` varchar(32) NOT NULL DEFAULT 'system',
  `modify_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `modify_at` int(10) unsigned NOT NULL DEFAULT '2130706433' COMMENT 'IP address',
  `modify_by` varchar(32) NOT NULL DEFAULT 'system',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uc_sku_countryid` (`sku`,`country_id`) USING BTREE,
  CONSTRAINT `fk_pb_sku` FOREIGN KEY (`sku`) REFERENCES `product_copy` (`sku`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT
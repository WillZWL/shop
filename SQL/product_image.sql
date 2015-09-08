CREATE TABLE `product_image` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `sku` bigint unsigned NOT NULL,
  `priority` tinyint(3) unsigned NOT NULL DEFAULT '1',
  `image` varchar(50) NOT NULL DEFAULT '' COMMENT 'image file extension',
  `alt_text` varchar(255) NOT NULL DEFAULT '',
  `status` tinyint(2) unsigned NOT NULL DEFAULT '1' COMMENT '0 = Inactive / 1 = Active',
  `create_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `create_at` int(10) unsigned NOT NULL DEFAULT '2130706433' COMMENT 'IP address, default 127.0.0.1',
  `create_by` varchar(32) NOT NULL DEFAULT 'system',
  `modify_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `modify_at` int(10) unsigned NOT NULL DEFAULT '2130706433' COMMENT 'IP address',
  PRIMARY KEY (`id`),
  KEY `fk_pi_sku` (`sku`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

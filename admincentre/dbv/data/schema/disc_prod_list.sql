CREATE TABLE `disc_prod_list` (
  `platform_id` varchar(7) NOT NULL,
  `sku` varchar(15) NOT NULL,
  `create_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `create_at` int(10) unsigned NOT NULL DEFAULT '2130706433' COMMENT 'IP address, default 127.0.0.1',
  `create_by` varchar(32) NOT NULL DEFAULT 'system',
  `modify_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `modify_at` int(10) unsigned NOT NULL DEFAULT '2130706433' COMMENT 'IP address',
  `modify_by` varchar(32) NOT NULL DEFAULT 'system',
  PRIMARY KEY (`platform_id`,`sku`),
  KEY `fk_pid.platform` (`platform_id`) USING BTREE,
  KEY `fk_sku_prod` (`sku`) USING BTREE,
  CONSTRAINT `fk_pid_platform` FOREIGN KEY (`platform_id`) REFERENCES `selling_platform_copy` (`id`),
  CONSTRAINT `fk_sku_prod` FOREIGN KEY (`sku`) REFERENCES `product_copy` (`sku`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT
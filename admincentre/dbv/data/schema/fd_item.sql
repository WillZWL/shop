CREATE TABLE `fd_item` (
  `fdssc_id` bigint(20) unsigned NOT NULL,
  `sku` varchar(15) NOT NULL,
  `order` tinyint(2) unsigned NOT NULL DEFAULT '1',
  `create_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `create_at` int(10) unsigned NOT NULL DEFAULT '2130706433' COMMENT 'IP address, default 127.0.0.1',
  `create_by` varchar(32) NOT NULL DEFAULT 'system',
  `modify_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `modify_at` int(10) unsigned NOT NULL DEFAULT '2130706433' COMMENT 'IP address',
  `modify_by` varchar(32) NOT NULL DEFAULT 'system',
  PRIMARY KEY (`fdssc_id`,`sku`),
  KEY `fk_fdssc_fdssc_id` (`fdssc_id`) USING BTREE,
  KEY `fk_prod_sku` (`sku`) USING BTREE,
  CONSTRAINT `fd_prod_sku` FOREIGN KEY (`sku`) REFERENCES `product_copy` (`sku`),
  CONSTRAINT `fk_fdssc_fdssc_id` FOREIGN KEY (`fdssc_id`) REFERENCES `fd_section_sub_cat` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT
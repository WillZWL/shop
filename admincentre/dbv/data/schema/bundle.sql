CREATE TABLE `bundle` (
  `prod_sku` varchar(15) NOT NULL,
  `component_sku` varchar(15) NOT NULL,
  `component_order` tinyint(2) unsigned NOT NULL DEFAULT '0' COMMENT '0 = MainProduct / 1,2,3... = Other Components',
  `create_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `create_at` int(10) unsigned NOT NULL DEFAULT '2130706433' COMMENT 'IP address, default 127.0.0.1',
  `create_by` varchar(32) NOT NULL DEFAULT 'system',
  `modify_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `modify_at` int(10) unsigned NOT NULL DEFAULT '2130706433' COMMENT 'IP address',
  `modify_by` varchar(32) NOT NULL DEFAULT 'system',
  PRIMARY KEY (`prod_sku`,`component_sku`,`component_order`),
  KEY `fk_bundle_component_sku` (`component_sku`) USING BTREE,
  CONSTRAINT `fk_bundle_component_sku` FOREIGN KEY (`component_sku`) REFERENCES `product_copy` (`sku`),
  CONSTRAINT `fk_bundle_prod_sku` FOREIGN KEY (`prod_sku`) REFERENCES `product_copy` (`sku`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT
CREATE TABLE `bundle_component` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `bundle_core_id` bigint(20) NOT NULL,
  `component_sku` varchar(15) NOT NULL,
  `create_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `create_at` int(10) unsigned NOT NULL DEFAULT '2130706433' COMMENT 'IP address, default 127.0.0.1',
  `create_by` varchar(32) NOT NULL DEFAULT 'system',
  `modify_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `modify_at` int(10) unsigned NOT NULL DEFAULT '2130706433' COMMENT 'IP address',
  `modify_by` varchar(32) NOT NULL DEFAULT 'system',
  PRIMARY KEY (`id`),
  UNIQUE KEY `ix_bcomp_coreid_compsku` (`bundle_core_id`,`component_sku`),
  KEY `fk_bcomp_ski` (`component_sku`),
  CONSTRAINT `fk_bcomp_ski` FOREIGN KEY (`component_sku`) REFERENCES `product_copy` (`sku`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8
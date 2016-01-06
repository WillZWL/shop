CREATE TABLE `bundle_core` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `core_sku` varchar(15) NOT NULL,
  `bundle_no` int(11) NOT NULL COMMENT 'bundle that this component belongs to',
  `status` int(2) NOT NULL DEFAULT '1' COMMENT '0=inactive, 1=active',
  `create_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `create_at` int(10) unsigned NOT NULL DEFAULT '2130706433' COMMENT 'IP address, default 127.0.0.1',
  `create_by` varchar(32) NOT NULL DEFAULT 'system',
  `modify_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `modify_at` int(10) unsigned NOT NULL DEFAULT '2130706433' COMMENT 'IP address',
  `modify_by` varchar(32) NOT NULL DEFAULT 'system',
  PRIMARY KEY (`id`),
  UNIQUE KEY `ix_bcore_sku_bundleno` (`core_sku`,`bundle_no`),
  CONSTRAINT `fb_bcore_sku` FOREIGN KEY (`core_sku`) REFERENCES `product_copy` (`sku`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8
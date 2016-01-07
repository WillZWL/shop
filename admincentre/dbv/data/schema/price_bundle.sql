CREATE TABLE `price_bundle` (
  `sku` varchar(15) NOT NULL,
  `bundle_core_id` bigint(20) NOT NULL,
  `platform_id` varchar(7) NOT NULL,
  `listing_status` varchar(2) NOT NULL DEFAULT 'N' COMMENT 'L - Listed, N - Not Listed, NC - No Content, NS - Not Suitable for Listing',
  `discount` double(15,2) unsigned NOT NULL DEFAULT '0.00' COMMENT 'discount %',
  `disc_type` varchar(2) NOT NULL COMMENT 'A = Amount / P = Percent',
  `auto_price` char(1) NOT NULL DEFAULT 'N' COMMENT 'N - No action, Y - Auto-price, C - Competitor reprice',
  `create_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `create_at` int(10) unsigned NOT NULL DEFAULT '2130706433' COMMENT 'IP address, default 127.0.0.1',
  `create_by` varchar(32) NOT NULL DEFAULT 'system',
  `modify_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `modify_at` int(10) unsigned NOT NULL DEFAULT '2130706433' COMMENT 'IP address',
  `modify_by` varchar(32) NOT NULL DEFAULT 'system',
  PRIMARY KEY (`sku`,`bundle_core_id`,`platform_id`),
  KEY `fk_pricebundle_coreid` (`bundle_core_id`),
  CONSTRAINT `fk_pricebundle_coreid` FOREIGN KEY (`bundle_core_id`) REFERENCES `bundle_core` (`id`),
  CONSTRAINT `fk_pricebundle_sku` FOREIGN KEY (`sku`) REFERENCES `product_copy` (`sku`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8
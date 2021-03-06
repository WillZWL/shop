CREATE TABLE `product` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sku` bigint(20) unsigned NOT NULL,
  `prod_grp_cd` int(11) NOT NULL,
  `colour_id` char(2) NOT NULL,
  `version_id` char(2) NOT NULL,
  `name` varchar(255) NOT NULL,
  `freight_cat_id` int(10) unsigned NOT NULL,
  `cat_id` int(10) unsigned NOT NULL,
  `sub_cat_id` int(10) unsigned NOT NULL,
  `sub_sub_cat_id` int(10) unsigned NOT NULL DEFAULT '0',
  `brand_id` int(10) unsigned NOT NULL DEFAULT '0',
  `clearance` tinyint(4) NOT NULL DEFAULT '0' COMMENT 'whether product is for clearance',
  `surplus_quantity` int(11) NOT NULL DEFAULT '0',
  `slow_move_7_days` tinyint(4) NOT NULL DEFAULT '0' COMMENT '1 = prod is slow moving last 7 days',
  `quantity` int(10) unsigned NOT NULL DEFAULT '0',
  `display_quantity` int(10) unsigned NOT NULL DEFAULT '0',
  `website_quantity` int(10) unsigned NOT NULL DEFAULT '0',
  `ex_demo` tinyint(4) NOT NULL DEFAULT '0',
  `china_oem` tinyint(4) NOT NULL DEFAULT '0',
  `rrp` decimal(15,2) unsigned DEFAULT '0.00',
  `image` varchar(4) NOT NULL DEFAULT '',
  `flash` varchar(4) NOT NULL DEFAULT '',
  `youtube_id` varchar(16) NOT NULL DEFAULT '',
  `ean` varchar(100) NOT NULL DEFAULT '',
  `mpn` varchar(20) NOT NULL DEFAULT '',
  `upc` varchar(20) NOT NULL DEFAULT '',
  `discount` decimal(15,2) unsigned NOT NULL DEFAULT '0.00',
  `proc_status` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '0 = Pending / 1 = Bundle Pending / 2 = RA Pending / 3 = N/A / 4 = Complete',
  `website_status` char(2) NOT NULL DEFAULT 'I' COMMENT 'I = Instock, O = Outstock, P = Pre-Order, A = Arriving',
  `sourcing_status` char(2) NOT NULL DEFAULT 'A' COMMENT 'A = Readily Available / O = Temp of Out Stock / C = Limited Stock / L = Last Lot, D = Discontinued',
  `expected_delivery_date` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `warranty_in_month` tinyint(4) NOT NULL DEFAULT '0',
  `cat_upselling` tinyint(4) NOT NULL DEFAULT '0',
  `lang_restricted` smallint(3) NOT NULL DEFAULT '1' COMMENT 'value 0 = completely no selection, bit 0 = Not applicable, bit 1 = FR, bit 2 = ES, bit 3 = RU, bit 4 = PL, bit 5= IT',
  `shipment_restricted_type` tinyint(4) NOT NULL DEFAULT '0' COMMENT '//1 = Battery, Can put more type',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '1' COMMENT '0 = Inactive / 1 = SKU Created / 2 = Production Listed',
  `create_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `create_at` int(10) unsigned NOT NULL DEFAULT '2130706433' COMMENT 'IP address, default 127.0.0.1',
  `create_by` varchar(32) NOT NULL DEFAULT 'system',
  `modify_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `modify_at` int(10) unsigned NOT NULL DEFAULT '2130706433' COMMENT 'IP address',
  `modify_by` varchar(32) NOT NULL DEFAULT 'system',
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_sku` (`sku`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8
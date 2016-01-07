CREATE TABLE `product_history` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `sku` varchar(15) NOT NULL,
  `quantity` int(10) unsigned NOT NULL DEFAULT '0',
  `display_quantity` mediumint(5) NOT NULL DEFAULT '0',
  `website_quantity` int(10) unsigned NOT NULL DEFAULT '0',
  `proc_status` tinyint(2) unsigned NOT NULL DEFAULT '0' COMMENT '0 = Pending / 1 = Bundle Pending / 2 = RA Pending / 3 = N/A / 4 = Complete',
  `website_status` varchar(2) NOT NULL DEFAULT 'I' COMMENT 'I = Instock, O = Outstock, P = Pre-Order, A = Arriving',
  `sourcing_status` varchar(2) NOT NULL DEFAULT 'A' COMMENT 'A = Readily Available / O = Temp of Out Stock / C = Limited Stock / L = Last Lot, D = Discontinued',
  `status` tinyint(2) unsigned NOT NULL DEFAULT '1' COMMENT '0 = Inactive / 1 = SKU Created / 2 = Production Listed',
  `create_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `create_at` int(10) unsigned NOT NULL DEFAULT '2130706433' COMMENT 'IP address, default 127.0.0.1',
  `create_by` varchar(32) NOT NULL DEFAULT 'system',
  `modify_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `modify_at` int(10) unsigned NOT NULL DEFAULT '2130706433' COMMENT 'IP address',
  `modify_by` varchar(32) NOT NULL DEFAULT 'system',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8
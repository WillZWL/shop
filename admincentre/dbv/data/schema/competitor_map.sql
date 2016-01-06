CREATE TABLE `competitor_map` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `ext_sku` varchar(15) NOT NULL DEFAULT '' COMMENT 'master_sku',
  `competitor_id` bigint(20) NOT NULL,
  `status` int(11) NOT NULL COMMENT '0 = inactive, 1 = active',
  `match` int(2) NOT NULL DEFAULT '1' COMMENT 'match URL for price compare? 0 = IGNORE, 1 = ACTIVE',
  `last_price` decimal(10,2) NOT NULL COMMENT 'previous competitor''s price',
  `now_price` decimal(10,2) NOT NULL COMMENT 'current update competitor''s price',
  `product_url` text NOT NULL COMMENT 'competitor''s product url',
  `note_1` text COMMENT 'competitor name frm price-comparison websites',
  `note_2` text,
  `comp_stock_status` int(2) DEFAULT NULL COMMENT 'competitor''s stock status - 0 = In stock, 1 = Out of stock, 2 = Pre-order, 3 = Arriving',
  `comp_ship_charge` decimal(10,2) DEFAULT NULL,
  `reprice_min_margin` decimal(10,2) NOT NULL DEFAULT '9.00' COMMENT '(%) minimum margin that is acceptable when doing repricing',
  `reprice_value` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT 'value applied to our prices during reprice; can be negative/positive',
  `sourcefile_timestamp` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'timestamp of spider uploaded file (GMT+8)',
  `create_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `create_at` int(10) unsigned NOT NULL DEFAULT '2130706433' COMMENT 'IP address, default 127.0.0.1',
  `create_by` varchar(32) NOT NULL DEFAULT 'system',
  `modify_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `modify_at` int(10) unsigned NOT NULL DEFAULT '2130706433' COMMENT 'IP address',
  `modify_by` varchar(32) NOT NULL DEFAULT 'system',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_sku_compid` (`ext_sku`,`competitor_id`),
  KEY `fk_compid` (`competitor_id`),
  CONSTRAINT `fk_compid` FOREIGN KEY (`competitor_id`) REFERENCES `competitor` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8
CREATE TABLE `price` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sku` bigint(20) unsigned NOT NULL,
  `platform_id` char(5) NOT NULL,
  `default_shiptype` tinyint(4) NOT NULL DEFAULT '0',
  `sales_qty` int(11) NOT NULL DEFAULT '0',
  `price` decimal(15,2) unsigned NOT NULL DEFAULT '0.00',
  `vb_price` decimal(15,2) DEFAULT NULL,
  `status` char(1) NOT NULL DEFAULT 'I',
  `allow_express` char(1) NOT NULL DEFAULT 'N' COMMENT 'Y-Yes, N-No',
  `is_advertised` char(1) NOT NULL DEFAULT 'N' COMMENT 'Y-Yes,N-No',
  `google_promo_id` varchar(255) NOT NULL DEFAULT '',
  `ext_mapping_code` varchar(32) NOT NULL DEFAULT '',
  `latency` int(10) unsigned NOT NULL DEFAULT '0',
  `oos_latency` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'Out of stock latency for Amazon, etc.',
  `listing_status` varchar(2) NOT NULL DEFAULT 'N' COMMENT 'L - Listed, N - Not Listed, NC - No Content, NS - Not Suitable for Listing',
  `platform_code` varchar(20) NOT NULL DEFAULT '' COMMENT 'ASIN for Amazon, etc.',
  `max_order_qty` tinyint(3) unsigned NOT NULL DEFAULT '100',
  `auto_price` char(1) NOT NULL DEFAULT 'N' COMMENT 'N - No action, Y - Auto-price, C - Competitor reprice, M - Manual update, V - Valuebasket price',
  `fixed_rrp` char(1) NOT NULL DEFAULT 'Y',
  `rrp_factor` decimal(15,2) NOT NULL DEFAULT '1.34',
  `delivery_scenarioid` int(2) NOT NULL DEFAULT '1' COMMENT 'Join delvery_time tb with country_id to get delivery times to display',
  `create_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `create_at` int(10) unsigned NOT NULL DEFAULT '2130706433' COMMENT 'IP address, default 127.0.0.1',
  `create_by` varchar(32) NOT NULL DEFAULT 'system',
  `modify_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `modify_at` int(10) unsigned NOT NULL DEFAULT '2130706433' COMMENT 'IP address',
  `modify_by` varchar(32) NOT NULL DEFAULT 'system',
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_sku_platform` (`sku`,`platform_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT
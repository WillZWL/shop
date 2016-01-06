CREATE TABLE `cache_product_feed` (
  `sku` varchar(100) NOT NULL,
  `platform_id` varchar(7) NOT NULL,
  `prod_name` varchar(255) NOT NULL,
  `prod_url` text NOT NULL,
  `currency_id` char(3) NOT NULL,
  `price` double(15,2) unsigned DEFAULT '0.00',
  `promotion_price` double(15,2) unsigned DEFAULT '0.00',
  `bundle_price` double(15,2) unsigned DEFAULT '0.00',
  `shipping_cost` double(15,2) unsigned DEFAULT '0.00',
  `promo_text` text,
  `listing_status` varchar(2) NOT NULL DEFAULT 'N' COMMENT 'L - Listed, N - Not Listed, NC - No Content, NS - Not Suitable for Listing',
  `expiry_time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `create_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `create_at` int(10) unsigned NOT NULL DEFAULT '2130706433' COMMENT 'IP address, default 127.0.0.1',
  `create_by` varchar(32) NOT NULL DEFAULT 'system',
  `modify_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `modify_at` int(10) unsigned NOT NULL DEFAULT '2130706433' COMMENT 'IP address',
  `modify_by` varchar(32) NOT NULL DEFAULT 'system',
  PRIMARY KEY (`sku`,`platform_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8
CREATE TABLE `google_api_request` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `request_batch_id` bigint(20) NOT NULL,
  `platform_id` char(5) NOT NULL,
  `sku` bigint(20) NOT NULL,
  `item_group_id` int(11) NOT NULL,
  `google_product_id` varchar(32) NOT NULL,
  `colour_id` char(2) NOT NULL DEFAULT '',
  `colour_name` varchar(64) NOT NULL DEFAULT '',
  `target_country` varchar(2) NOT NULL,
  `content_language` varchar(2) NOT NULL,
  `title` varchar(256) NOT NULL,
  `google_product_category` varchar(256) NOT NULL DEFAULT '' COMMENT 'PAUSED, ENABLED',
  `product_type` varchar(512) NOT NULL,
  `cat_id` bigint(20) unsigned NOT NULL,
  `cat_name` varchar(64) NOT NULL DEFAULT '',
  `brand_name` varchar(32) NOT NULL,
  `gtin` varchar(128) NOT NULL DEFAULT '',
  `upc` varchar(128) NOT NULL DEFAULT '',
  `mpn` varchar(128) NOT NULL DEFAULT '',
  `ean` varchar(128) NOT NULL DEFAULT '',
  `shipping_weight_value` double(8,2) NOT NULL,
  `image_link` varchar(512) NOT NULL,
  `link` varchar(512) NOT NULL,
  `currency` varchar(3) NOT NULL,
  `price` double(15,2) NOT NULL,
  `description` text,
  `google_product_status` varchar(10) NOT NULL COMMENT 'I = Insert / D = Delete',
  `custom_attribute_promo_id` varchar(255) NOT NULL DEFAULT '',
  `ref_website_quantity` int(4) NOT NULL DEFAULT '0',
  `ref_display_quantity` int(4) NOT NULL DEFAULT '0',
  `ref_listing_status` varchar(1) NOT NULL,
  `ref_website_status` char(2) NOT NULL DEFAULT '',
  `ref_exdemo` tinyint(4) NOT NULL,
  `ref_is_advertised` char(1) NOT NULL DEFAULT '',
  `availability` varchar(32) NOT NULL DEFAULT '',
  `condition` varchar(32) NOT NULL DEFAULT '',
  `result` char(1) NOT NULL DEFAULT 'N' COMMENT 'F = Fail, S = Success, W = Success with Warning, N = NEW',
  `key_message` varchar(2048) NOT NULL DEFAULT '',
  `api_response` text,
  `create_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `create_at` int(10) unsigned NOT NULL DEFAULT '2130706433' COMMENT 'IP address, default 127.0.0.1',
  `create_by` varchar(32) NOT NULL DEFAULT 'system',
  `modify_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `modify_at` int(10) unsigned NOT NULL DEFAULT '2130706433' COMMENT 'IP address',
  `modify_by` varchar(32) NOT NULL DEFAULT 'system',
  PRIMARY KEY (`id`),
  KEY `idx_request_batch_id` (`request_batch_id`) USING BTREE,
  KEY `idx_request_result` (`result`) USING BTREE,
  KEY `idx_criteria` (`ref_website_quantity`,`ref_display_quantity`,`ref_listing_status`,`ref_website_status`,`ref_is_advertised`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8
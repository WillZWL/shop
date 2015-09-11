CREATE TABLE `product_warranty` (
  `id` int not null auto_increment,
  `sku` bigint unsigned NOT NULL,
  `platform_id` char(5) NOT NULL,
  `warranty_in_month` tinyint NOT NULL DEFAULT 0,
  `create_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `create_at` int(10) unsigned NOT NULL DEFAULT '2130706433' COMMENT 'IP address, default 127.0.0.1',
  `create_by` varchar(32) NOT NULL DEFAULT 'system',
  `modify_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `modify_at` int(10) unsigned NOT NULL DEFAULT '2130706433' COMMENT 'IP address',
  `modify_by` varchar(32) NOT NULL DEFAULT 'system',
  PRIMARY KEY (id),
  unique key idx_sku_platform (sku, platform_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

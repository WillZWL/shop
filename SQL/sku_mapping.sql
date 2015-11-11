CREATE TABLE `sku_mapping` (
  `id` int not null auto_increment,
  `sku` bigint unsigned NOT NULL,
  `ext_sys` char(3) NOT NULL DEFAULT '',
  `ext_sku` varchar(15) NOT NULL DEFAULT '',
  `status` tinyint(2) unsigned NOT NULL DEFAULT '1' COMMENT '0 = Inactive / 1 = Active',
  `create_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `create_at` int(10) unsigned NOT NULL DEFAULT '2130706433' COMMENT 'IP address, default 127.0.0.1',
  `create_by` varchar(32) NOT NULL DEFAULT 'system',
  `modify_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `modify_at` int(10) unsigned NOT NULL DEFAULT '2130706433' COMMENT 'IP address',
  `modify_by` varchar(32) NOT NULL DEFAULT 'system',
  PRIMARY KEY (`id`),
  unique key idx_mapping (sku, ext_sys, ext_sku)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

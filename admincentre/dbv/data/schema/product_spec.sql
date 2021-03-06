CREATE TABLE `product_spec` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `product_spec_id` varchar(255) NOT NULL DEFAULT '',
  `psg_id` int(10) unsigned NOT NULL COMMENT 'product_spec_group_id',
  `code` varchar(255) NOT NULL DEFAULT '',
  `name` varchar(64) NOT NULL DEFAULT '',
  `unit_type_id` varchar(25) NOT NULL,
  `status` tinyint(3) unsigned NOT NULL DEFAULT '1' COMMENT '0 = Inactive / 1 = Active',
  `create_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `create_at` int(10) unsigned NOT NULL DEFAULT '2130706433' COMMENT 'IP address, default 127.0.0.1',
  `create_by` varchar(32) NOT NULL DEFAULT 'system',
  `modify_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `modify_at` int(10) unsigned NOT NULL DEFAULT '2130706433' COMMENT 'IP address',
  `modify_by` varchar(32) NOT NULL DEFAULT 'system',
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_product_spec_id` (`product_spec_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8
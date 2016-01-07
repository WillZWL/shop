CREATE TABLE `product_type` (
  `sku` varchar(15) NOT NULL,
  `type_id` varchar(10) NOT NULL,
  `status` tinyint(2) NOT NULL COMMENT '0 = Inactive, 1 = Active',
  `create_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `create_at` int(10) unsigned NOT NULL DEFAULT '2130706433' COMMENT 'IP address, default 127.0.0.1',
  `create_by` varchar(32) NOT NULL DEFAULT 'system',
  `modify_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `modify_at` int(10) unsigned NOT NULL DEFAULT '2130706433' COMMENT 'IP address',
  `modify_by` varchar(32) NOT NULL DEFAULT 'system',
  PRIMARY KEY (`sku`,`type_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8
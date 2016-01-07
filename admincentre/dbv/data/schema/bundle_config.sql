CREATE TABLE `bundle_config` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `country_id` char(2) NOT NULL,
  `discount_1_item` double(5,2) NOT NULL DEFAULT '0.00',
  `discount_2_item` double(5,2) NOT NULL DEFAULT '0.00',
  `discount_3_more_item` double(5,2) NOT NULL DEFAULT '0.00',
  `status` char(1) NOT NULL DEFAULT '1',
  `create_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `create_at` int(10) unsigned NOT NULL DEFAULT '2130706433' COMMENT 'IP address, default 127.0.0.1',
  `create_by` varchar(32) NOT NULL DEFAULT 'system',
  `modify_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `modify_at` int(10) unsigned NOT NULL DEFAULT '2130706433' COMMENT 'IP address',
  `modify_by` varchar(32) NOT NULL DEFAULT 'system',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT
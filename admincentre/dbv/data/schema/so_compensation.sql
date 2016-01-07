CREATE TABLE `so_compensation` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `so_no` char(8) NOT NULL,
  `line_no` smallint(5) unsigned NOT NULL,
  `item_sku` varchar(15) NOT NULL,
  `qty` smallint(5) unsigned NOT NULL,
  `status` tinyint(2) unsigned NOT NULL DEFAULT '0' COMMENT '0 = Denied / 1 = Pending / 2 = Approved',
  `create_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `create_at` int(10) unsigned NOT NULL DEFAULT '2130706433' COMMENT 'IP address, default 127.0.0.1',
  `create_by` varchar(32) NOT NULL DEFAULT 'system',
  `modify_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `modify_at` int(10) unsigned NOT NULL DEFAULT '2130706433' COMMENT 'IP address',
  `modify_by` varchar(32) NOT NULL DEFAULT 'system',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8
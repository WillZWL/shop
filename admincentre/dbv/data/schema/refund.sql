CREATE TABLE `refund` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `so_no` char(8) NOT NULL,
  `reason` smallint(5) unsigned NOT NULL DEFAULT '0',
  `status` varchar(2) NOT NULL DEFAULT 'CS' COMMENT 'I - IN PROGRESS, C - COMPLETED ',
  `total_refund_amount` float(10,2) unsigned NOT NULL DEFAULT '0.00',
  `create_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `create_at` int(10) unsigned NOT NULL DEFAULT '2130706433' COMMENT 'IP address, default 127.0.0.1',
  `create_by` varchar(32) NOT NULL DEFAULT 'system',
  `modify_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `modify_at` int(10) unsigned NOT NULL DEFAULT '2130706433' COMMENT 'IP address',
  `modify_by` varchar(32) NOT NULL DEFAULT 'system',
  PRIMARY KEY (`id`),
  KEY `fk_refund_so` (`so_no`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT
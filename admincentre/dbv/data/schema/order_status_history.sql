CREATE TABLE `order_status_history` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `so_no` char(8) NOT NULL,
  `status` tinyint(2) unsigned NOT NULL,
  `create_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `create_at` int(10) unsigned NOT NULL DEFAULT '2130706433' COMMENT 'IP address, default 127.0.0.1',
  `create_by` varchar(32) NOT NULL DEFAULT 'system',
  `modify_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `modify_at` int(10) unsigned NOT NULL DEFAULT '2130706433' COMMENT 'IP address',
  `modify_by` varchar(32) NOT NULL DEFAULT 'system',
  PRIMARY KEY (`id`),
  KEY `fk_sos_so_no` (`so_no`) USING BTREE,
  CONSTRAINT `fk_sos_so_no` FOREIGN KEY (`so_no`) REFERENCES `so_copy` (`so_no`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT
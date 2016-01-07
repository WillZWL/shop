CREATE TABLE `banner` (
  `cat_id` bigint(20) unsigned NOT NULL,
  `type` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `usage` varchar(2) NOT NULL DEFAULT 'PV' COMMENT 'PV-Preview, PB- Published',
  `image_file` varchar(32) DEFAULT NULL,
  `flash_file` varchar(32) DEFAULT NULL,
  `link` varchar(100) DEFAULT NULL,
  `link_type` char(1) NOT NULL DEFAULT 'E' COMMENT 'E - External, I - Internal',
  `status` char(1) NOT NULL DEFAULT 'A' COMMENT 'A- Active, I - Inactive',
  `create_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `create_at` int(10) unsigned NOT NULL DEFAULT '2130706433' COMMENT 'IP address, default 127.0.0.1',
  `create_by` varchar(32) NOT NULL DEFAULT 'system',
  `modify_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `modify_at` int(10) unsigned NOT NULL DEFAULT '2130706433' COMMENT 'IP address',
  `modify_by` varchar(32) NOT NULL DEFAULT 'system',
  PRIMARY KEY (`cat_id`,`type`,`usage`),
  KEY `fk_banner_cat` (`cat_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT
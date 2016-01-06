CREATE TABLE `ra_group` (
  `group_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `group_name` varchar(100) NOT NULL,
  `status` tinyint(2) NOT NULL DEFAULT '1' COMMENT '0 = Inactive, 1 = Active',
  `warranty` tinyint(2) NOT NULL DEFAULT '0' COMMENT '0 = No , 1 = Yes',
  `ignore_qty_bundle` tinyint(2) NOT NULL DEFAULT '0' COMMENT 'Ignore qty for bundle display, 0=No, 1=Ignore',
  `create_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `create_at` int(10) unsigned NOT NULL DEFAULT '2130706433' COMMENT 'IP address, default 127.0.0.1',
  `create_by` varchar(32) NOT NULL DEFAULT 'system',
  `modify_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `modify_at` int(10) unsigned NOT NULL DEFAULT '2130706433' COMMENT 'IP address',
  `modify_by` varchar(32) NOT NULL DEFAULT 'system',
  PRIMARY KEY (`group_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT
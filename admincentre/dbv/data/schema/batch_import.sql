CREATE TABLE `batch_import` (
  `batch_id` int(11) NOT NULL AUTO_INCREMENT,
  `function_name` varchar(128) NOT NULL,
  `status` smallint(1) NOT NULL COMMENT '0=new, 1=processed & success, 2=processed & fail, 3=error and wait user action, 4=cancelled',
  `remark` varchar(256) DEFAULT NULL,
  `end_time` timestamp NULL DEFAULT '0000-00-00 00:00:00',
  `create_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `create_at` int(10) unsigned NOT NULL DEFAULT '2130706433' COMMENT 'IP address, default 127.0.0.1',
  `create_by` varchar(32) NOT NULL DEFAULT 'system',
  `modify_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `modify_at` int(10) unsigned NOT NULL DEFAULT '2130706433' COMMENT 'IP address',
  `modify_by` varchar(32) NOT NULL DEFAULT 'system',
  PRIMARY KEY (`batch_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8
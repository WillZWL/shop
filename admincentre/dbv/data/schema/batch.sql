CREATE TABLE `batch` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `func_name` varchar(255) NOT NULL,
  `status` varchar(2) NOT NULL DEFAULT 'N' COMMENT 'N = New / P = Processing / C = Completed / CE = Completed with Error / BE = Broke with Error / RP = ReProcessing',
  `listed` tinyint(2) NOT NULL DEFAULT '1' COMMENT '0 = Not Listed / 1 = Listed',
  `remark` varchar(255) DEFAULT NULL,
  `end_time` datetime DEFAULT NULL,
  `create_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `create_at` int(10) unsigned NOT NULL DEFAULT '2130706433' COMMENT 'IP address, default 127.0.0.1',
  `create_by` varchar(32) NOT NULL DEFAULT 'system',
  `modify_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `modify_at` int(10) unsigned NOT NULL DEFAULT '2130706433' COMMENT 'IP address',
  `modify_by` varchar(32) NOT NULL DEFAULT 'system',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT
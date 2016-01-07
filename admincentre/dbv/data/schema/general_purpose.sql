CREATE TABLE `general_purpose` (
  `so_no` char(8) NOT NULL,
  `purpose` varchar(30) NOT NULL COMMENT 'AS=aftership shipment status',
  `status` tinyint(3) NOT NULL DEFAULT '0' COMMENT ' AS{1=>Pending,2=>InfoReceived,3=>InTransit,4=>OutForDelivery,5=>AttemptFail,6=>Delivered,7=>Exception,8=>Expired}',
  `comment` varchar(255) DEFAULT NULL,
  `create_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `create_at` int(10) unsigned NOT NULL DEFAULT '2130706433' COMMENT 'IP address, default 127.0.0.1',
  `create_by` varchar(32) NOT NULL DEFAULT 'system',
  `modify_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `modify_at` int(10) unsigned NOT NULL DEFAULT '2130706433' COMMENT 'IP address',
  `modify_by` varchar(32) NOT NULL DEFAULT 'system',
  PRIMARY KEY (`so_no`,`purpose`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='currently this is used to track the latest aftership shipmen'
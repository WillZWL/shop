CREATE TABLE `courier` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `courier_id` varchar(32) NOT NULL DEFAULT '',
  `aftership_id` varchar(32) NOT NULL DEFAULT '',
  `courier_name` varchar(32) NOT NULL DEFAULT '',
  `description` varchar(255) NOT NULL DEFAULT '',
  `type` char(1) NOT NULL DEFAULT 'F' COMMENT 'F = Freight / W = Weight / R = Real Courier',
  `tracking_link` varchar(255) NOT NULL DEFAULT '',
  `weight_type` varchar(2) NOT NULL DEFAULT '' COMMENT 'CH = Charge / CO = Cost / B = Both',
  `show_status` tinyint(2) NOT NULL DEFAULT '0',
  `create_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `create_at` int(10) unsigned NOT NULL DEFAULT '2130706433' COMMENT 'IP address, default 127.0.0.1',
  `create_by` varchar(32) NOT NULL DEFAULT 'system',
  `modify_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `modify_at` int(10) unsigned NOT NULL DEFAULT '2130706433' COMMENT 'IP address',
  `modify_by` varchar(32) NOT NULL DEFAULT 'system',
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_courier_id` (`courier_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8
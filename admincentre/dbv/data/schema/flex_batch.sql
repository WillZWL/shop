CREATE TABLE `flex_batch` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `gateway_id` varchar(255) NOT NULL,
  `filename` varchar(255) DEFAULT NULL,
  `status` varchar(2) NOT NULL DEFAULT 'N' COMMENT 'N = New / P = Processing / C = Completed',
  `create_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `create_at` int(10) unsigned NOT NULL DEFAULT '2130706433' COMMENT 'IP address, default 127.0.0.1',
  `create_by` varchar(32) NOT NULL DEFAULT 'system',
  `modify_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `modify_at` int(10) unsigned NOT NULL DEFAULT '2130706433' COMMENT 'IP address',
  `modify_by` varchar(32) NOT NULL DEFAULT 'system',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8
CREATE TABLE `pricing_rules` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `country_id` char(2) NOT NULL,
  `range_min` decimal(15,2) NOT NULL DEFAULT '0.00',
  `range_max` decimal(15,2) NOT NULL DEFAULT '0.00',
  `mark_up_value` decimal(10,2) NOT NULL DEFAULT '0.00',
  `mark_up_type` char(1) NOT NULL COMMENT 'P: percentage; A:absolute number',
  `min_margin` double(15,2) NOT NULL DEFAULT '0.00',
  `monday` tinyint(1) NOT NULL DEFAULT '0',
  `tuesday` tinyint(1) NOT NULL DEFAULT '0',
  `wednesday` tinyint(1) NOT NULL DEFAULT '0',
  `thursday` tinyint(1) NOT NULL DEFAULT '0',
  `friday` tinyint(1) NOT NULL DEFAULT '0',
  `saturday` tinyint(1) NOT NULL DEFAULT '0',
  `sunday` tinyint(1) NOT NULL DEFAULT '0',
  `status` char(1) NOT NULL DEFAULT '1',
  `create_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `create_at` int(10) unsigned NOT NULL DEFAULT '2130706433' COMMENT 'IP address, default 127.0.0.1',
  `create_by` varchar(32) NOT NULL DEFAULT 'system',
  `modify_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `modify_at` int(10) unsigned NOT NULL DEFAULT '2130706433' COMMENT 'IP address',
  `modify_by` varchar(32) NOT NULL DEFAULT 'system',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT
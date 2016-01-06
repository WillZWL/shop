CREATE TABLE `exchange_rate_flex` (
  `from_currency_id` char(3) NOT NULL,
  `to_currency_id` char(3) NOT NULL,
  `rate` double(9,4) unsigned NOT NULL DEFAULT '1.0000',
  `approvial_status` tinyint(2) unsigned NOT NULL DEFAULT '1' COMMENT '0 = Not yet approved / 1 = Approved',
  `create_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `create_at` int(10) unsigned NOT NULL DEFAULT '2130706433' COMMENT 'IP address, default 127.0.0.1',
  `create_by` varchar(32) NOT NULL DEFAULT 'system',
  `modify_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `modify_at` int(10) unsigned NOT NULL DEFAULT '2130706433' COMMENT 'IP address',
  `modify_by` varchar(32) NOT NULL DEFAULT 'system',
  PRIMARY KEY (`from_currency_id`,`to_currency_id`),
  KEY `to_flex_idx` (`to_currency_id`),
  CONSTRAINT `from_flex_idx` FOREIGN KEY (`from_currency_id`) REFERENCES `currency_copy` (`id`) ON DELETE CASCADE,
  CONSTRAINT `to_flex_idx` FOREIGN KEY (`to_currency_id`) REFERENCES `currency_copy` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8
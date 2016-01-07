CREATE TABLE `flex_gateway_mapping` (
  `gateway_id` varchar(20) NOT NULL,
  `currency_id` char(3) NOT NULL,
  `gateway_code` varchar(20) NOT NULL,
  `ria` tinyint(2) NOT NULL DEFAULT '1' COMMENT '0 = Not RIA, 1 = RIA ',
  `create_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `create_at` int(10) unsigned NOT NULL DEFAULT '2130706433' COMMENT 'IP address, default 127.0.0.1',
  `create_by` varchar(32) NOT NULL DEFAULT 'system',
  `modify_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `modify_at` int(10) unsigned NOT NULL DEFAULT '2130706433' COMMENT 'IP address',
  `modify_by` varchar(32) NOT NULL DEFAULT 'system',
  PRIMARY KEY (`gateway_id`,`currency_id`),
  KEY `fk_fgm_currency_id` (`currency_id`),
  CONSTRAINT `fk_fgm_currency_id` FOREIGN KEY (`currency_id`) REFERENCES `currency_copy` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8
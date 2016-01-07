CREATE TABLE `platform_pmgw` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `platform_id` varchar(7) NOT NULL,
  `sequence` tinyint(4) NOT NULL DEFAULT '1',
  `payment_gateway_id` varchar(20) NOT NULL DEFAULT 'paypal',
  `pmgw_ref_currency_id` char(3) NOT NULL DEFAULT '',
  `ref_from_amt` double NOT NULL DEFAULT '0',
  `ref_to_amt_exclusive` double NOT NULL DEFAULT '0',
  `status` tinyint(2) unsigned NOT NULL DEFAULT '1' COMMENT '0 = Inactive / 1 = Active',
  `create_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `create_at` int(10) unsigned NOT NULL DEFAULT '2130706433' COMMENT 'IP address, default 127.0.0.1',
  `create_by` varchar(32) NOT NULL DEFAULT 'system',
  `modify_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `modify_at` int(10) unsigned NOT NULL DEFAULT '2130706433' COMMENT 'IP address',
  `modify_by` varchar(32) NOT NULL DEFAULT 'system',
  PRIMARY KEY (`id`),
  KEY `idx_gateway_platform` (`payment_gateway_id`,`platform_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT
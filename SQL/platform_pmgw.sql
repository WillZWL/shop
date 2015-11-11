CREATE TABLE `platform_pmgw` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `platform_id` VARCHAR(7) NOT NULL,
  `sequence` TINYINT(4) NOT NULL DEFAULT '1',
  `payment_gateway_id` VARCHAR(20) NOT NULL DEFAULT 'paypal',
  `pmgw_ref_currency_id` CHAR(3) NOT NULL DEFAULT '',
  `ref_from_amt` DOUBLE NOT NULL DEFAULT '0.00',
  `ref_to_amt_exclusive` DOUBLE NOT NULL DEFAULT '0.00',
  `status` TINYINT(2) UNSIGNED NOT NULL DEFAULT '1' COMMENT '0 = Inactive / 1 = Active',
  `create_on` TIMESTAMP NOT NULL DEFAULT '0000-00-00 00:00:00',
  `create_at` INT(10) UNSIGNED NOT NULL DEFAULT '2130706433' COMMENT 'IP address, default 127.0.0.1',
  `create_by` VARCHAR(32) NOT NULL DEFAULT 'system',
  `modify_on` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `modify_at` INT(10) UNSIGNED NOT NULL DEFAULT '2130706433' COMMENT 'IP address',
  `modify_by` VARCHAR(32) NOT NULL DEFAULT 'system',
  PRIMARY KEY (id),
  KEY idx_gateway_platform (`payment_gateway_id`,`platform_id`)
) ENGINE=INNODB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;
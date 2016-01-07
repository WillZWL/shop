CREATE TABLE `bank_account` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `acc_no` varchar(255) NOT NULL,
  `status` int(2) NOT NULL COMMENT '0 = inactive / 1 = active',
  `currency_id` char(3) NOT NULL,
  `timezone_gmt` int(11) NOT NULL DEFAULT '0' COMMENT 'timezone in GMT, e.g. +8',
  `create_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `create_at` int(10) unsigned NOT NULL DEFAULT '2130706433' COMMENT 'IP address, default 127.0.0.1',
  `create_by` varchar(32) NOT NULL DEFAULT 'system',
  `modify_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `modify_at` int(10) unsigned NOT NULL DEFAULT '2130706433' COMMENT 'IP address',
  `modify_by` varchar(32) NOT NULL DEFAULT 'system',
  PRIMARY KEY (`id`,`acc_no`),
  UNIQUE KEY `bankacc_accno` (`acc_no`) USING BTREE,
  UNIQUE KEY `bankacc_id` (`id`),
  KEY `fk_bankacc_curr` (`currency_id`),
  CONSTRAINT `fk_bankacc_curr` FOREIGN KEY (`currency_id`) REFERENCES `currency_copy` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8
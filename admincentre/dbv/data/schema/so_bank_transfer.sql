CREATE TABLE `so_bank_transfer` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `so_no` char(8) DEFAULT NULL,
  `sbt_status` int(2) NOT NULL DEFAULT '0' COMMENT 'status. 0 = Inactive / 1 = Active',
  `net_diff_status` int(2) NOT NULL COMMENT 'Net diff btw total received and order amt. 1 - Fully paid / 2 - Underpaid <= 1% / 3 - Underpaid > 1% / 4 - Overpaid / 5 - Unknown',
  `ext_ref_no` varchar(255) NOT NULL COMMENT 'bank ref / sales ref',
  `received_amt_localcurr` decimal(15,2) NOT NULL DEFAULT '0.00' COMMENT 'net amt received, in local currency of bank acc',
  `bank_account_id` int(11) NOT NULL COMMENT 'account id that funds were received in',
  `received_date_localtime` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'date of receipt in bank acc''s local timezone',
  `bank_charge` decimal(15,2) DEFAULT NULL,
  `notes` varchar(255) DEFAULT NULL COMMENT 'Other notes/comments.',
  `create_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `create_at` int(10) unsigned NOT NULL DEFAULT '2130706433' COMMENT 'IP address, default 127.0.0.1',
  `create_by` varchar(32) NOT NULL DEFAULT 'system',
  `modify_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `modify_at` int(10) unsigned NOT NULL DEFAULT '2130706433' COMMENT 'IP address',
  `modify_by` varchar(32) NOT NULL DEFAULT 'system',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_sobt_ext_ref_no` (`ext_ref_no`) USING BTREE,
  KEY `fk_bt_so` (`so_no`),
  KEY `fk_bt_acc_id` (`bank_account_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8
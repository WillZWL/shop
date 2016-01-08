CREATE TABLE `interface_flex_pmgw_transactions` (
  `trans_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `batch_id` bigint(20) unsigned NOT NULL,
  `so_no` char(8) DEFAULT NULL,
  `payment_gateway_id` varchar(20) DEFAULT NULL,
  `txn_id` varchar(100) DEFAULT NULL COMMENT 'Transaction id',
  `payment_type` varchar(2) DEFAULT NULL COMMENT 'S = SETTLEED, C = CHARGEBACK, R=REFUND',
  `txn_time` datetime DEFAULT NULL,
  `currency_id` char(3) DEFAULT NULL,
  `amount` double(15,2) unsigned DEFAULT NULL,
  `commission` double(15,2) unsigned DEFAULT '0.00',
  `ext_ref` text,
  `batch_status` varchar(2) NOT NULL DEFAULT '' COMMENT 'N = New / R = Ready update to master / S = Success / F = Failed / I = Investigated',
  `failed_reason` text,
  `create_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `create_at` int(10) unsigned NOT NULL DEFAULT '2130706433' COMMENT 'IP address, default 127.0.0.1',
  `create_by` varchar(32) NOT NULL DEFAULT 'system',
  `modify_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `modify_at` int(10) unsigned NOT NULL DEFAULT '2130706433' COMMENT 'IP address',
  `modify_by` varchar(32) NOT NULL DEFAULT 'system',
  PRIMARY KEY (`trans_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8
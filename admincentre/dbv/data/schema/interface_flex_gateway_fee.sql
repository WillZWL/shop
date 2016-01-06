CREATE TABLE `interface_flex_gateway_fee` (
  `trans_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `txn_id` varchar(100) DEFAULT NULL COMMENT 'Transaction id',
  `flex_batch_id` bigint(20) unsigned NOT NULL,
  `gateway_id` varchar(255) NOT NULL,
  `txn_time` datetime NOT NULL,
  `currency_id` char(3) NOT NULL,
  `amount` double(15,6) NOT NULL,
  `status` varchar(10) NOT NULL COMMENT 'FXI = Currency In, FXO = Currency Out, PS = Payment Sent',
  `batch_status` varchar(2) NOT NULL DEFAULT '' COMMENT 'N = New / R = Ready update to master / S = Success / F = Failed / I = Investigated',
  `failed_reason` text,
  `create_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `create_at` int(10) unsigned NOT NULL DEFAULT '2130706433' COMMENT 'IP address, default 127.0.0.1',
  `create_by` varchar(32) NOT NULL DEFAULT 'system',
  `modify_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `modify_at` int(10) unsigned NOT NULL DEFAULT '2130706433' COMMENT 'IP address',
  `modify_by` varchar(32) NOT NULL DEFAULT 'system',
  PRIMARY KEY (`trans_id`,`txn_time`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8
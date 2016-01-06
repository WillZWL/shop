CREATE TABLE `flex_gateway_fee` (
  `txn_id` varchar(100) NOT NULL DEFAULT '' COMMENT 'Transaction id',
  `flex_batch_id` bigint(20) unsigned NOT NULL,
  `gateway_id` varchar(255) NOT NULL,
  `txn_time` datetime NOT NULL,
  `currency_id` char(3) NOT NULL,
  `amount` double(15,6) NOT NULL,
  `status` varchar(5) NOT NULL COMMENT 'FXI = Exchange In, FXO = Exchange Out, PS = Payment Sent',
  `create_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `create_at` int(10) unsigned NOT NULL DEFAULT '2130706433' COMMENT 'IP address, default 127.0.0.1',
  `create_by` varchar(32) NOT NULL DEFAULT 'system',
  `modify_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `modify_at` int(10) unsigned NOT NULL DEFAULT '2130706433' COMMENT 'IP address',
  `modify_by` varchar(32) NOT NULL DEFAULT 'system',
  PRIMARY KEY (`txn_id`,`txn_time`,`status`),
  KEY `fk_fgf_flex_batch_id` (`flex_batch_id`),
  CONSTRAINT `fk_fgf_flex_batch_id` FOREIGN KEY (`flex_batch_id`) REFERENCES `flex_batch` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8
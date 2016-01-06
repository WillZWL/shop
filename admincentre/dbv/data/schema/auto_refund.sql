CREATE TABLE `auto_refund` (
  `refund_id` bigint(20) NOT NULL,
  `so_no` varchar(8) NOT NULL,
  `payment_gateway_id` varchar(20) NOT NULL,
  `action` varchar(2) NOT NULL COMMENT 'R - Waiting to send Request, I - Request sent in progress, C - refund completed, CE - Completed with Error, IT - Need IT to investigate',
  `amount` float(10,2) NOT NULL,
  `log_out` text,
  `log_in` text,
  `create_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `create_at` int(10) unsigned NOT NULL DEFAULT '2130706433' COMMENT 'IP address, default 127.0.0.1',
  `create_by` varchar(32) NOT NULL DEFAULT 'system',
  `modify_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `modify_at` int(10) unsigned NOT NULL DEFAULT '2130706433' COMMENT 'IP address',
  `modify_by` varchar(32) NOT NULL DEFAULT 'system',
  PRIMARY KEY (`refund_id`),
  KEY `idx_auto_refund_so_no` (`so_no`) USING BTREE,
  KEY `idx_auto_refund_payment_gateway_id` (`payment_gateway_id`) USING BTREE,
  KEY `idx_auto_refund_action` (`action`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8
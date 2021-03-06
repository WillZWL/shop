CREATE TABLE `risk_ref` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `payment_gateway_id` varchar(20) NOT NULL,
  `risk_ref` varchar(64) NOT NULL,
  `risk_ref_desc` varchar(255) NOT NULL DEFAULT '',
  `action` varchar(2) NOT NULL DEFAULT '' COMMENT 'P = Pass / F = Fail / CC = Credit Check',
  `create_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `create_at` int(10) unsigned NOT NULL DEFAULT '2130706433' COMMENT 'IP address, default 127.0.0.1',
  `create_by` varchar(32) NOT NULL DEFAULT 'system',
  `modify_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `modify_at` int(10) unsigned NOT NULL DEFAULT '2130706433' COMMENT 'IP address',
  `modify_by` varchar(32) NOT NULL DEFAULT 'system',
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_risk_gateway` (`risk_ref`,`payment_gateway_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8
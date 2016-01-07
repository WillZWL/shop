CREATE TABLE `exchange_rate_history` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `from_currency_id` char(3) NOT NULL,
  `to_currency_id` char(3) NOT NULL,
  `rate` double(11,6) unsigned NOT NULL DEFAULT '1.000000',
  `date` datetime DEFAULT NULL,
  `create_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `create_at` int(10) unsigned NOT NULL DEFAULT '2130706433' COMMENT 'IP address, default 127.0.0.1',
  `create_by` varchar(32) NOT NULL DEFAULT 'system',
  `modify_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `modify_at` int(10) unsigned NOT NULL DEFAULT '2130706433' COMMENT 'IP address',
  `modify_by` varchar(32) NOT NULL DEFAULT 'system',
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_currency_id` (`from_currency_id`,`to_currency_id`,`date`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8
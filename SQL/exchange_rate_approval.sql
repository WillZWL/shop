CREATE TABLE `exchange_rate_approval` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `from_currency_id` char(3) NOT NULL,
  `to_currency_id` char(3) NOT NULL,
  `rate` double(11,6) unsigned NOT NULL DEFAULT '1.000000',
  `approval_status` tinyint(2) unsigned NOT NULL DEFAULT '1' COMMENT '0 = Not yet approved / 1 = Approved',
  `create_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `create_at` int(10) unsigned NOT NULL DEFAULT '2130706433' COMMENT 'IP address, default 127.0.0.1',
  `create_by` varchar(32) NOT NULL DEFAULT 'system',
  `modify_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `modify_at` int(10) unsigned NOT NULL DEFAULT '2130706433' COMMENT 'IP address',
  `modify_by` varchar(32) NOT NULL DEFAULT 'system',
  PRIMARY KEY (`id`),
  unique key idx_currency_id (from_currency_id, to_currency_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
CREATE TABLE `chargeback` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `so_no` char(8) NOT NULL,
  `chargeback_status_id` int(11) NOT NULL COMMENT 'refer to lookup_chargeback_status',
  `chargeback_reason_id` int(11) NOT NULL,
  `chargeback_reason` text NOT NULL,
  `chargeback_remark_id` int(11) NOT NULL,
  `chargeback_remark` text NOT NULL,
  `document` text NOT NULL,
  `create_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `create_at` int(10) unsigned NOT NULL DEFAULT '2130706433' COMMENT 'IP address, default 127.0.0.1',
  `create_by` varchar(32) NOT NULL DEFAULT 'system',
  `modify_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  `modify_at` int(10) unsigned NOT NULL DEFAULT '2130706433' COMMENT 'IP address',
  `modify_by` varchar(32) NOT NULL DEFAULT 'system',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8
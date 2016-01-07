CREATE TABLE `interface_t3m_score` (
  `trans_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `batch_id` bigint(20) unsigned NOT NULL,
  `so_no` varchar(8) NOT NULL,
  `client_id` bigint(20) unsigned NOT NULL,
  `t3m_score` varchar(10) NOT NULL DEFAULT '0',
  `batch_status` varchar(2) NOT NULL DEFAULT '' COMMENT 'N = New / R = Ready update to master / S = Success / F = Failed / I = Investigated',
  `failed_reason` varchar(255) DEFAULT NULL,
  `create_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `create_at` int(10) unsigned NOT NULL DEFAULT '2130706433' COMMENT 'IP address, default 127.0.0.1',
  `create_by` varchar(32) NOT NULL DEFAULT 'system',
  `modify_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `modify_at` int(10) unsigned NOT NULL DEFAULT '2130706433' COMMENT 'IP address',
  `modify_by` varchar(32) NOT NULL DEFAULT 'system',
  PRIMARY KEY (`trans_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT
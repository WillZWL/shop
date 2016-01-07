CREATE TABLE `refund_history` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `refund_id` bigint(20) unsigned NOT NULL,
  `status` varchar(2) NOT NULL DEFAULT 'CS' COMMENT 'N - NEW, CS - CS, CP - COMPLIANCE, LG - LOGISTICS, AC- ACCOUNT, D - DENIED, C - COMPLETED',
  `app_status` char(2) DEFAULT NULL COMMENT 'D- Denied, A-Approved, AD - Both',
  `notes` text,
  `create_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `create_at` int(10) unsigned NOT NULL DEFAULT '2130706433' COMMENT 'IP address, default 127.0.0.1',
  `create_by` varchar(32) NOT NULL DEFAULT 'system',
  `modify_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `modify_at` int(10) unsigned NOT NULL DEFAULT '2130706433' COMMENT 'IP address',
  `modify_by` varchar(32) NOT NULL DEFAULT 'system',
  PRIMARY KEY (`id`),
  KEY `fk_refund_id` (`refund_id`) USING BTREE,
  CONSTRAINT `fk_refund_id` FOREIGN KEY (`refund_id`) REFERENCES `refund` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT
CREATE TABLE `rma_fc` (
  `cid` char(2) NOT NULL COMMENT 'International country code (2 characters)',
  `rma_fc` varchar(20) DEFAULT NULL,
  `create_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `create_at` int(10) unsigned NOT NULL DEFAULT '2130706433' COMMENT 'IP address, default 127.0.0.1',
  `create_by` varchar(32) NOT NULL DEFAULT 'system',
  `modify_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `modify_at` int(10) unsigned NOT NULL DEFAULT '2130706433' COMMENT 'IP address',
  `modify_by` varchar(32) NOT NULL DEFAULT 'system',
  PRIMARY KEY (`cid`),
  KEY `fk_rf_country_id` (`cid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8
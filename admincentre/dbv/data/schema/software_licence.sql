CREATE TABLE `software_licence` (
  `sku` varchar(15) NOT NULL,
  `key` varchar(255) NOT NULL,
  `link` varchar(255) DEFAULT NULL,
  `distributed` tinyint(2) unsigned NOT NULL DEFAULT '0' COMMENT '0 = No / 1 = Yes',
  `status` tinyint(2) unsigned NOT NULL DEFAULT '1' COMMENT '0 = Inactive / 1 = Active',
  `create_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `create_at` int(10) unsigned NOT NULL DEFAULT '2130706433' COMMENT 'IP address, default 127.0.0.1',
  `create_by` varchar(32) NOT NULL DEFAULT 'system',
  `modify_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `modify_at` int(10) unsigned NOT NULL DEFAULT '2130706433' COMMENT 'IP address',
  `modify_by` varchar(32) NOT NULL DEFAULT 'system',
  PRIMARY KEY (`sku`,`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8
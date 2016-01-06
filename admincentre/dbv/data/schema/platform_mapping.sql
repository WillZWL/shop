CREATE TABLE `platform_mapping` (
  `ext_system` char(6) NOT NULL,
  `ext_mapping_key` varchar(20) NOT NULL,
  `ext_remark` varchar(15) DEFAULT NULL,
  `selling_platform` varchar(7) NOT NULL,
  `account` varchar(32) DEFAULT NULL,
  `create_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `create_at` int(10) unsigned NOT NULL DEFAULT '2130706433' COMMENT 'IP address, default 127.0.0.1',
  `create_by` varchar(32) NOT NULL DEFAULT 'system',
  `modify_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `modify_at` int(10) unsigned NOT NULL DEFAULT '2130706433' COMMENT 'IP address',
  `modify_by` varchar(32) NOT NULL DEFAULT 'system',
  PRIMARY KEY (`ext_system`,`ext_mapping_key`),
  KEY `fk_pm_sp` (`selling_platform`),
  CONSTRAINT `fk_pm_sp` FOREIGN KEY (`selling_platform`) REFERENCES `selling_platform_copy` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8
CREATE TABLE `domain_platform` (
  `domain` varchar(255) NOT NULL COMMENT '% = Default for all',
  `platform_id` varchar(7) NOT NULL,
  `site_name` varchar(255) DEFAULT NULL,
  `short_name` varchar(4) DEFAULT NULL,
  `domain_type` tinyint(2) DEFAULT '1' COMMENT '-1 = Development / 0 = Multiple / 1 = Production',
  `status` tinyint(2) unsigned NOT NULL DEFAULT '1' COMMENT '0 = Inactive / 1 = Active',
  `create_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `create_at` int(10) unsigned NOT NULL DEFAULT '2130706433' COMMENT 'IP address, default 127.0.0.1',
  `create_by` varchar(32) NOT NULL DEFAULT 'system',
  `modify_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `modify_at` int(10) unsigned NOT NULL DEFAULT '2130706433' COMMENT 'IP address',
  `modify_by` varchar(32) NOT NULL DEFAULT 'system',
  PRIMARY KEY (`domain`),
  KEY `fk_dp_pid` (`platform_id`),
  CONSTRAINT `fk_dp_pid` FOREIGN KEY (`platform_id`) REFERENCES `selling_platform_copy` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8
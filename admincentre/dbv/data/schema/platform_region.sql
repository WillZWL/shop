CREATE TABLE `platform_region` (
  `platform_id` varchar(7) NOT NULL,
  `region_id` bigint(20) unsigned NOT NULL,
  `create_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `create_at` int(10) unsigned NOT NULL DEFAULT '2130706433' COMMENT 'IP address, default 127.0.0.1',
  `create_by` varchar(32) NOT NULL DEFAULT 'system',
  `modify_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `modify_at` int(10) unsigned NOT NULL DEFAULT '2130706433' COMMENT 'IP address',
  `modify_by` varchar(32) NOT NULL DEFAULT 'system',
  PRIMARY KEY (`platform_id`,`region_id`),
  KEY `fk_pr_region_id` (`region_id`),
  CONSTRAINT `fk_pr_platform_id` FOREIGN KEY (`platform_id`) REFERENCES `selling_platform_copy` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `fk_pr_region_id` FOREIGN KEY (`region_id`) REFERENCES `region` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8
CREATE TABLE `country_state` (
  `country_id` char(2) NOT NULL COMMENT 'International country code (2 characters)',
  `name` varchar(64) NOT NULL DEFAULT '' COMMENT 'State/Possession Name',
  `state_id` varchar(5) NOT NULL COMMENT 'State/Possession Abbreviation',
  `status` tinyint(2) NOT NULL COMMENT '0 = Inactive, 1 = Active',
  `create_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `create_at` int(10) unsigned NOT NULL DEFAULT '2130706433' COMMENT 'IP address, default 127.0.0.1',
  `create_by` varchar(32) NOT NULL DEFAULT 'system',
  `modify_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `modify_at` int(10) unsigned NOT NULL DEFAULT '2130706433' COMMENT 'IP address',
  `modify_by` varchar(32) NOT NULL DEFAULT 'system',
  PRIMARY KEY (`country_id`,`name`),
  CONSTRAINT `fk_cs_country_id` FOREIGN KEY (`country_id`) REFERENCES `country_copy` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8
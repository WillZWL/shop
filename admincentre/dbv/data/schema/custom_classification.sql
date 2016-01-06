CREATE TABLE `custom_classification` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `country_id` char(2) NOT NULL COMMENT 'International country code (2 characters)',
  `code` varchar(20) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `duty_pcent` double(5,2) NOT NULL DEFAULT '0.00',
  `create_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `create_at` int(10) unsigned NOT NULL DEFAULT '2130706433' COMMENT 'IP address, default 127.0.0.1',
  `create_by` varchar(32) NOT NULL DEFAULT 'system',
  `modify_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `modify_at` int(10) unsigned NOT NULL DEFAULT '2130706433' COMMENT 'IP address',
  `modify_by` varchar(32) NOT NULL DEFAULT 'system',
  PRIMARY KEY (`id`),
  KEY `fk_cc_country_id` (`country_id`) USING BTREE,
  CONSTRAINT `fk_cc_country_id` FOREIGN KEY (`country_id`) REFERENCES `country_copy` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8
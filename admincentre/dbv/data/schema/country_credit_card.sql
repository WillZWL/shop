CREATE TABLE `country_credit_card` (
  `country_id` char(2) NOT NULL,
  `card_code` varchar(20) NOT NULL,
  `priority` tinyint(3) unsigned DEFAULT '9',
  `forcing_with_condition` smallint(2) DEFAULT NULL,
  `status` tinyint(2) unsigned NOT NULL DEFAULT '1' COMMENT '0 = Inactive / 1 = Active',
  `create_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `create_at` int(10) unsigned NOT NULL DEFAULT '2130706433' COMMENT 'IP address, default 127.0.0.1',
  `create_by` varchar(32) NOT NULL DEFAULT 'system',
  `modify_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `modify_at` int(10) unsigned NOT NULL DEFAULT '2130706433' COMMENT 'IP address',
  `modify_by` varchar(32) NOT NULL DEFAULT 'system',
  PRIMARY KEY (`country_id`,`card_code`),
  KEY `fk_ccc_ccd` (`card_code`),
  CONSTRAINT `fk_ccc_ccd` FOREIGN KEY (`card_code`) REFERENCES `pmgw_card` (`code`),
  CONSTRAINT `fk_ccc_country_id` FOREIGN KEY (`country_id`) REFERENCES `country_copy` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8
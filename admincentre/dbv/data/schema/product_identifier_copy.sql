CREATE TABLE `product_identifier_copy` (
  `prod_grp_cd` int(5) NOT NULL,
  `colour_id` varchar(2) NOT NULL,
  `country_id` char(2) NOT NULL,
  `ean` varchar(100) DEFAULT NULL,
  `mpn` varchar(20) DEFAULT NULL,
  `upc` varchar(20) DEFAULT NULL,
  `status` tinyint(2) unsigned NOT NULL DEFAULT '0' COMMENT '0 = Inactive / 1 = Active',
  `create_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `create_at` int(10) unsigned NOT NULL DEFAULT '2130706433' COMMENT 'IP address, default 127.0.0.1',
  `create_by` varchar(32) NOT NULL DEFAULT 'system',
  `modify_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `modify_at` int(10) unsigned NOT NULL DEFAULT '2130706433' COMMENT 'IP address',
  `modify_by` varchar(32) NOT NULL DEFAULT 'system',
  PRIMARY KEY (`prod_grp_cd`,`colour_id`,`country_id`),
  KEY `fk_pi_colour_id` (`colour_id`),
  KEY `fk_pi_country_id` (`country_id`),
  CONSTRAINT `fk_pi_colour_id` FOREIGN KEY (`colour_id`) REFERENCES `colour_copy` (`id`),
  CONSTRAINT `fk_pi_country_id` FOREIGN KEY (`country_id`) REFERENCES `country_copy` (`id`),
  CONSTRAINT `fk_pi_prod_grp_cd` FOREIGN KEY (`prod_grp_cd`) REFERENCES `product_copy` (`prod_grp_cd`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8
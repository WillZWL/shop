CREATE TABLE `product_identifier` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `prod_grp_cd` int(5) NOT NULL,
  `colour_id` varchar(2) NOT NULL,
  `country_id` char(2) NOT NULL,
  `ean` varchar(100) NOT NULL DEFAULT '',
  `mpn` varchar(20) NOT NULL DEFAULT '',
  `upc` varchar(20) NOT NULL DEFAULT '',
  `status` tinyint(2) unsigned NOT NULL DEFAULT '0' COMMENT '0 = Inactive / 1 = Active',
  `create_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `create_at` int(10) unsigned NOT NULL DEFAULT '2130706433' COMMENT 'IP address, default 127.0.0.1',
  `create_by` varchar(32) NOT NULL DEFAULT 'system',
  `modify_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `modify_at` int(10) unsigned NOT NULL DEFAULT '2130706433' COMMENT 'IP address',
  `modify_by` varchar(32) NOT NULL DEFAULT 'system',
  PRIMARY KEY (`id`),
  KEY `idx_grp_colour_country` (`prod_grp_cd`,`colour_id`,`country_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8
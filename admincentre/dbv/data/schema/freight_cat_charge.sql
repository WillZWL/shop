CREATE TABLE `freight_cat_charge` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `fcat_id` bigint(20) unsigned NOT NULL,
  `origin_country` char(2) NOT NULL COMMENT 'International country code (2 characters)',
  `dest_country` char(2) NOT NULL COMMENT 'International country code (2 characters)',
  `currency_id` char(3) NOT NULL DEFAULT 'HKD',
  `amount` double(15,2) unsigned NOT NULL,
  `create_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `create_at` int(10) unsigned NOT NULL DEFAULT '2130706433' COMMENT 'IP address, default 127.0.0.1',
  `create_by` varchar(32) NOT NULL DEFAULT 'system',
  `modify_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `modify_at` int(10) unsigned NOT NULL DEFAULT '2130706433' COMMENT 'IP address',
  `modify_by` varchar(32) NOT NULL DEFAULT 'system',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8
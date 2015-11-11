CREATE TABLE `delivery` (
  `id` int not null auto_increment,
  `delivery_type_id` varchar(16) NOT NULL DEFAULT '',
  `country_id` char(2) NOT NULL COMMENT 'International country code (2 characters)',
  `min_day` tinyint(3) unsigned DEFAULT '0',
  `max_day` tinyint(3) unsigned DEFAULT '0',
  `status` tinyint(2) unsigned NOT NULL DEFAULT '1' COMMENT '0 = Inactive / 1 = Active',
  `create_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `create_at` int(10) unsigned NOT NULL DEFAULT '2130706433' COMMENT 'IP address, default 127.0.0.1',
  `create_by` varchar(32) NOT NULL DEFAULT 'system',
  `modify_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `modify_at` int(10) unsigned NOT NULL DEFAULT '2130706433' COMMENT 'IP address',
  `modify_by` varchar(32) NOT NULL DEFAULT 'system',
  PRIMARY KEY (`id`),
  KEY `idx_country_id` (`country_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
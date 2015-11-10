CREATE TABLE `unit` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `unit_id` varchar(5) NOT NULL,
  `unit_type_id` varchar(16) NOT NULL COMMENT 'e.g. length, weight, time',
  `unit_name` varchar(25) NOT NULL,
  `func_unit_name` varchar(255) DEFAULT NULL,
  `standardize_value` double(11,4) unsigned NOT NULL DEFAULT '1.0000',
  `description` varchar(255) NOT NULL,
  `status` tinyint(2) unsigned NOT NULL DEFAULT '1' COMMENT '0 = Inactive / 1 = Active',
  `create_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `create_at` int(10) unsigned NOT NULL DEFAULT '2130706433' COMMENT 'IP address, default 127.0.0.1',
  `create_by` varchar(32) NOT NULL DEFAULT 'system',
  `modify_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `modify_at` int(10) unsigned NOT NULL DEFAULT '2130706433' COMMENT 'IP address',
  `modify_by` varchar(32) NOT NULL DEFAULT 'system',
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_unit_id` (`unit_id`)
  ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
CREATE TABLE `display_qty_factor_copy` (
  `cat_id` bigint(20) unsigned NOT NULL,
  `class_id` bigint(20) unsigned NOT NULL,
  `factor` double(3,2) unsigned NOT NULL DEFAULT '1.00',
  `create_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `create_at` int(10) unsigned NOT NULL DEFAULT '2130706433' COMMENT 'IP address, default 127.0.0.1',
  `create_by` varchar(32) NOT NULL DEFAULT 'system',
  `modify_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `modify_at` int(10) unsigned NOT NULL DEFAULT '2130706433' COMMENT 'IP address',
  `modify_by` varchar(32) NOT NULL DEFAULT 'system',
  PRIMARY KEY (`cat_id`,`class_id`),
  KEY `fk_dqf_class_id` (`class_id`),
  CONSTRAINT `fk_dqf_class_id` FOREIGN KEY (`class_id`) REFERENCES `display_qty_class` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8
CREATE TABLE `delivery_option` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `lang_id` varchar(16) NOT NULL,
  `courier_id` varchar(20) DEFAULT NULL,
  `display_name` varchar(255) NOT NULL,
  `create_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `create_at` int(10) unsigned NOT NULL DEFAULT '2130706433' COMMENT 'IP address, default 127.0.0.1',
  `create_by` varchar(32) NOT NULL DEFAULT 'system',
  `modify_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `modify_at` int(10) unsigned NOT NULL DEFAULT '2130706433' COMMENT 'IP address',
  `modify_by` varchar(32) NOT NULL DEFAULT 'system',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_do_lang_courier_id` (`lang_id`,`courier_id`),
  KEY `fk_do_courier_id` (`courier_id`),
  CONSTRAINT `fk_do_courier_id` FOREIGN KEY (`courier_id`) REFERENCES `courier_copy` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_do_lang_id` FOREIGN KEY (`lang_id`) REFERENCES `language_copy` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT
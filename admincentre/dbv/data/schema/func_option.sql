CREATE TABLE `func_option` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `func_id` varchar(255) NOT NULL,
  `lang_id` varchar(16) NOT NULL DEFAULT 'en',
  `text` varchar(255) NOT NULL,
  `value` varchar(255) DEFAULT NULL,
  `priority` tinyint(3) unsigned DEFAULT '0',
  `status` tinyint(2) unsigned NOT NULL DEFAULT '1' COMMENT '0 = Inactive / 1 = Active',
  `create_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `create_at` int(10) unsigned NOT NULL DEFAULT '2130706433' COMMENT 'IP address, default 127.0.0.1',
  `create_by` varchar(32) NOT NULL DEFAULT 'system',
  `modify_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `modify_at` int(10) unsigned NOT NULL DEFAULT '2130706433' COMMENT 'IP address',
  `modify_by` varchar(32) NOT NULL DEFAULT 'system',
  PRIMARY KEY (`id`),
  UNIQUE KEY `fk_fo_func_lang_text` (`func_id`,`lang_id`,`text`),
  KEY `fk_fo_lang_id` (`lang_id`),
  CONSTRAINT `fk_fo_lang_id` FOREIGN KEY (`lang_id`) REFERENCES `language_copy` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT
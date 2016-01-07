CREATE TABLE `display_info` (
  `display_id` bigint(20) unsigned NOT NULL,
  `lang_id` varchar(16) NOT NULL,
  `page_title` varchar(250) DEFAULT NULL COMMENT 'Displays on top of the browser title',
  `meta_title` varchar(250) DEFAULT NULL,
  `meta_description` text,
  `meta_keyword` text,
  `create_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `create_at` int(10) unsigned NOT NULL DEFAULT '2130706433' COMMENT 'IP address, default 127.0.0.1',
  `create_by` varchar(32) NOT NULL DEFAULT 'system',
  `modify_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `modify_at` int(10) unsigned NOT NULL DEFAULT '2130706433' COMMENT 'IP address',
  `modify_by` varchar(32) NOT NULL DEFAULT 'system',
  PRIMARY KEY (`display_id`,`lang_id`),
  KEY `fk_disp_info_lang_id` (`lang_id`),
  CONSTRAINT `fk_disp_info_display_id` FOREIGN KEY (`display_id`) REFERENCES `display` (`id`),
  CONSTRAINT `fk_disp_info_lang_id` FOREIGN KEY (`lang_id`) REFERENCES `language_copy` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='Website display information which is language specific only'
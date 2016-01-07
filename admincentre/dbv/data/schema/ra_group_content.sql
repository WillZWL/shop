CREATE TABLE `ra_group_content` (
  `group_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `group_display_name` varchar(100) NOT NULL,
  `lang_id` varchar(16) NOT NULL,
  `create_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `create_at` varchar(16) NOT NULL,
  `create_by` varchar(32) NOT NULL,
  `modify_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `modify_at` varchar(16) NOT NULL,
  `modify_by` varchar(32) NOT NULL,
  PRIMARY KEY (`group_id`,`lang_id`),
  KEY `fk_rc_lang_id` (`lang_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT
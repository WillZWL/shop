CREATE TABLE `subject_domain_detail_label` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `subject` varchar(50) NOT NULL,
  `subkey` varchar(50) DEFAULT NULL,
  `lang_id` varchar(16) NOT NULL,
  `value` varchar(200) DEFAULT NULL,
  `create_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `create_at` int(10) unsigned NOT NULL DEFAULT '2130706433' COMMENT 'IP address, default 127.0.0.1',
  `create_by` varchar(32) NOT NULL DEFAULT 'system',
  `modify_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `modify_at` int(10) unsigned NOT NULL DEFAULT '2130706433' COMMENT 'IP address',
  `modify_by` varchar(32) NOT NULL DEFAULT 'system',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_subject_sub_key_lang_id` (`subject`,`subkey`,`lang_id`),
  KEY `fk_sddl_lang_id` (`lang_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8
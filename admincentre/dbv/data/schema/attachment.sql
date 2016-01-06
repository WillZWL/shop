CREATE TABLE `attachment` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `tpl_id` varchar(32) NOT NULL,
  `lang_id` varchar(16) NOT NULL DEFAULT 'en',
  `name` varchar(32) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `att_file` varchar(255) DEFAULT NULL,
  `create_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `create_at` int(10) unsigned NOT NULL DEFAULT '2130706433' COMMENT 'IP address, default 127.0.0.1',
  `create_by` varchar(32) NOT NULL DEFAULT 'system',
  `modify_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `modify_at` int(10) unsigned NOT NULL DEFAULT '2130706433' COMMENT 'IP address',
  `modify_by` varchar(32) NOT NULL DEFAULT 'system',
  PRIMARY KEY (`id`),
  KEY `fk_attachment_tpl_id` (`tpl_id`) USING BTREE,
  KEY `fk_a_lang_id` (`lang_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT
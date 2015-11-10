CREATE TABLE `template` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `template_id` varchar(32) NOT NULL,
  `lang_id` varchar(16) NOT NULL DEFAULT 'en',
  `name` varchar(32) NOT NULL DEFAULT '',
  `description` varchar(255) NOT NULL DEFAULT '',
  `status` int(11) NOT NULL DEFAULT '1' COMMENT '0 = inactive, 1 = active',
  `tpl_file` varchar(50) NOT NULL,
  `tpl_alt_file` varchar(50) NOT NULL,
  `subject` varchar(100) NOT NULL DEFAULT '',
  `message_html` text NOT NULL DEFAULT '',
  `message_alt` text NOT NULL DEFAULT '',
  `create_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `create_at` int(10) unsigned NOT NULL DEFAULT '2130706433' COMMENT 'IP address, default 127.0.0.1',
  `create_by` varchar(32) NOT NULL DEFAULT 'system',
  `modify_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `modify_at` int(10) unsigned NOT NULL DEFAULT '2130706433' COMMENT 'IP address',
  `modify_by` varchar(32) NOT NULL DEFAULT 'system',
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_template_lang` (`template_id`,`lang_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
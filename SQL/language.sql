CREATE TABLE `language` (
  `id` int not null auto_increment,
  `lang_id` varchar(6) NOT NULL COMMENT 'e.g. zh-cn, zh-tw, en and etc.',
  `lang_name` varchar(32) NOT NULL,
  `description` varchar(255) NOT NULL DEFAULT '',
  `status` tinyint NOT NULL DEFAULT '1' COMMENT '0 = Inactive / 1 = Active',
  `char_set` varchar(16) NOT NULL DEFAULT "UTF8" COMMENT 'Character set of the language',
  `create_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `create_at` int(10) unsigned NOT NULL DEFAULT '2130706433' COMMENT 'IP address, default 127.0.0.1',
  `create_by` varchar(32) NOT NULL DEFAULT 'system',
  `modify_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `modify_at` int(10) unsigned NOT NULL DEFAULT '2130706433' COMMENT 'IP address',
  `modify_by` varchar(32) NOT NULL DEFAULT 'system',
  PRIMARY KEY (`id`),
  unique index (lang_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `faqadmin` (
  `id` int not null auto_increment,
  `lang_id` varchar(16) NOT NULL COMMENT 'e.g. zh-cn, zh-tw, en and etc.',
  `faq_ver` varchar(16) NOT NULL COMMENT 'e.g. cveng, cv-fr, cv-de, etc.',
  `create_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `create_at` int(10) unsigned NOT NULL DEFAULT '2130706433' COMMENT 'IP address, default 127.0.0.1',
  `create_by` varchar(32) NOT NULL DEFAULT 'system',
  `modify_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `modify_at` int(10) unsigned NOT NULL DEFAULT '2130706433' COMMENT 'IP address',
  `modify_by` varchar(32) NOT NULL DEFAULT 'system',
  PRIMARY KEY (`id`),
  unique index idx_lang_id (lang_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
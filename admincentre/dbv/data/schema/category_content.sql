CREATE TABLE `category_content` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cat_id` bigint(20) unsigned NOT NULL,
  `lang_id` varchar(16) NOT NULL,
  `image` varchar(50) DEFAULT NULL COMMENT 'image file extension',
  `flash` varchar(50) DEFAULT NULL,
  `text` text COMMENT 'alternative text when image unavailable',
  `latest_news` text COMMENT 'category latest news',
  `status` tinyint(2) unsigned NOT NULL DEFAULT '1' COMMENT '0 = Inactive / 1 = Active',
  `create_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `create_at` int(10) unsigned NOT NULL DEFAULT '2130706433' COMMENT 'IP address, default 127.0.0.1',
  `create_by` varchar(32) NOT NULL DEFAULT 'system',
  `modify_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `modify_at` int(10) unsigned NOT NULL DEFAULT '2130706433' COMMENT 'IP address',
  `modify_by` varchar(32) NOT NULL DEFAULT 'system',
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_cat_lang` (`cat_id`,`lang_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8
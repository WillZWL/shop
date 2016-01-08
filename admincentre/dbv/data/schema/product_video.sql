CREATE TABLE `product_video` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `sku` varchar(15) NOT NULL,
  `country_id` varchar(6) DEFAULT NULL COMMENT 'International country code (2 characters)',
  `lang_id` varchar(16) NOT NULL COMMENT 'e.g. zh-cn, zh-tw, en and etc.',
  `type` varchar(2) NOT NULL COMMENT 'G = Guide / R = Review',
  `src` varchar(2) NOT NULL COMMENT 'Video Source - Y = Youtube / V = Vzaar',
  `ref_id` varchar(50) NOT NULL COMMENT 'video reference id',
  `description` text,
  `view_count` bigint(20) unsigned DEFAULT '0' COMMENT 'number of views of the video',
  `status` tinyint(2) unsigned NOT NULL DEFAULT '1' COMMENT '0 = Inactive / 1 = Active',
  `create_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `create_at` int(10) unsigned NOT NULL DEFAULT '2130706433' COMMENT 'IP address, default 127.0.0.1',
  `create_by` varchar(32) NOT NULL DEFAULT 'system',
  `modify_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `modify_at` int(10) unsigned NOT NULL DEFAULT '2130706433' COMMENT 'IP address',
  `modify_by` varchar(32) NOT NULL DEFAULT 'system',
  PRIMARY KEY (`id`),
  KEY `fk_pv_sku` (`sku`),
  KEY `fk_pv_country_id` (`country_id`),
  KEY `fk_pv_lang_id` (`lang_id`),
  CONSTRAINT `fk_pv_country_id` FOREIGN KEY (`country_id`) REFERENCES `country_copy` (`id`),
  CONSTRAINT `fk_pv_lang_id` FOREIGN KEY (`lang_id`) REFERENCES `language_copy` (`id`),
  CONSTRAINT `fk_pv_sku` FOREIGN KEY (`sku`) REFERENCES `product_copy` (`sku`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8
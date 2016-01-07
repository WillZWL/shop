CREATE TABLE `external_category` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `ext_party` varchar(20) NOT NULL,
  `level` int(3) DEFAULT NULL,
  `ext_id` varchar(64) DEFAULT NULL,
  `ext_name` varchar(256) DEFAULT NULL,
  `lang_id` varchar(16) DEFAULT NULL,
  `country_id` char(2) DEFAULT NULL COMMENT 'International country code (2 characters)',
  `status` tinyint(2) unsigned NOT NULL DEFAULT '1' COMMENT '0 = Inactive / 1 = Active',
  `create_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `create_at` int(10) unsigned NOT NULL DEFAULT '2130706433' COMMENT 'IP address, default 127.0.0.1',
  `create_by` varchar(32) NOT NULL DEFAULT 'system',
  `modify_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `modify_at` int(10) unsigned NOT NULL DEFAULT '2130706433' COMMENT 'IP address',
  `modify_by` varchar(32) NOT NULL DEFAULT 'system',
  PRIMARY KEY (`id`),
  KEY `idx_ext_id` (`ext_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8
CREATE TABLE `affiliate` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `affiliate_id` varchar(16) NOT NULL,
  `platform_id` varchar(7) NOT NULL,
  `affiliate_description` varchar(255) NOT NULL,
  `ext_party` varchar(20) NOT NULL DEFAULT '' COMMENT 'used by category_mapping',
  `create_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `create_at` int(10) unsigned NOT NULL DEFAULT '2130706433' COMMENT 'IP address, default 127.0.0.1',
  `create_by` varchar(32) NOT NULL DEFAULT 'system',
  `modify_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `modify_at` int(10) unsigned NOT NULL DEFAULT '2130706433' COMMENT 'IP address',
  `modify_by` varchar(32) NOT NULL DEFAULT 'system',
  PRIMARY KEY (`id`),
  KEY `idx_affiliate` (`affiliate_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8
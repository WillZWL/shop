CREATE TABLE `product_condition` (
  `id` bigint(20) unsigned NOT NULL COMMENT 'AMAZON: 0 - New / 1 - Used, Like New / 2 - Used, Very Good / 3 - Used, Good / 4 - Used Acceptable / 5 - Collectible, Like New / 6 - Collectible, Very Good / 7 - Collectible, Good / 8 - Collectible, Acceptable / 9 - Not used / 10 - Refurbished (for compute',
  `type` varchar(32) NOT NULL COMMENT 'WEBSITE, EBAY, AMAZON, etc.',
  `condition` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `create_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `create_at` int(10) unsigned NOT NULL DEFAULT '2130706433' COMMENT 'IP address, default 127.0.0.1',
  `create_by` varchar(32) NOT NULL DEFAULT 'system',
  `modify_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `modify_at` int(10) unsigned NOT NULL DEFAULT '2130706433' COMMENT 'IP address',
  `modify_by` varchar(32) NOT NULL DEFAULT 'system',
  PRIMARY KEY (`id`,`type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8
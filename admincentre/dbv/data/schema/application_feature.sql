CREATE TABLE `application_feature` (
  `app_feature_id` int(11) NOT NULL AUTO_INCREMENT,
  `feature_name` varchar(128) NOT NULL,
  `status` tinyint(2) unsigned NOT NULL DEFAULT '0',
  `create_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `create_at` int(10) unsigned NOT NULL DEFAULT '2130706433' COMMENT 'IP address, default 127.0.0.1',
  `create_by` varchar(32) NOT NULL DEFAULT 'system',
  `modify_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `modify_at` int(10) unsigned NOT NULL DEFAULT '2130706433' COMMENT 'IP address',
  `modify_by` varchar(32) NOT NULL DEFAULT 'system',
  PRIMARY KEY (`feature_name`),
  UNIQUE KEY `index_feature_name` (`feature_name`),
  UNIQUE KEY `index_feature_id` (`app_feature_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8
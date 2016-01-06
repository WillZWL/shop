CREATE TABLE `application_feature_right` (
  `app_id` varchar(20) NOT NULL,
  `app_feature_id` int(11) NOT NULL,
  `role_id` varchar(20) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `create_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `create_at` int(10) unsigned NOT NULL DEFAULT '2130706433' COMMENT 'IP address, default 127.0.0.1',
  `create_by` varchar(32) NOT NULL DEFAULT 'system',
  `modify_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `modify_at` int(10) unsigned NOT NULL DEFAULT '2130706433' COMMENT 'IP address',
  `modify_by` varchar(32) NOT NULL DEFAULT 'system',
  PRIMARY KEY (`app_id`,`app_feature_id`,`role_id`),
  KEY `fk_app_feature_id` (`app_feature_id`),
  KEY `fk_role_id` (`role_id`),
  CONSTRAINT `fk_app_feature_id` FOREIGN KEY (`app_feature_id`) REFERENCES `application_feature` (`app_feature_id`) ON UPDATE CASCADE,
  CONSTRAINT `fk_app_id` FOREIGN KEY (`app_id`) REFERENCES `application` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `fk_role_id` FOREIGN KEY (`role_id`) REFERENCES `role` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8
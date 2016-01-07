CREATE TABLE `http_info` (
  `name` varchar(32) NOT NULL,
  `type` varchar(2) NOT NULL DEFAULT 'P' COMMENT 'D=>Development, P=>Production',
  `server` varchar(255) DEFAULT NULL,
  `username` text,
  `password` text COMMENT 'Encrypted',
  `application_id` text,
  `signature` text COMMENT 'Encrypted',
  `token` text,
  `remark` text,
  `status` tinyint(2) NOT NULL DEFAULT '1',
  `create_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `create_at` int(10) unsigned NOT NULL DEFAULT '2130706433' COMMENT 'IP address, default 127.0.0.1',
  `create_by` varchar(32) NOT NULL DEFAULT 'system',
  `modify_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `modify_at` int(10) unsigned NOT NULL DEFAULT '2130706433' COMMENT 'IP address',
  `modify_by` varchar(32) NOT NULL DEFAULT 'system',
  PRIMARY KEY (`name`,`type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8
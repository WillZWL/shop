CREATE TABLE `user` (
  `id` varchar(20) NOT NULL,
  `username` varchar(32) NOT NULL,
  `password` char(32) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `status` tinyint(2) unsigned NOT NULL DEFAULT '0',
  `failed_attempt` tinyint(3) unsigned DEFAULT '0',
  `create_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `create_at` varchar(16) NOT NULL,
  `create_by` varchar(32) NOT NULL,
  `modify_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `modify_at` varchar(16) NOT NULL,
  `modify_by` varchar(32) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT
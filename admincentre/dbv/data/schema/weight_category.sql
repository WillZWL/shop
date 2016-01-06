CREATE TABLE `weight_category` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `weight` double(8,2) unsigned NOT NULL,
  `create_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `create_at` varchar(16) NOT NULL DEFAULT '127.0.0.1' COMMENT 'IP address',
  `create_by` varchar(32) NOT NULL,
  `modify_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `modify_at` varchar(16) NOT NULL DEFAULT '127.0.0.1' COMMENT 'IP address',
  `modify_by` varchar(32) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT
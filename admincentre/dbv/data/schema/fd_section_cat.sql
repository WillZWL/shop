CREATE TABLE `fd_section_cat` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `fds_id` bigint(20) unsigned NOT NULL,
  `banner` varchar(50) DEFAULT NULL,
  `image` varchar(50) DEFAULT NULL,
  `display_order` tinyint(3) unsigned NOT NULL DEFAULT '1',
  `create_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `create_at` int(10) unsigned NOT NULL DEFAULT '2130706433' COMMENT 'IP address, default 127.0.0.1',
  `create_by` varchar(32) NOT NULL DEFAULT 'system',
  `modify_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `modify_at` int(10) unsigned NOT NULL DEFAULT '2130706433' COMMENT 'IP address',
  `modify_by` varchar(32) NOT NULL DEFAULT 'system',
  PRIMARY KEY (`id`),
  KEY `fk_fds_fds_id` (`fds_id`) USING BTREE,
  CONSTRAINT `fk_fds_fds_id` FOREIGN KEY (`fds_id`) REFERENCES `fd_section` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT
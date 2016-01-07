CREATE TABLE `fd_section` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `fd_id` bigint(20) unsigned NOT NULL,
  `fd_image` varchar(50) DEFAULT NULL,
  `display_order` tinyint(3) unsigned NOT NULL DEFAULT '1',
  `create_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `create_at` int(10) unsigned NOT NULL DEFAULT '2130706433' COMMENT 'IP address, default 127.0.0.1',
  `create_by` varchar(32) NOT NULL DEFAULT 'system',
  `modify_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `modify_at` int(10) unsigned NOT NULL DEFAULT '2130706433' COMMENT 'IP address',
  `modify_by` varchar(32) NOT NULL DEFAULT 'system',
  PRIMARY KEY (`id`),
  KEY `fk_fd_fd_id` (`fd_id`) USING BTREE,
  CONSTRAINT `fk_fd_fd_id` FOREIGN KEY (`fd_id`) REFERENCES `festive_deal` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT
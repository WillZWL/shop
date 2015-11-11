CREATE TABLE `ftp_info` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(20) NOT NULL,
  `server` varchar(64) NOT NULL,
  `username` varchar(32) NOT NULL,
  `password` text NOT NULL,
  `port` smallint(5) NOT NULL,
  `pasv` tinyint(2) NOT NULL COMMENT '0 = Disable / 1 = Enable',
  `create_on` TIMESTAMP NOT NULL DEFAULT '0000-00-00 00:00:00',
  `create_at` INT(10) UNSIGNED NOT NULL DEFAULT '2130706433' COMMENT 'IP address, default 127.0.0.1',
  `create_by` VARCHAR(32) NOT NULL DEFAULT 'system',
  `modify_on` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `modify_at` INT(10) UNSIGNED NOT NULL DEFAULT '2130706433' COMMENT 'IP address',
  `modify_by` VARCHAR(32) NOT NULL DEFAULT 'system',
  PRIMARY KEY (`id`),
  unique key idx_name (name)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
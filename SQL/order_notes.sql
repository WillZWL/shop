CREATE TABLE `order_notes` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `so_no` char(8) NOT NULL,
  `type` varchar(2) NOT NULL DEFAULT 'O',
  `note` varchar(255) NOT NULL DEFAULT '',
  `create_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `create_at` int(10) unsigned NOT NULL DEFAULT '2130706433' COMMENT 'IP address, default 127.0.0.1',
  `create_by` varchar(32) NOT NULL DEFAULT 'system',
  `modify_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `modify_at` int(10) unsigned NOT NULL DEFAULT '2130706433' COMMENT 'IP address',
  `modify_by` varchar(32) NOT NULL DEFAULT 'system',
  PRIMARY key (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
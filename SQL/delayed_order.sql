CREATE TABLE `delayed_order` (
  `id` int not null auto_increment,
  `so_no` char(8) NOT NULL,
  `status` tinyint(2) unsigned NOT NULL DEFAULT '0' COMMENT '0 = No email yet / 1 = Minor delayed email already sent / 2 = Major delayed email already sent / 3 = minor delayed dispatch sent/ 4 = major delayed dispatch sent',
  `create_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `create_at` int(10) unsigned NOT NULL DEFAULT '2130706433' COMMENT 'IP address, default 127.0.0.1',
  `create_by` varchar(32) NOT NULL DEFAULT 'system',
  `modify_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `modify_at` int(10) unsigned NOT NULL DEFAULT '2130706433' COMMENT 'IP address',
  `modify_by` varchar(32) NOT NULL DEFAULT 'system',
  PRIMARY KEY (`id`),
  KEY `idx_so_no` (`so_no`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;
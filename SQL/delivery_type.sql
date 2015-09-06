CREATE TABLE `delivery_type` (
  `id` int not null auto_increment,
  `delivery_type_id` varchar(16)  NOT NULL DEFAULT '',
  `name` varchar(64)  NOT NULL DEFAULT '',
  `platform_type` varchar(20)  NOT NULL DEFAULT '' COMMENT 'selling_platform_type',
  `create_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `create_at` int(10) unsigned NOT NULL DEFAULT '2130706433' COMMENT 'IP address, default 127.0.0.1',
  `create_by` varchar(32) NOT NULL DEFAULT 'system',
  `modify_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `modify_at` int(10) unsigned NOT NULL DEFAULT '2130706433' COMMENT 'IP address',
  `modify_by` varchar(32) NOT NULL DEFAULT 'system',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
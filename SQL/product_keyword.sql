CREATE TABLE `product_keyword` (
  `id` int not null auto_increment,
  `sku` bigint unsigned NOT NULL,
  `lang_id` varchar(5) NOT NULL COMMENT 'e.g. zh-cn, zh-tw, en and etc.',
  `keyword` varchar(200) NOT NULL,
  `type` tinyint(2) not null DEFAULT '1' COMMENT '1 = Default',
  `create_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `create_at` int(10) unsigned NOT NULL DEFAULT '2130706433' COMMENT 'IP address, default 127.0.0.1',
  `create_by` varchar(32) NOT NULL DEFAULT 'system',
  `modify_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `modify_at` int(10) unsigned NOT NULL DEFAULT '2130706433' COMMENT 'IP address',
  `modify_by` varchar(32) NOT NULL DEFAULT 'system',
  PRIMARY key (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

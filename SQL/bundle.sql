CREATE TABLE `bundle` (
  `id` int not null auto_increment,
  `prod_sku`  bigint(20) unsigned NOT NULL,
  `component_sku` varchar(15) NOT NULL,
  `component_order` tinyint(2) unsigned NOT NULL DEFAULT '0' COMMENT '0 = MainProduct / 1,2,3... = Other Components',
  `create_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `create_at` int(10) unsigned NOT NULL DEFAULT '2130706433' COMMENT 'IP address, default 127.0.0.1',
  `create_by` varchar(32) NOT NULL DEFAULT 'system',
  `modify_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `modify_at` int(10) unsigned NOT NULL DEFAULT '2130706433' COMMENT 'IP address',
  `modify_by` varchar(32) NOT NULL DEFAULT 'system',
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_sku_component_order` (`prod_sku`,`component_sku`,`component_order`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
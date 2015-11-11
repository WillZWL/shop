CREATE TABLE `inventory` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `warehouse_id` varchar(20) NOT NULL DEFAULT '',
  `prod_sku` varchar(15) NOT NULL DEFAULT '',
  `inventory` int(10) NOT NULL DEFAULT '0',
  `git` int(10) NOT NULL DEFAULT '0',
  `surplus_qty` int(11) NOT NULL DEFAULT '0',
  `create_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `create_at` int(10) unsigned NOT NULL DEFAULT '2130706433' COMMENT 'IP address, default 127.0.0.1',
  `create_by` varchar(32) NOT NULL DEFAULT 'system',
  `modify_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `modify_at` int(10) unsigned NOT NULL DEFAULT '2130706433' COMMENT 'IP address',
  `modify_by` varchar(32) NOT NULL DEFAULT 'system',
  PRIMARY KEY (`id`),
  KEY `idx_warehouse_id` (`warehouse_id`) USING BTREE,
  KEY `idx_prod_sku` (`prod_sku`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
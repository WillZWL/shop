CREATE TABLE `wms_inventory` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `warehouse_id` varchar(20) NOT NULL,
  `master_sku` varchar(15) NOT NULL,
  `inventory` int(10) NOT NULL,
  `git` int(10) NOT NULL,
  `create_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `create_at` int(10) unsigned NOT NULL DEFAULT '2130706433' COMMENT 'IP address, default 127.0.0.1',
  `create_by` varchar(32) NOT NULL DEFAULT 'system',
  `modify_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `modify_at` int(10) unsigned NOT NULL DEFAULT '2130706433' COMMENT 'IP address',
  `modify_by` varchar(32) NOT NULL DEFAULT 'system',
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_warehouse_sku` (`warehouse_id`,`master_sku`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8
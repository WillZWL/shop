CREATE TABLE `cps_sourcing_list` (
  `list_date` date NOT NULL,
  `item_sku` varchar(15) NOT NULL,
  `order_info` text NOT NULL,
  `required_info` text NOT NULL,
  `required_qty` smallint(5) unsigned NOT NULL,
  `avg_cost` double(15,2) DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT '0',
  `create_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `create_at` int(10) unsigned NOT NULL DEFAULT '2130706433' COMMENT 'IP address, default 127.0.0.1',
  `create_by` varchar(32) NOT NULL DEFAULT 'system',
  `modify_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `modify_at` int(10) unsigned NOT NULL DEFAULT '2130706433' COMMENT 'IP address',
  `modify_by` varchar(32) NOT NULL DEFAULT 'system',
  PRIMARY KEY (`list_date`,`item_sku`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8
CREATE TABLE `sourcing_list` (
  `list_date` date NOT NULL,
  `batch_no` tinyint(3) NOT NULL DEFAULT '1',
  `item_sku` varchar(15) NOT NULL,
  `platform_qty` text NOT NULL,
  `required_qty` smallint(5) unsigned NOT NULL,
  `prioritized_qty` smallint(5) NOT NULL DEFAULT '0',
  `supplier_id` bigint(20) unsigned NOT NULL,
  `sourcing_reg_id` bigint(20) unsigned DEFAULT NULL,
  `sourced_qty` smallint(5) unsigned NOT NULL DEFAULT '0',
  `comments` varchar(255) DEFAULT NULL,
  `create_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `create_at` int(10) unsigned NOT NULL DEFAULT '2130706433' COMMENT 'IP address, default 127.0.0.1',
  `create_by` varchar(32) NOT NULL DEFAULT 'system',
  `modify_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `modify_at` int(10) unsigned NOT NULL DEFAULT '2130706433' COMMENT 'IP address',
  `modify_by` varchar(32) NOT NULL DEFAULT 'system',
  PRIMARY KEY (`list_date`,`batch_no`,`item_sku`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT
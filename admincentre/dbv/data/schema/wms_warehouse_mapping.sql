CREATE TABLE `wms_warehouse_mapping` (
  `platform_id` varchar(7) NOT NULL,
  `warehouse_id` varchar(20) NOT NULL,
  `status` tinyint(2) unsigned NOT NULL DEFAULT '1' COMMENT '0 = Inactive / 1 = Active',
  `create_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `create_at` varchar(16) NOT NULL DEFAULT '127.0.0.1' COMMENT 'IP address',
  `create_by` varchar(32) NOT NULL,
  `modify_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `modify_at` varchar(16) NOT NULL DEFAULT '127.0.0.1' COMMENT 'IP address',
  `modify_by` varchar(32) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT
CREATE TABLE `wms_warehouse` (
  `type` char(1) NOT NULL COMMENT 'R = Retailer / W = Warehouse',
  `warehouse_id` varchar(20) NOT NULL,
  `status` tinyint(2) unsigned NOT NULL DEFAULT '1' COMMENT '0 = Inactive / 1 = Active',
  `modify_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`type`,`warehouse_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8
CREATE TABLE `supplier_shipment` (
  `shipment_id` varchar(11) NOT NULL,
  `remark` varchar(255) DEFAULT NULL,
  `status` varchar(2) NOT NULL,
  `qty_received` smallint(5) unsigned DEFAULT NULL,
  `tracking_no` varchar(40) DEFAULT NULL,
  `courier` varchar(6) DEFAULT NULL,
  `reason_code` varchar(3) DEFAULT NULL,
  `create_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `create_at` int(10) unsigned NOT NULL DEFAULT '2130706433' COMMENT 'IP address, default 127.0.0.1',
  `create_by` varchar(32) NOT NULL DEFAULT 'system',
  `modify_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `modify_at` int(10) unsigned NOT NULL DEFAULT '2130706433' COMMENT 'IP address',
  `modify_by` varchar(32) NOT NULL DEFAULT 'system',
  PRIMARY KEY (`shipment_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT
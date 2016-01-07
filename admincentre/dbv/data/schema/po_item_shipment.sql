CREATE TABLE `po_item_shipment` (
  `sid` varchar(20) NOT NULL,
  `po_number` varchar(9) NOT NULL,
  `line_number` smallint(5) unsigned NOT NULL,
  `invm_trans_id` bigint(20) unsigned DEFAULT NULL,
  `qty` smallint(5) NOT NULL,
  `to_location` varchar(20) NOT NULL,
  `received_qty` smallint(5) unsigned DEFAULT '0',
  `reason_code` varchar(4) DEFAULT NULL,
  `create_at` int(10) unsigned NOT NULL DEFAULT '2130706433' COMMENT 'IP address, default 127.0.0.1',
  `create_by` varchar(32) NOT NULL DEFAULT 'system',
  `modify_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `modify_at` int(10) unsigned NOT NULL DEFAULT '2130706433' COMMENT 'IP address',
  `modify_by` varchar(32) NOT NULL DEFAULT 'system',
  `create_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`sid`,`po_number`,`line_number`),
  KEY `fk_pois_po_no_item_no` (`po_number`,`line_number`) USING BTREE,
  CONSTRAINT `fk_pois_po_no_item_no` FOREIGN KEY (`po_number`, `line_number`) REFERENCES `po_item` (`po_number`, `line_number`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT
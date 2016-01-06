CREATE TABLE `purchase_order` (
  `po_number` varchar(9) NOT NULL DEFAULT '',
  `supplier_id` bigint(20) unsigned DEFAULT NULL,
  `supplier_invoice_number` varchar(16) DEFAULT NULL,
  `delivery_mode` varchar(2) DEFAULT NULL,
  `status` varchar(2) DEFAULT NULL COMMENT 'N - New, PS - Partially Delivered, FS - Fully Dispatched, CL - Cancelled, C - Completed',
  `currency` char(3) DEFAULT NULL,
  `amount` double(9,2) unsigned NOT NULL DEFAULT '0.00',
  `eta` date DEFAULT '0000-00-00',
  `create_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `create_at` int(10) unsigned NOT NULL DEFAULT '2130706433' COMMENT 'IP address, default 127.0.0.1',
  `create_by` varchar(32) NOT NULL DEFAULT 'system',
  `modify_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `modify_at` int(10) unsigned NOT NULL DEFAULT '2130706433' COMMENT 'IP address',
  `modify_by` varchar(32) NOT NULL DEFAULT 'system',
  PRIMARY KEY (`po_number`),
  KEY `fk_po_supplier` (`supplier_id`) USING BTREE,
  CONSTRAINT `fk_po_supplier` FOREIGN KEY (`supplier_id`) REFERENCES `supplier` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT
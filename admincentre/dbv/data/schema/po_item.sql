CREATE TABLE `po_item` (
  `po_number` varchar(9) NOT NULL DEFAULT '',
  `line_number` smallint(6) unsigned NOT NULL DEFAULT '0',
  `sku` varchar(15) NOT NULL,
  `order_qty` smallint(4) unsigned NOT NULL DEFAULT '0',
  `shipped_qty` smallint(5) unsigned NOT NULL DEFAULT '0',
  `unit_price` double(8,2) unsigned NOT NULL DEFAULT '0.00',
  `status` varchar(2) NOT NULL DEFAULT 'A' COMMENT 'A - Active, D - Deleted',
  `create_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `create_at` int(10) unsigned NOT NULL DEFAULT '2130706433' COMMENT 'IP address, default 127.0.0.1',
  `create_by` varchar(32) NOT NULL DEFAULT 'system',
  `modify_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `modify_at` int(10) unsigned NOT NULL DEFAULT '2130706433' COMMENT 'IP address',
  `modify_by` varchar(32) NOT NULL DEFAULT 'system',
  PRIMARY KEY (`po_number`,`line_number`),
  KEY `fk_po_po_number` (`po_number`) USING BTREE,
  CONSTRAINT `fk_po_po_number` FOREIGN KEY (`po_number`) REFERENCES `purchase_order` (`po_number`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT
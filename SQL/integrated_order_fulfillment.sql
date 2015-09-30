CREATE TABLE `integrated_order_fulfillment` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `so_no` char(8) NOT NULL,
  `line_no` smallint(5) unsigned NOT NULL,
  `sku` varchar(15) NOT NULL DEFAULT '',
  `platform_id` varchar(7) NOT NULL DEFAULT '',
  `platform_order_id` varchar(100) NOT NULL DEFAULT '',
  `order_create_date` timestamp NULL NOT NULL DEFAULT '0000-00-00 00:00:00',
  `expect_delivery_date` date NOT NULL DEFAULT '0000-00-00 00:00:00',
  `product_name` varchar(255) NOT NULL DEFAULT '',
  `website_status` varchar(2) NOT NULL DEFAULT '',
  `delivery_name` varchar(100) NOT NULL DEFAULT '',
  `delivery_country_id` char(2) NOT NULL DEFAULT '',
  `delivery_type_id` varchar(16) NOT NULL DEFAULT '',
  `payment_gateway_id` varchar(20) NOT NULL DEFAULT '',
  `rec_courier` varchar(255) NOT NULL DEFAULT '' COMMENT 'recommended courier',
  `note` varchar(255) NOT NULL DEFAULT '',
  `amount` double(15,2) unsigned NOT NULL DEFAULT '0.00' COMMENT 'sku level, but this is the order level amount',
  `refund_status` tinyint(2) unsigned DEFAULT '0' COMMENT '0 = No / 1 = Requested / 2 = Logistic Approved / 3 = CS Approved / 4 = Refunded',
  `hold_status` tinyint(2) unsigned DEFAULT '0' COMMENT '0 = No / 1 = Requested / 2 = Manager Requested / 3 = APS need Payment order in Sales - APS area / 15 = Has Split Child',
  `qty` smallint(5) unsigned NOT NULL DEFAULT '0',
  `outstanding_qty` smallint(5) unsigned NOT NULL DEFAULT '0',
  `status` tinyint(2) unsigned DEFAULT '1' COMMENT '0 = Inactive / 1 = New / 2 = Paid / 3 = Credit Checked / 4 = Partial Allocated / 5 = Full Allocated / 6 = Shipped',
  `split_so_group` char(8) NOT NULL DEFAULT '' COMMENT 'main so_no that a childsplitorder belongs to',
  `delivery_postcode` varchar(16) NOT NULL DEFAULT '',
  `order_total_sku` smallint(2) unsigned NOT NULL DEFAULT '0',
  `create_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `create_at` int(10) unsigned NOT NULL DEFAULT '2130706433' COMMENT 'IP address, default 127.0.0.1',
  `create_by` varchar(32) NOT NULL DEFAULT 'system',
  `modify_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `modify_at` int(10) unsigned NOT NULL DEFAULT '2130706433' COMMENT 'IP address',
  `modify_by` varchar(32) NOT NULL DEFAULT 'system',
  PRIMARY KEY (`id`),
  unique key idx_so_no_line_sku (`so_no`,`line_no`,`sku`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
CREATE TABLE `so` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `so_no` char(8) NOT NULL,
  `platform_order_id` varchar(100) NOT NULL,
  `platform_id` varchar(7) NOT NULL,
  `txn_id` varchar(100) NOT NULL DEFAULT '' COMMENT 'Transaction id',
  `client_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  `biz_type` varchar(20) NOT NULL COMMENT 'ONLINE/AMAZON/WALKIN/SPECIAL/OFFLINE/B2B',
  `amount` double(15,2) unsigned NOT NULL,
  `cost` double(15,2) unsigned NOT NULL,
  `vat_percent` double(5,2) unsigned NOT NULL DEFAULT '0.00',
  `rate` double(11,6) unsigned NOT NULL DEFAULT '1.000000',
  `ref_1` double(11,6) unsigned NOT NULL DEFAULT '1.000000',
  `delivery_charge` double(8,2) unsigned NOT NULL,
  `delivery_type_id` varchar(16) NOT NULL,
  `weight` double(8,2) unsigned NOT NULL,
  `currency_id` char(3) NOT NULL,
  `lang_id` varchar(16) NOT NULL DEFAULT 'en' COMMENT 'e.g. zh-cn, zh-tw, en and etc.',
  `bill_name` varchar(101) NOT NULL DEFAULT '' COMMENT 'Default is client name',
  `bill_company` varchar(50) NOT NULL DEFAULT '',
  `bill_address` text,
  `bill_postcode` varchar(16) NOT NULL DEFAULT '',
  `bill_city` varchar(80) NOT NULL DEFAULT '',
  `bill_state` varchar(80) NOT NULL DEFAULT '',
  `bill_country_id` char(2) NOT NULL DEFAULT '',
  `delivery_name` varchar(101) NOT NULL DEFAULT '' COMMENT 'Default is client name, Amazon has a separate ship to name',
  `delivery_company` varchar(50) NOT NULL DEFAULT '',
  `delivery_address` text,
  `delivery_postcode` varchar(16) NOT NULL DEFAULT '',
  `delivery_city` varchar(80) NOT NULL DEFAULT '',
  `delivery_state` varchar(80) NOT NULL DEFAULT '',
  `delivery_country_id` char(2) NOT NULL DEFAULT '',
  `parent_so_no` char(8) NOT NULL DEFAULT '',
  `status` tinyint(2) unsigned NOT NULL DEFAULT '1' COMMENT '0 = Inactive / 1 = New / 2 = Paid / 3 = Fulfilment AKA Credit Checked / 4 = Partial Allocated / 5 = Full Allocated / 6 = Shipped',
  `refund_status` tinyint(2) unsigned NOT NULL DEFAULT '0' COMMENT '0 = No / 1 = Requested / 2 = Logistic Approved / 3 = CS Approved / 4 = Refunded',
  `hold_status` tinyint(2) unsigned NOT NULL DEFAULT '0' COMMENT '0 = No / 1 = Requested / 2 = Manager Requested / 3 = APS need Payment order in Sales - APS area / 10 = Permanent Hold / 15 = Has Split Child',
  `hold_reason` varchar(255) NOT NULL DEFAULT '',
  `refund_reason` varchar(255) NOT NULL DEFAULT '',
  `order_note` varchar(255) NOT NULL DEFAULT '',
  `promotion_code` varchar(20) NOT NULL DEFAULT '' COMMENT 'System Actual Promotion Code',
  `client_promotion_code` varchar(255) NOT NULL DEFAULT '' COMMENT 'Promotion code which inputed by client',
  `expect_delivery_date` date NOT NULL DEFAULT '0000-00-00',
  `expect_ship_days` varchar(11) NOT NULL DEFAULT '' COMMENT 'Expected shipping time frame (days)',
  `expect_del_days` varchar(11) NOT NULL DEFAULT '' COMMENT 'Expected delivery time frame (days); should be larger than expect_ship_days',
  `order_create_date` datetime NOT NULL,
  `dispatch_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `fingerprint_id` varchar(128) NOT NULL DEFAULT '',
  `cc_reminder_schedule_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `cc_reminder_type` varchar(20) NOT NULL DEFAULT '',
  `cs_customer_query` smallint(3) DEFAULT '0' COMMENT 'Using bit operation 1 = chasing order, can have more value later',
  `split_status` int(2) DEFAULT '0' COMMENT '0 = No Split / 1 = Split Requested / 2 = Split Order',
  `split_create_on` timestamp NULL DEFAULT '0000-00-00 00:00:00',
  `split_create_by` varchar(255) NOT NULL DEFAULT '',
  `split_so_group` char(8) NOT NULL DEFAULT '' COMMENT 'main so_no that a childsplitorder belongs to',
  `create_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `create_at` int(10) unsigned NOT NULL DEFAULT '2130706433' COMMENT 'IP address, default 127.0.0.1',
  `create_by` varchar(32) NOT NULL DEFAULT 'system',
  `modify_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `modify_at` int(10) unsigned NOT NULL DEFAULT '2130706433' COMMENT 'IP address',
  `modify_by` varchar(32) NOT NULL DEFAULT 'system',
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_so` (`so_no`,`platform_id`,`currency_id`,`client_id`,`delivery_type_id`,`cc_reminder_schedule_date`,`parent_so_no`,`status`),
  KEY `idx_create` (`order_create_date`) USING BTREE,
  KEY `idx_create_on` (`create_on`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8
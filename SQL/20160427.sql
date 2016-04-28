ALTER TABLE `courier_feed` DROP COLUMN `batch_id`;

update config set value='/var/data/panther/warehouse/ams/from_warehouse/tracking_information' where variable = "tracking_info_ams_path";
update config set value='/var/data/panther/warehouse/ilg/from_warehouse/tracking_information' where variable = "tracking_info_ilg_path";
update config set value='/var/data/panther/warehouse/im/from_warehouse/tracking_information' where variable = "tracking_info_im_path";


CREATE TABLE `interface_tracking_info` (
  `trans_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `batch_id` bigint(20) unsigned NOT NULL,
  `sh_no` varchar(11) NOT NULL,
  `so_no` varchar(8) NOT NULL,
  `order_number` varchar(8) NOT NULL DEFAULT '',
  `status` varchar(15) NOT NULL DEFAULT '',
  `tracking_no` varchar(20) NOT NULL DEFAULT '',
  `ship_method` varchar(10) NOT NULL DEFAULT '',
  `courier_id` varchar(16) NOT NULL DEFAULT '',
  `dispatch_date` varchar(19) NOT NULL DEFAULT '',
  `weight` double(8,2) unsigned NOT NULL DEFAULT '0.00',
  `consignee` varchar(30) NOT NULL DEFAULT '',
  `postcode` varchar(16) NOT NULL DEFAULT '',
  `country` varchar(2) NOT NULL DEFAULT '',
  `amount` double(15,2) unsigned NOT NULL DEFAULT '0.00',
  `currency` varchar(3) NOT NULL DEFAULT '',
  `charge_out` varchar(9) NOT NULL DEFAULT '',
  `qty` smallint(5) unsigned NOT NULL DEFAULT '0',
  `sku` varchar(19) NOT NULL DEFAULT '',
  `qty_shipped` smallint(5) unsigned NOT NULL DEFAULT '0',
  `shipping_cost` double(15,2) unsigned NOT NULL DEFAULT '0.00',
  `batch_status` varchar(2) NOT NULL DEFAULT '' COMMENT 'N = New / R = Ready update to master / S = Success / F = Failed / I = Investigated',
  `failed_reason` varchar(255) NOT NULL DEFAULT '',
  `create_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `create_at` int(10) unsigned NOT NULL DEFAULT '2130706433' COMMENT 'IP address, default 127.0.0.1',
  `create_by` varchar(32) NOT NULL DEFAULT 'system',
  `modify_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `modify_at` int(10) unsigned NOT NULL DEFAULT '2130706433' COMMENT 'IP address',
  `modify_by` varchar(32) NOT NULL DEFAULT 'system',
  PRIMARY KEY (`trans_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;
CREATE TABLE `interface_so_shipment` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `batch_id` bigint(20) unsigned NOT NULL,
  `sh_no` char(11) NOT NULL,
  `courier_id` varchar(16) NOT NULL,
  `tracking_no` varchar(40) DEFAULT NULL,
  `status` tinyint(2) unsigned NOT NULL DEFAULT '0' COMMENT '1 = Picked / 2 = Shipped',
  `batch_status` varchar(2) NOT NULL DEFAULT '' COMMENT 'N = New / R = Ready update to master / S = Success / F = Failed / I = Investigated',
  `failed_reason` varchar(255) DEFAULT NULL,
  `create_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `create_at` int(10) unsigned NOT NULL DEFAULT '2130706433' COMMENT 'IP address, default 127.0.0.1',
  `create_by` varchar(32) NOT NULL DEFAULT 'system',
  `modify_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `modify_at` int(10) unsigned NOT NULL DEFAULT '2130706433' COMMENT 'IP address',
  `modify_by` varchar(32) NOT NULL DEFAULT 'system',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT
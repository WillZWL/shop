CREATE TABLE `interface_product_video` (
  `trans_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `batch_id` bigint(20) unsigned NOT NULL,
  `sku` varchar(15) NOT NULL,
  `ref_id` varchar(50) NOT NULL COMMENT 'video reference id',
  `view_count` bigint(20) unsigned DEFAULT '0' COMMENT 'number of views of the video',
  `batch_status` varchar(2) NOT NULL DEFAULT '' COMMENT 'N = New / R = Ready update to master / S = Success / F = Failed / I = Investigated',
  `failed_reason` varchar(255) DEFAULT NULL,
  `create_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `create_at` int(10) unsigned NOT NULL DEFAULT '2130706433' COMMENT 'IP address, default 127.0.0.1',
  `create_by` varchar(32) NOT NULL DEFAULT 'system',
  `modify_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `modify_at` int(10) unsigned NOT NULL DEFAULT '2130706433' COMMENT 'IP address',
  `modify_by` varchar(32) NOT NULL DEFAULT 'system',
  PRIMARY KEY (`trans_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8
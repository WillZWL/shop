CREATE TABLE `ra_group_product` (
  `ra_group_id` bigint(20) unsigned NOT NULL,
  `sku` bigint(20) unsigned NOT NULL,
  `priority` tinyint(3) DEFAULT '1',
  `build_bundle` int(2) DEFAULT NULL COMMENT 'use sku to make bundle components? 1= Use',
  `create_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `create_at` int(10) unsigned NOT NULL DEFAULT '2130706433' COMMENT 'IP address, default 127.0.0.1',
  `create_by` varchar(32) NOT NULL DEFAULT 'system',
  `modify_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `modify_at` int(10) unsigned NOT NULL DEFAULT '2130706433' COMMENT 'IP address',
  `modify_by` varchar(32) NOT NULL DEFAULT 'system',
  PRIMARY KEY (`ra_group_id`,`sku`),
  KEY `fk_rgp_ra_group_id` (`ra_group_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8
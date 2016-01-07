CREATE TABLE `so_shipment` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sh_no` char(11) NOT NULL,
  `courier_id` varchar(32) NOT NULL,
  `tracking_no` varchar(40) NOT NULL DEFAULT '',
  `courier_feed_sent` tinyint(2) NOT NULL DEFAULT '0' COMMENT '0 = Not Included in DGM EFILE / 1 = Included in DGM, Used By deutsche-post so far',
  `status` tinyint(2) unsigned NOT NULL DEFAULT '0' COMMENT '1 = Picked / 2 = Shipped',
  `create_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `create_at` int(10) unsigned NOT NULL DEFAULT '2130706433' COMMENT 'IP address, default 127.0.0.1',
  `create_by` varchar(32) NOT NULL DEFAULT 'system',
  `modify_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `modify_at` int(10) unsigned NOT NULL DEFAULT '2130706433' COMMENT 'IP address',
  `modify_by` varchar(32) NOT NULL DEFAULT 'system',
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_sh_no` (`sh_no`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8
CREATE TABLE `courier_feed` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `batch_id` int(10) NOT NULL DEFAULT '0',
  `so_no_str` text NOT NULL DEFAULT '' COMMENT 'order number separated by pile character |',
  `courier_id` varchar(40) NOT NULL DEFAULT '',
  `mawb` varchar(40) NOT NULL DEFAULT '',
  `exec` tinyint(2) NOT NULL DEFAULT '0' COMMENT '0=not yet executed, 1=executed',
  `comment` varchar(255) NOT NULL DEFAULT '',
  `create_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `create_at` int(10) unsigned NOT NULL DEFAULT '2130706433' COMMENT 'IP address, default 127.0.0.1',
  `create_by` varchar(32) NOT NULL DEFAULT 'system',
  `modify_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `modify_at` int(10) unsigned NOT NULL DEFAULT '2130706433' COMMENT 'IP address',
  `modify_by` varchar(32) NOT NULL DEFAULT 'system',
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_batch_id` (`batch_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='use for courier feed cron job';
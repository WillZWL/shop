CREATE TABLE `schedule_job` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `schedule_job_id` varchar(64) NOT NULL,
  `name` varchar(255) NOT NULL DEFAULT '',
  `last_access_time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `status` varchar(2) DEFAULT NULL COMMENT '0-inactive,1-active',
  `create_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `create_at` int(10) unsigned NOT NULL DEFAULT '2130706433' COMMENT 'IP address, default 127.0.0.1',
  `create_by` varchar(32) NOT NULL DEFAULT 'system',
  `modify_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `modify_at` int(10) unsigned NOT NULL DEFAULT '2130706433' COMMENT 'IP address',
  `modify_by` varchar(32) NOT NULL DEFAULT 'system',
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_schedule_job_id` (`schedule_job_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT
insert into schedule_job (id, name, last_access_time, status, create_on, create_at, create_by, modify_at, modify_by)
values
('WMS_GET_TRACKING_FEED', "WMS Get Tracking Feed Cron Time", now(), 1, now(), '127.0.0.1', 'brave', '127.0.0.1', 'brave');


insert into application (id, app_name, description, display_order, status, display_row, create_on, create_at, create_by, modify_at, modify_by)
values ('CRN0042', 'Cron for dispatch auto import tracking number', 'Cron for dispatch auto import tracking number', 0, 1, 1, now(), '127.0.0.1', 'brave', '127.0.0.1', 'brave');
call add_role_right('CRN0042', 'admin');

CREATE TABLE `interface_tracking` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `schedule_job_id` varchar(64) NOT NULL,
  `start_time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `end_time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `status` varchar(2) NOT NULL DEFAULT 'N' COMMENT 'N = New / NR = No Record / F = Request Fail / CE = Completed with Error / C = Completed',
  `create_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `create_at` int(10) unsigned NOT NULL DEFAULT '2130706433' COMMENT 'IP address, default 127.0.0.1',
  `create_by` varchar(32) NOT NULL DEFAULT 'system',
  `modify_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `modify_at` int(10) unsigned NOT NULL DEFAULT '2130706433' COMMENT 'IP address',
  `modify_by` varchar(32) NOT NULL DEFAULT 'system',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `interface_tracking_feed` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `tracking_id` bigint(20) NOT NULL,
  `so_no` char(8) NOT NULL,
  `retailer_name` varchar(32) DEFAULT NULL,
  `sh_no` char(11) DEFAULT NULL,
  `tracking_no` varchar(40) DEFAULT NULL,
  `history_tracking_no` varchar(40) DEFAULT NULL,
  `weight_in_kg` double(16,2) unsigned NOT NULL DEFAULT '0.00',
  `courier_name` varchar(32) DEFAULT NULL,
  `courier_id` varchar(32) DEFAULT NULL,
  `courier_id_num` int(11) NOT NULL DEFAULT '0',
  `items` text  DEFAULT NULL COMMENT 'master_sku with warehouse_id',
  `notes` varchar(50) DEFAULT NULL,
  `vb_courier_id` varchar(32) DEFAULT NULL,
  `refund_status` tinyint(2) unsigned NOT NULL DEFAULT '0',
  `hold_status` tinyint(2) unsigned NOT NULL DEFAULT '0',
  `send_email` tinyint(2) DEFAULT '0' COMMENT '0 ＝ Normal ／ 1 ＝ Wait send／ 2 ＝ Sent successfully',
  `status` varchar(1) NOT NULL DEFAULT 'N' COMMENT 'N = New / R = Ready update to master / S = Success / F = Failed / I = Investigated',
  `create_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `create_at` int(10) unsigned NOT NULL DEFAULT '2130706433' COMMENT 'IP address, default 127.0.0.1',
  `create_by` varchar(32) NOT NULL DEFAULT 'system',
  `modify_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `modify_at` int(10) unsigned NOT NULL DEFAULT '2130706433' COMMENT 'IP address',
  `modify_by` varchar(32) NOT NULL DEFAULT 'system',
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_tracking_so` (`tracking_id`,`so_no`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `landpage_listing` (
  `id` int not null auto_increment,
  `catid` int unsigned NOT NULL DEFAULT '0',
  `platform_id` char(6) NOT NULL DEFAULT '',
  `type` char(2) NOT NULL DEFAULT '',
  `mode` varchar(2) NOT NULL DEFAULT 'M',
  `rank` tinyint(2) unsigned NOT NULL DEFAULT '0',
  `selection` varchar(15) NOT NULL,
  `create_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `create_at` int(10) unsigned NOT NULL DEFAULT '2130706433' COMMENT 'IP address, default 127.0.0.1',
  `create_by` varchar(32) NOT NULL DEFAULT 'system',
  `modify_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `modify_at` int(10) unsigned NOT NULL DEFAULT '2130706433' COMMENT 'IP address',
  `modify_by` varchar(32) NOT NULL DEFAULT 'system',
  PRIMARY key (id),
  unique key idx_catid_platform_type_mode_rand (`catid`,`platform_id`,`type`,`mode`,`rank`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `colour` (
  `id` int not null auto_increment,
  `colour_id` char(2) NOT NULL,
  `colour_name` varchar(64) NOT NULL,
  `status` tinyint NOT NULL DEFAULT 1,
  `create_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `create_at` int(10) unsigned NOT NULL DEFAULT '2130706433' COMMENT 'IP address, default 127.0.0.1',
  `create_by` varchar(32) NOT NULL DEFAULT 'system',
  `modify_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `modify_at` int(10) unsigned NOT NULL DEFAULT '2130706433' COMMENT 'IP address',
  `modify_by` varchar(32) NOT NULL DEFAULT 'system',
  PRIMARY KEY (`id`),
  unique index idx_colour_id (colour_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `colour_extend` (
  `id` int not null auto_increment,
  `colour_id` char(2) NOT NULL,
  `lang_id` varchar(5) NOT NULL,
  `colour_name` varchar(64) NOT NULL DEFAULT '',
  `create_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `create_at` int(10) unsigned NOT NULL DEFAULT '2130706433' COMMENT 'IP address, default 127.0.0.1',
  `create_by` varchar(32) NOT NULL DEFAULT 'system',
  `modify_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `modify_at` int(10) unsigned NOT NULL DEFAULT '2130706433' COMMENT 'IP address',
  `modify_by` varchar(32) NOT NULL DEFAULT 'system',
  PRIMARY KEY (id),
  unique index idx_colour_id (colour_id, lang_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
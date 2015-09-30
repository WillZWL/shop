CREATE TABLE `category_product_spec` (
  `id` int not null auto_increment,
  `ps_id` varchar(255) NOT NULL DEFAULT '',
  `cat_id` bigint(20) unsigned NOT NULL,
  `unit_id` varchar(25) NOT NULL,
  `priority` tinyint(3) unsigned DEFAULT '9',
  `status` tinyint(2) unsigned NOT NULL DEFAULT '1' COMMENT '0 = Inactive / 1 = Active',
  `create_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `create_at` int(10) unsigned NOT NULL DEFAULT '2130706433' COMMENT 'IP address, default 127.0.0.1',
  `create_by` varchar(32) NOT NULL DEFAULT 'system',
  `modify_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `modify_at` int(10) unsigned NOT NULL DEFAULT '2130706433' COMMENT 'IP address',
  `modify_by` varchar(32) NOT NULL DEFAULT 'system',
  PRIMARY KEY (id),
  unique index idx_spec_cat_unit (ps_id, cat_id, unit_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
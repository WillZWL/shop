CREATE TABLE `bundle_config` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `country_id` char(2) NOT NULL,
  `discount_1_item` double(5,2) NOT NULL DEFAULT '0.00',
  `discount_2_item` double(5,2) NOT NULL DEFAULT '0.00',
  `discount_3_more_item` double(5,2) NOT NULL DEFAULT '0.00',
  `status` char(1) NOT NULL DEFAULT '1',
  `create_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `create_at` int(10) unsigned NOT NULL DEFAULT '2130706433' COMMENT 'IP address, default 127.0.0.1',
  `create_by` varchar(32) NOT NULL DEFAULT 'system',
  `modify_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `modify_at` int(10) unsigned NOT NULL DEFAULT '2130706433' COMMENT 'IP address',
  `modify_by` varchar(32) NOT NULL DEFAULT 'system',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;



insert into application (id, app_name, description,  display_order, status, display_row, create_on, create_at, create_by, modify_at, modify_by)
values ('MKT0087', 'Bundle & RA Admin', 'Bundle & RA admin', 0, 1, 1, now(), '127.0.0.1', 'cristina', '127.0.0.1', 'cristina');

update application set display_order = 136, url = 'marketing/bundleconfig', app_group_id=4 where id='MKT0087';

call add_role_right('MKT0087', 'admin');
call add_role_right('MKT0087', 'alan');
call add_role_right('MKT0087', 'mkt_lead');
call add_role_right('MKT0087', 'mkt_man');
call add_role_right('MKT0087', 'mkt_staff');
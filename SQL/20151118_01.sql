CREATE TABLE `adwords_data` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `sku` bigint(20) unsigned NOT NULL,
  `platform_id` varchar(7) NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '1' COMMENT 'enabled = 1, disabled = 0',
  `price` double(15,2) NOT NULL DEFAULT '0.00',
  `api_request_result` tinyint(4) DEFAULT '1',
  `comment` varchar(255) NOT NULL DEFAULT '',
  `create_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `create_at` int(10) unsigned NOT NULL DEFAULT '2130706433' COMMENT 'IP address, default 127.0.0.1',
  `create_by` varchar(32) NOT NULL DEFAULT 'system',
  `modify_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `modify_at` int(10) unsigned NOT NULL DEFAULT '2130706433' COMMENT 'IP address',
  `modify_by` varchar(32) NOT NULL DEFAULT 'system',
  PRIMARY KEY (`id`),
  KEY `idx_sku_platform` (`sku`,`platform_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `google_shopping` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `sku` bigint(20) unsigned NOT NULL,
  `platform_id` varchar(7) NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '1' COMMENT 'enabled = 1, disabled = 0',
  `price` double(15,2) NOT NULL DEFAULT '0.00',
  `api_request_result` tinyint(4) DEFAULT '1',
  `comment` text,
  `create_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `create_at` int(10) unsigned NOT NULL DEFAULT '2130706433' COMMENT 'IP address, default 127.0.0.1',
  `create_by` varchar(32) NOT NULL DEFAULT 'system',
  `modify_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `modify_at` int(10) unsigned NOT NULL DEFAULT '2130706433' COMMENT 'IP address',
  `modify_by` varchar(32) NOT NULL DEFAULT 'system',
  PRIMARY KEY (`id`),
  KEY `idx_sku_platform` (`sku`,`platform_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `affiliate_sku_platform` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `affiliate_id` varchar(16) NOT NULL,
  `sku` bigint(20) unsigned NOT NULL,
  `platform_id` varchar(7) NOT NULL DEFAULT 'WEBSITE',
  `status` tinyint(2) unsigned NOT NULL DEFAULT '1' COMMENT '0 = auto / 1 = exclude / 2 = include.',
  `create_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `create_at` int(10) unsigned NOT NULL DEFAULT '2130706433' COMMENT 'IP address, default 127.0.0.1',
  `create_by` varchar(32) NOT NULL DEFAULT 'system',
  `modify_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `modify_at` int(10) unsigned NOT NULL DEFAULT '2130706433' COMMENT 'IP address',
  `modify_by` varchar(32) NOT NULL DEFAULT 'system',
  PRIMARY KEY (`id`),
  KEY `idx_sku_affiliate` (`sku`,`affiliate_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `product_identifier` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `prod_grp_cd` int(5) NOT NULL,
  `colour_id` varchar(2) NOT NULL,
  `country_id` char(2) NOT NULL,
  `ean` varchar(100) NOT NULL DEFAULT '',
  `mpn` varchar(20) NOT NULL DEFAULT '',
  `upc` varchar(20) NOT NULL DEFAULT '',
  `status` tinyint(2) unsigned NOT NULL DEFAULT '0' COMMENT '0 = Inactive / 1 = Active',
  `create_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `create_at` int(10) unsigned NOT NULL DEFAULT '2130706433' COMMENT 'IP address, default 127.0.0.1',
  `create_by` varchar(32) NOT NULL DEFAULT 'system',
  `modify_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `modify_at` int(10) unsigned NOT NULL DEFAULT '2130706433' COMMENT 'IP address',
  `modify_by` varchar(32) NOT NULL DEFAULT 'system',
  PRIMARY KEY (`id`),
  KEY `idx_grp_colour_country` (`prod_grp_cd`,`colour_id`,`country_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `affiliate` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `affiliate_id` varchar(16) NOT NULL,
  `platform_id` varchar(7) NOT NULL,
  `affiliate_description` varchar(255) NOT NULL,
  `ext_party` varchar(20) NOT NULL DEFAULT '' COMMENT 'used by category_mapping',
  `create_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `create_at` int(10) unsigned NOT NULL DEFAULT '2130706433' COMMENT 'IP address, default 127.0.0.1',
  `create_by` varchar(32) NOT NULL DEFAULT 'system',
  `modify_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `modify_at` int(10) unsigned NOT NULL DEFAULT '2130706433' COMMENT 'IP address',
  `modify_by` varchar(32) NOT NULL DEFAULT 'system',
  PRIMARY KEY (`id`),
  KEY `idx_affiliate` (`affiliate_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO affiliate (affiliate_id,platform_id,affiliate_description,ext_party)
SELECT id,platform_id,affiliate_description,ext_party FROM `affiliate_copy`;

CREATE TABLE `price_margin` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `sku` bigint(20) unsigned NOT NULL,
  `platform_id` varchar(7) NOT NULL,
  `selling_price` double(15,2) NOT NULL DEFAULT '0.00',
  `profit` double(15,2) NOT NULL DEFAULT '0.00',
  `margin` double(15,2) NOT NULL DEFAULT '0.00',
  `create_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `create_at` int(10) unsigned NOT NULL DEFAULT '2130706433' COMMENT 'IP address, default 127.0.0.1',
  `create_by` varchar(32) NOT NULL DEFAULT 'system',
  `modify_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `modify_at` int(10) unsigned NOT NULL DEFAULT '2130706433' COMMENT 'IP address',
  `modify_by` varchar(32) NOT NULL DEFAULT 'system',
  PRIMARY KEY (`id`),
  KEY `idx_sku_platform` (`sku`,`platform_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO price_margin (sku,platform_id,selling_price,profit,margin)
SELECT p.sku,a.platform_id,a.selling_price,a.profit,a.margin FROM `price_margin_copy` a
INNER JOIN product p ON a.sku = p.`sku_bak`;

CREATE TABLE `display_qty_factor` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `cat_id` bigint(20) unsigned NOT NULL,
  `class_id` bigint(20) unsigned NOT NULL,
  `factor` double(3,2) unsigned NOT NULL DEFAULT '1.00',
  `create_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `create_at` int(10) unsigned NOT NULL DEFAULT '2130706433' COMMENT 'IP address, default 127.0.0.1',
  `create_by` varchar(32) NOT NULL DEFAULT 'system',
  `modify_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `modify_at` int(10) unsigned NOT NULL DEFAULT '2130706433' COMMENT 'IP address',
  `modify_by` varchar(32) NOT NULL DEFAULT 'system',
  PRIMARY KEY (`id`),
  unique key idx_cat_class (`cat_id`,`class_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO display_qty_factor (cat_id,class_id,factor)
SELECT cat_id,class_id,factor FROM `display_qty_factor_copy`;

insert into application (id, app_name, description, display_order, status, display_row, url, app_group_id, create_on, create_at, create_by, modify_at, modify_by)
values ('MKT0200', 'Pricing Tool Admin', 'Pricing Tool Admin', 150, 1, 1, 'marketing/pricing_tools', 4, now(), '127.0.0.1', 'brave', '127.0.0.1', 'brave');
call add_role_right('MKT0200', 'admin');














CREATE TABLE `category_mapping` (
  `id` int not null auto_increment,
  `ext_party` varchar(20) NOT NULL,
  `level` int(3) NOT NULL COMMENT '0 = SKU / 1 = Category / 2 = Sub-Category',
  `category_mapping_id` varchar(64) NOT NULL COMMENT 'sku / cat_id / sub_cat_id',
  `ext_id` varchar(64) NOT NULL DEFAULT '',
  `ext_name` varchar(256) DEFAULT NULL COMMENT 'this field is not used and please refer to another ext_name field in tbl external_category via ext_id',
  `lang_id` varchar(16) NOT NULL DEFAULT '',
  `country_id` char(2) NOT NULL DEFAULT '' COMMENT 'International country code (2 characters)',
  `product_name` varchar(70) DEFAULT NULL,
  `status` tinyint(2) unsigned NOT NULL DEFAULT '1' COMMENT '0 = Inactive / 1 = Active',
  `create_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `create_at` int(10) unsigned NOT NULL DEFAULT '2130706433' COMMENT 'IP address, default 127.0.0.1',
  `create_by` varchar(32) NOT NULL DEFAULT 'system',
  `modify_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `modify_at` int(10) unsigned NOT NULL DEFAULT '2130706433' COMMENT 'IP address',
  `modify_by` varchar(32) NOT NULL DEFAULT 'system',
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
CREATE TABLE `product_complementary_acc` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `mainprod_sku` varchar(15) NOT NULL COMMENT 'main product''s sku',
  `vb_main_prod` varchar(15) NOT NULL DEFAULT '',
  `accessory_sku` varchar(15) NOT NULL COMMENT 'accessory''s sku to map to the main product''s sku',
  `vb_accessory_sku` varchar(15) NOT NULL DEFAULT '',
  `dest_country_id` char(2) NOT NULL COMMENT 'destination country to attach this complementary accessory',
  `status` int(2) NOT NULL DEFAULT '1' COMMENT '0 = inactive, 1 = active',
  `create_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `create_at` int(10) unsigned NOT NULL DEFAULT '2130706433' COMMENT 'IP address, default 127.0.0.1',
  `create_by` varchar(32) NOT NULL DEFAULT 'system',
  `modify_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  `modify_at` int(10) unsigned NOT NULL DEFAULT '2130706433' COMMENT 'IP address',
  `modify_by` varchar(32) NOT NULL DEFAULT 'system',
  PRIMARY KEY (`id`),
  KEY `fk_ca_casku` (`accessory_sku`),
  KEY `country_sku` (`dest_country_id`,`mainprod_sku`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8
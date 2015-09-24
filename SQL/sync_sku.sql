-- ----------------------------
-- Table structure for interface_sku_info
-- ----------------------------
DROP TABLE IF EXISTS `interface_sku_info`;
CREATE TABLE `interface_sku_info` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `batch_id` int(11) unsigned NOT NULL,
  `cps_batch_id` char(11) NOT NULL,
  `prod_sku` varchar(20) DEFAULT '',
  `master_sku` varchar(20) DEFAULT '',
  `mastersku_cached` varchar(20) NOT NULL,
  `pricehkd` decimal(10,2) NOT NULL DEFAULT '0.00',
  `price` decimal(10,2) unsigned NOT NULL DEFAULT '0.00',
  `currency_id` varchar(3) NOT NULL DEFAULT '',
  `region` varchar(3) NOT NULL DEFAULT '',
  `location` varchar(3) NOT NULL DEFAULT '',
  `moq` int(11) NOT NULL DEFAULT '0',
  `lead_days` int(11) NOT NULL DEFAULT '0',
  `lang_restricted` varchar(255) NOT NULL DEFAULT '',
  `comments` text,
  `surplus_qty` int(11) NOT NULL DEFAULT '0',
  `supply_status` int(11) NOT NULL DEFAULT '6',
  `status` varchar(2) NOT NULL DEFAULT 'N',
  `failed_reason` varchar(255) DEFAULT NULL,
  `create_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `create_at` int(10) unsigned NOT NULL DEFAULT '2130706433' COMMENT 'IP address, default 127.0.0.1',
  `create_by` varchar(32) NOT NULL DEFAULT 'system',
  `modify_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `modify_at` int(10) unsigned NOT NULL DEFAULT '2130706433' COMMENT 'IP address',
  `modify_by` varchar(32) NOT NULL DEFAULT 'system',
  PRIMARY KEY (`id`),
  KEY `idx_batch_id` (`batch_id`) USING BTREE,
  KEY `idx_mastersku_cached` (`mastersku_cached`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


-- ----------------------------
-- Table structure for product_history_sync
-- ----------------------------
DROP TABLE IF EXISTS `product_history_sync`;
CREATE TABLE `product_history_sync` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `batch_id` int(11) NOT NULL DEFAULT '0',
  `sku` varchar(15) NOT NULL,
  `quantity` int(10) unsigned NOT NULL DEFAULT '0',
  `lang_restricted` int(11) unsigned NOT NULL DEFAULT '0',
  `currency_id` varchar(3) NOT NULL,
  `cost` decimal(10,2) NOT NULL DEFAULT '0.00',
  `lead_day` int(11) NOT NULL DEFAULT '0',
  `moq` int(11) NOT NULL DEFAULT '0',
  `supply_status` varchar(3) NOT NULL DEFAULT 'A',
  `create_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `create_at` int(10) unsigned NOT NULL DEFAULT '2130706433' COMMENT 'IP address, default 127.0.0.1',
  `create_by` varchar(32) NOT NULL DEFAULT 'system',
  `modify_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `modify_at` int(10) unsigned NOT NULL DEFAULT '2130706433' COMMENT 'IP address',
  `modify_by` varchar(32) NOT NULL DEFAULT 'system',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8;


#add APP ID
INSERT INTO `application` (`id`, `app_name`, `parent_app_id`, `description`, `display_order`, `status`, `display_row`, `url`, `app_group_id`, `create_on`, `create_at`, `create_by`, `modify_on`, `modify_at`, `modify_by`) 
VALUES ('INT0003', 'Cron update product', NULL, 'Cron update product', '0', '1', '1', NULL, NULL, NOW(), '127.0.0.1', 'will zhang', now(), '127.0.0.1', 'will zhang');

call add_role_right('INT0003', 'admin');

ALTER TABLE `supplier_prod`
ADD COLUMN `comments` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL AFTER `supplier_status`;

ALTER TABLE `supplier_prod`
ADD COLUMN `location` varchar(5) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' AFTER `moq`;

ALTER TABLE `supplier_prod`
ADD COLUMN `region` varchar(5) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' AFTER `location`;

ALTER TABLE `supplier_prod`
ADD COLUMN `surplus_qty` int(5) NOT NULL DEFAULT 0 AFTER `region`;

ALTER TABLE `supplier_prod`
ADD COLUMN `pricehkd`  double(15,2) NOT NULL AFTER `cost`;


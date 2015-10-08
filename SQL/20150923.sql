CREATE TABLE `payment_option` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `platform_id` varchar(7) COLLATE utf8_bin NOT NULL DEFAULT '',
  `page` varchar(32) COLLATE utf8_bin NOT NULL,
  `set_id` int(11) NOT NULL,
  `create_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `create_at` varchar(16) COLLATE utf8_bin NOT NULL,
  `create_by` varchar(32) COLLATE utf8_bin NOT NULL,
  `modify_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `modify_at` varchar(16) COLLATE utf8_bin NOT NULL,
  `modify_by` varchar(32) COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_platform_id` (`platform_id`,`page`) USING BTREE,
  KEY `idx_set_id` (`set_id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

CREATE TABLE `payment_option_set` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `set_id` int(11) NOT NULL,
  `name` varchar(64) NOT NULL,
  `status` smallint(1) NOT NULL,
  `create_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `create_at` varchar(16) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `create_by` varchar(32) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `modify_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `modify_at` varchar(16) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `modify_by` varchar(32) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_payment_set` (`set_id`) USING BTREE,
  KEY `idx_status` (`status`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

CREATE TABLE `payment_option_set_content` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `set_id` int(11) NOT NULL,
  `card_code` varchar(20) NOT NULL,
  `ref_currency` varchar(3) NOT NULL,
  `ref_from_amt` double(10,2) NOT NULL,
  `ref_to_amt_exclusive` double(10,2) NOT NULL,
  `status` tinyint(2) unsigned NOT NULL DEFAULT '1' COMMENT '0 = Inactive / 1 = Active',
  `create_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `create_at` varchar(16) NOT NULL,
  `create_by` varchar(32) NOT NULL,
  `modify_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `modify_at` varchar(16) NOT NULL,
  `modify_by` varchar(32) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_set_id` (`set_id`) USING BTREE,
  KEY `idx_card_code` (`card_code`) USING BTREE,
  KEY `idx_currency` (`ref_currency`) USING BTREE,
  KEY `idx_set_id_status` (`set_id`,`status`),
  CONSTRAINT `fk_set_id` FOREIGN KEY (`set_id`) REFERENCES `payment_option_set` (`set_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

CREATE TABLE `payment_option_card` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code` varchar(20) NOT NULL,
  `payment_gateway_id` varchar(20) NOT NULL DEFAULT 'paypal',
  `card_id` varchar(20) NOT NULL,
  `card_name` varchar(64) NOT NULL,
  `card_image` varchar(64) DEFAULT NULL,
  `status` tinyint(2) unsigned NOT NULL DE'1' COMMENT '0 = Inactive / 1 = Active',
  `create_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `create_at` varchar(16) NOT NULL,
  `create_by` varchar(32) NOT NULL,
  `modify_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `modify_at` varchar(16) NOT NULL,
  `modify_by` varchar(32) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_code` (`code`) USING BTREE,
  KEY `idx_code_status` (`code`,`status`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

/*
ALTER TABLE `payment_gateway`
ADD INDEX `idx_payment_gateway_id_status` (`payment_gateway_id`, `status`) USING BTREE ;

ALTER TABLE `payment_option_card`
ADD UNIQUE INDEX `idx_code` (`code`) USING BTREE ,
ADD INDEX `idx_code_status` (`code`, `status`) USING BTREE ;

ALTER TABLE `payment_option_set_content`
ADD INDEX `idx_set_id_status` (`set_id`, `status`) ;

ALTER TABLE `payment_option`
ADD INDEX `idx_set_id` (`set_id`) USING BTREE ;

*/

insert into payment_option_card(code, payment_gateway_id, card_id, card_name, card_image, status, create_on, create_at, create_by, modify_at, modify_by)
select code, payment_gateway_id, card_id, card_name, card_image, status, now(), '127.0.0.1', 'oswald', '127.0.0.1', 'oswald' from pmgw_card where code like "paypal%" or code like "mb_%";

insert into payment_option_set
(id, set_id, name, status, create_on, create_at, create_by, modify_at, modify_by)
values
(1, 1, "GBP_Set", 1, now(), '127.0.0.1', 'oswald', '127.0.0.1', 'oswald'),
(2, 2, "EUR_Set", 1, now(), '127.0.0.1', 'oswald', '127.0.0.1', 'oswald');

insert into payment_option
(id, platform_id, page, set_id, create_on, create_at, create_by, modify_at, modify_by)
values
(1, "WEBGB", "checkout", 1, now(), '127.0.0.1', 'oswald', '127.0.0.1', 'oswald'),
(2, "WEBFR", "checkout", 2, now(), '127.0.0.1', 'oswald', '127.0.0.1', 'oswald'),
(3, "WEBES", "checkout", 2, now(), '127.0.0.1', 'oswald', '127.0.0.1', 'oswald'),
(4, "WEBIT", "checkout", 2, now(), '127.0.0.1', 'oswald', '127.0.0.1', 'oswald');

insert into payment_option_set_content
(id, set_id, card_code, ref_currency, ref_from_amt, ref_to_amt_exclusive, status, create_on, create_at, create_by, modify_at, modify_by)
VALUES
(1, 1, "paypal_VSA", "GBP", 0, 20000, 1, now(), '127.0.0.1', 'oswald', '127.0.0.1', 'oswald'),
(2, 1, "paypal_MSC", "GBP", 0, 20000, 1, now(), '127.0.0.1', 'oswald', '127.0.0.1', 'oswald'),
(3, 1, "paypal", "GBP", 0, 20000, 1, now(), '127.0.0.1', 'oswald', '127.0.0.1', 'oswald');


update payment_option_card set card_image='90x45/btn_paypal.png' where code='paypal';


ALTER TABLE `so`
DROP COLUMN `finance_dispatch_date`,
MODIFY COLUMN `fingerprint_id`  varchar(128) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' AFTER `dispatch_date`;

ALTER TABLE `so`
MODIFY COLUMN `vat_percent`  double(5,2) UNSIGNED NOT NULL DEFAULT '0.00' AFTER `cost`;

ALTER TABLE `so`
ADD INDEX `idx_create` (`order_create_date`) USING BTREE ;

ALTER TABLE `so_item`
ADD INDEX `idx_create_on` (`create_on`) USING BTREE ;

ALTER TABLE `so_item_detail`
ADD INDEX `idx_create_on` (`create_on`) USING BTREE ;

ALTER TABLE `so_item_detail`
ADD COLUMN `prod_name`  varchar(255) NOT NULL DEFAULT '' AFTER `item_sku`,
ADD COLUMN `ext_item_cd`  varchar(255) NOT NULL DEFAULT '' AFTER `prod_name`,
ADD COLUMN `website_status`  varchar(2) NOT NULL DEFAULT '' AFTER `margin_raw`,
ADD COLUMN `warranty_in_month`  smallint(2) NOT NULL DEFAULT 0 AFTER `website_status`,
ADD COLUMN `supplier_status`  varchar(2) NOT NULL DEFAULT '' COMMENT 'A = Readily Available / O = Temp Out of Stock' AFTER `warranty_in_month`;

ALTER TABLE `so_item_detail`
DROP COLUMN `discount`,
MODIFY COLUMN `bundle_core_id`  bigint(20) NOT NULL DEFAULT 0 COMMENT 'bundle_core_id used' AFTER `discount_total`;

ALTER TABLE `so_item_detail`
MODIFY COLUMN `profit`  double(15,2) NOT NULL DEFAULT 0.00 COMMENT '//unit profit' AFTER `item_unit_cost`,
MODIFY COLUMN `profit_raw`  double(15,2) NOT NULL DEFAULT 0.00 COMMENT 'profit w/o promo, unit profit' AFTER `profit`;

ALTER TABLE `so_credit_chk`
DROP COLUMN `t3m_is_sent`,
DROP COLUMN `t3m_in_file`,
DROP COLUMN `t3m_result`,
MODIFY COLUMN `card_holder`  varchar(64) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' AFTER `so_no`;

ALTER TABLE `so_risk`
CHANGE COLUMN `risk_var1` `risk_var_1`  varchar(64) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' AFTER `risk_requested`,
CHANGE COLUMN `risk_var2` `risk_var_2`  varchar(64) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' AFTER `risk_var_1`,
CHANGE COLUMN `risk_var3` `risk_var_3`  varchar(64) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' AFTER `risk_var_2`,
CHANGE COLUMN `risk_var4` `risk_var_4`  varchar(64) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' AFTER `risk_var_3`,
CHANGE COLUMN `risk_var5` `risk_var_5`  varchar(64) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' AFTER `risk_var_4`,
CHANGE COLUMN `risk_var6` `risk_var_6`  varchar(64) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' AFTER `risk_var_5`,
CHANGE COLUMN `risk_var7` `risk_var_7`  varchar(64) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' AFTER `risk_var_6`,
CHANGE COLUMN `risk_var8` `risk_var_8`  varchar(64) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' AFTER `risk_var_7`,
CHANGE COLUMN `risk_var9` `risk_var_9`  varchar(64) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' AFTER `risk_var_8`,
CHANGE COLUMN `risk_var10` `risk_var_10`  varchar(1024) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' AFTER `risk_var_9`;

ALTER TABLE `so_payment_log` DROP FOREIGN KEY `fk_sopl_so_no`;
ALTER TABLE `so_payment_query_log` DROP FOREIGN KEY `fk_sopql_so_no`;

ALTER TABLE `so_item_detail`
ADD COLUMN `product_type`  int(11) NOT NULL DEFAULT 0 AFTER `qty`;

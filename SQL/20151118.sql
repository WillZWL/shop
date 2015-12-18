update application set url='marketing/ext-category-mapping' where id='MKT0074';

ALTER TABLE `category`
ADD COLUMN `hidden`  tinyint(1) NOT NULL DEFAULT 0 AFTER `status`;

update category set hidden=1 where name like '%Do not%';
update category set hidden=0 where name not like '%Do not%';

ALTER TABLE `google_shopping`
ADD COLUMN `id`  int(11) NULL AUTO_INCREMENT FIRST ,
DROP PRIMARY KEY,
ADD PRIMARY KEY (`id`),
DROP INDEX `platform_id` ,
ADD INDEX `idx_platform_id` (`platform_id`) USING BTREE ,
ADD INDEX `idx_status` (`status`) USING BTREE ;



ALTER TABLE `external_category`
ADD INDEX `idx_ext_id` (`ext_id`) USING BTREE ;

ALTER TABLE `category_mapping`
ADD INDEX `idx_join` (`category_mapping_id`, `level`, `country_id`, `status`) USING BTREE ,
ADD INDEX `idx_product_name` (`product_name`) USING BTREE ,
ADD INDEX `idx_ext_party` (`ext_party`) USING BTREE ;

update category_mapping cm
inner join product p on p.sku_vb=cm.category_mapping_id
set cm.category_mapping_id=p.sku;

update category_mapping set product_name='' where product_name is null;

ALTER TABLE `affiliate_sku_platform`
DROP INDEX `idx_sku_affiliate` ,
ADD INDEX `idx_sku_affiliate` (`sku`, `affiliate_id`, `platform_id`) USING BTREE ;

ALTER TABLE `category_mapping`
MODIFY COLUMN `category_mapping_id`  bigint(20) UNSIGNED NOT NULL COMMENT 'sku / cat_id / sub_cat_id' AFTER `level`;

drop table google_shopping;
CREATE TABLE `google_api_request` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `request_batch_id` bigint(20) NOT NULL,
  `platform_id` char(5) NOT NULL,
  `sku` bigint(20) NOT NULL,
  `item_group_id` int(11) NOT NULL,
  `google_product_id` varchar(32) NOT NULL,
  `colour_id` char(2) NOT NULL DEFAULT '',
  `colour_name` varchar(64) NOT NULL DEFAULT '',
  `target_country` varchar(2) NOT NULL,
  `content_language` varchar(2) NOT NULL,
  `title` varchar(256) NOT NULL,
  `google_product_category` varchar(256) NOT NULL DEFAULT '' COMMENT 'PAUSED, ENABLED',
  `product_type` varchar(512) NOT NULL,
  `cat_id` bigint(20) unsigned NOT NULL,
  `cat_name` varchar(64) NOT NULL DEFAULT '',
  `brand_name` varchar(32) NOT NULL,
  `gtin` varchar(128) NOT NULL DEFAULT '',
  `upc` varchar(128) NOT NULL DEFAULT '',
  `mpn` varchar(128) NOT NULL DEFAULT '',
  `ean` varchar(128) NOT NULL DEFAULT '',
  `shipping_weight_value` double(8,2) NOT NULL,
  `image_link` varchar(512) NOT NULL,
  `link` varchar(512) NOT NULL,
  `currency` varchar(3) NOT NULL,
  `price` double(15,2) NOT NULL,
  `description` text,
  `google_product_status` varchar(10) NOT NULL,
  `custom_attribute_promo_id` varchar(255) NOT NULL DEFAULT '',
  `ref_website_quantity` int(4) NOT NULL DEFAULT '0',
  `ref_display_quantity` int(4) NOT NULL DEFAULT '0',
  `ref_listing_status` varchar(1) NOT NULL,
  `ref_website_status` char(2) NOT NULL DEFAULT '',
  `ref_exdemo` tinyint(4) NOT NULL,
  `availability` varchar(32) NOT NULL DEFAULT '',
  `condition` varchar(32) NOT NULL DEFAULT '',
  `result` char(1) NOT NULL DEFAULT 'N' COMMENT 'F = Fail, S = Success, N = NEW',
  `api_response` text,
  `create_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `create_at` int(10) unsigned NOT NULL DEFAULT '2130706433' COMMENT 'IP address, default 127.0.0.1',
  `create_by` varchar(32) NOT NULL DEFAULT 'system',
  `modify_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `modify_at` int(10) unsigned NOT NULL DEFAULT '2130706433' COMMENT 'IP address',
  `modify_by` varchar(32) NOT NULL DEFAULT 'system',
  PRIMARY KEY (`id`),
  KEY `idx_request_batch_id` (`request_batch_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

insert into application (id, app_name, description, display_order, status, display_row, create_on, create_at, create_by, modify_at, modify_by)
values ('CRN0044', 'Cron for sending API to do integration', 'Cron for sending API to do integration', 0, 1, 1, now(), '2130706433', 'oswald', '2130706433', 'oswald');
call add_role_right('CRN0044', 'admin');

ALTER TABLE `payment_option`
DROP INDEX `idx_platform_id` ,
ADD UNIQUE INDEX `idx_platform_id` (`platform_id`, `page`) USING BTREE ;


/* above is LIVE */

ALTER TABLE `google_api_request`
MODIFY COLUMN `result`  char(1) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'N' COMMENT 'F = Fail, S = Success, W = Success with Warning, N = NEW' AFTER `condition`;

ALTER TABLE `google_api_request`
ADD COLUMN `warning`  varchar(2048) NOT NULL DEFAULT '' AFTER `result`;

ALTER TABLE `google_api_request`
CHANGE COLUMN `warning` `key_message`  varchar(2048) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' AFTER `result`;

ALTER TABLE `google_api_request`
ADD INDEX `idx_request_result` (`result`) USING BTREE ;

ALTER TABLE `google_request_batch`
MODIFY COLUMN `status`  varchar(2) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'N' COMMENT 'N = New / P = Processing / C = Completed / CE = Completed with Error / RP = ReProcessing / F = Completely Fail / U = Unknown Error' AFTER `func_name`;

ALTER TABLE `pending_google_api_request`
ADD COLUMN `ref_is_advertised`  char(1) NOT NULL DEFAULT 'N' AFTER `ref_exdemo`;

ALTER TABLE `pending_google_api_request`
ADD INDEX `idx_is_advertised` (`ref_is_advertised`) USING BTREE ;

ALTER TABLE `google_api_request`
MODIFY COLUMN `google_product_status`  varchar(10) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT 'I = Insert / D = Delete' AFTER `description`;

ALTER TABLE `pending_google_api_request`
DROP COLUMN `google_product_status`,
MODIFY COLUMN `custom_attribute_promo_id`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' AFTER `description`;

ALTER TABLE `google_api_request`
ADD COLUMN `ref_is_advertised`  char(1) NOT NULL DEFAULT '' AFTER `ref_exdemo`;

ALTER TABLE `google_api_request`
ADD INDEX `idx_criteria` (`ref_website_quantity`, `ref_display_quantity`, `ref_listing_status`, `ref_website_status`, `ref_is_advertised`) ;


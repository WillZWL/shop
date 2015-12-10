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


/* above is LIVE */
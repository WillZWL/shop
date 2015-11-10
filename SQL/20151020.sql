ALTER TABLE `so`
ADD COLUMN `refund_reason` VARCHAR(255) NOT NULL DEFAULT '' AFTER `hold_reason`;

ALTER TABLE `so`
ADD COLUMN `order_note` VARCHAR(255) NOT NULL DEFAULT '' AFTER `refund_reason`;

ALTER TABLE `product_custom_classification`
DROP INDEX `idx_sku_country` ,
ADD UNIQUE KEY `idx_sku_country` (`sku`, `country_id`);

ALTER TABLE `product_custom_classification`
CHANGE COLUMN `sku` `sku`  bigint(20) unsigned NOT NULL;

ALTER TABLE `platform_biz_var`
DROP INDEX `idx_selling_platform_id` ,
ADD UNIQUE KEY `idx_platform_country_language`
(`selling_platform_id`,`platform_currency_id`,`dest_country`,`language_id`,`delivery_type`),
ADD KEY `idx_currency_type`
(`platform_currency_id`,`delivery_type`);
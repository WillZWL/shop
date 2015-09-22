ALTER TABLE `platform_biz_var`
ADD UNIQUE INDEX `idx_selling_platform_id` (`selling_platform_id`) USING BTREE ;

ALTER TABLE `site_config`
ADD UNIQUE INDEX `idx_domain` (`domain`) USING BTREE ,
ADD INDEX `idx_platform_id` (`platform`) USING BTREE ;

ALTER TABLE `currency`
ADD UNIQUE INDEX `idx_currency_id` (`currency_id`) USING BTREE ;

ALTER TABLE `platform_pmgw`
DROP COLUMN `time_from`,
DROP COLUMN `time_to_exclusive`,
MODIFY COLUMN `payment_gateway_id`  varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'paypal' AFTER `sequence`,
MODIFY COLUMN `status`  tinyint(2) UNSIGNED NOT NULL DEFAULT 1 COMMENT '0 = Inactive / 1 = Active' AFTER `ref_to_amt_exclusive`;

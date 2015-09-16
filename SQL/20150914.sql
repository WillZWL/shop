ALTER TABLE `platform_biz_var`
ADD UNIQUE INDEX `idx_selling_platform_id` (`selling_platform_id`) USING BTREE ;

ALTER TABLE `site_config`
ADD UNIQUE INDEX `idx_domain` (`domain`) USING BTREE ,
ADD INDEX `idx_platform_id` (`platform`) USING BTREE ;

ALTER TABLE `currency`
ADD UNIQUE INDEX `idx_currency_id` (`currency_id`) USING BTREE ;


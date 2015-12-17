ALTER TABLE `product_content`
ADD COLUMN `stop_sync`  tinyint(4) NULL DEFAULT 1 COMMENT 'value 0 = completely no selection, bit 0 = No stop, bit 1 = Stop prod_name, bit 2 = Stop contents, bit 3 = Stop keyworks, bit 4 = Stop detail_desc' AFTER `youtube_caption_2`;

ALTER TABLE `product_content_extend`
ADD COLUMN `stop_sync`  tinyint(4) NULL DEFAULT 1 COMMENT 'value 0 = completely no selection, bit 0 = No stop, bit 1 = Stop feature, bit 2 = Stop specification, bit 3 = Stop enhanced_listing' AFTER `enhanced_listing`;

ALTER TABLE sub_cat_platform_var CHANGE COLUMN `platform_commission` `platform_commission_percent` DECIMAL(8,2) UNSIGNED NOT NULL DEFAULT 0.00 COMMENT 'Commission is in percentage.';


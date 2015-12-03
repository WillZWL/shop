ALTER TABLE `category`
ADD COLUMN `stop_sync_priority`  tinyint(2) NOT NULL DEFAULT 0 COMMENT '0: sync VB value; 1: no sync VB value' AFTER `priority`;

ALTER TABLE `category_extend`
ADD COLUMN `stop_sync_name`  tinyint(2) NOT NULL DEFAULT 0 AFTER `name`;

ALTER TABLE `product_image`
ADD COLUMN `stop_sync_image`  tinyint(2) NOT NULL DEFAULT 0 COMMENT '0: sync VB data; 1: no sync VB data' AFTER `vb_image`;

ALTER TABLE `price`
ADD COLUMN `google_status`  varchar(2) NOT NULL DEFAULT '' AFTER `delivery_scenarioid`,
ADD COLUMN `google_update_result`  varchar(2048) NOT NULL DEFAULT '' AFTER `google_status`;

ALTER TABLE `price`
MODIFY COLUMN `google_status`  varchar(2) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '1st char: I/D = Insert/Delete, 2nd char S/F/W = Success/Fail/Warnings' AFTER `delivery_scenarioid`;


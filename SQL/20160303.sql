ALTER TABLE `payment_option_set_content` DROP FOREIGN KEY `fk_set_id`;

ALTER TABLE `payment_option_set`
DROP COLUMN `set_id`,
MODIFY COLUMN `name`  varchar(64) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL AFTER `id`,
DROP INDEX `idx_payment_set`;

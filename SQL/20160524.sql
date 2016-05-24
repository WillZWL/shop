ALTER TABLE `client`
ADD COLUMN `verify_code` VARCHAR(255) NOT NULL DEFAULT '' AFTER `password`;
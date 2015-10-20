ALTER TABLE `so`
ADD COLUMN `refund_reason` VARCHAR(255) NOT NULL DEFAULT '' AFTER `hold_reason`;
ALTER TABLE `so`
ADD COLUMN `order_note` VARCHAR(255) NOT NULL DEFAULT '' AFTER `refund_reason`;
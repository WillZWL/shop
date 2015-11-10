ALTER TABLE `so_credit_chk`
CHANGE COLUMN `card_last4` `card_last_4` char(4) NOT NULL DEFAULT '';

ALTER TABLE `so_hold_reason`
ADD KEY idx_so_reason_create (`so_no`,`reason`,`create_on`);

ALTER TABLE `refund` DROP FOREIGN KEY `fk_refund_so`;
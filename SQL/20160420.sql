ALTER TABLE `so`
ADD KEY `idx_hold_status` (`hold_status`),
ADD KEY `idx_refund_status` (`refund_status`),
ADD COLUMN `rec_courier` varchar(255) NOT NULL DEFAULT '' COMMENT 'recommended courier' AFTER `delivery_type_id`,
ADD COLUMN `payment_gateway_id` varchar(20) NOT NULL DEFAULT '' AFTER `order_note`,
ADD COLUMN `order_total_item` smallint(2) unsigned NOT NULL DEFAULT '0' AFTER `weight`;

UPDATE so,(SELECT so_no, `rec_courier` FROM `integrated_order_fulfillment` GROUP BY so_no) a
SET so.`rec_courier`=a.`rec_courier`
WHERE so.`so_no` = a.`so_no`;

UPDATE so,(SELECT so_no, `payment_gateway_id` FROM `integrated_order_fulfillment` GROUP BY so_no) a
SET so.`payment_gateway_id`=a.`payment_gateway_id`
WHERE so.`so_no` = a.`so_no`;

UPDATE so,(SELECT so_no, count(*) total FROM `so_item_detail` GROUP BY so_no) a
SET so.`order_total_item`=a.total
WHERE so.`so_no` = a.`so_no`;


-- # remove integrated_order_fulfillment table
DROP TABLE `integrated_order_fulfillment`;

DROP TRIGGER IF EXISTS `tg_so_payment_status_insert`;
DROP TRIGGER IF EXISTS `tg_so_payment_status_update`;
DROP TRIGGER IF EXISTS `tg_so_item_detail_update`;
DROP TRIGGER IF EXISTS `tg_so_item_detail_insert`;


DROP TRIGGER IF EXISTS `tg_so_update`;

DELIMITER //
CREATE TRIGGER `tg_so_update` AFTER UPDATE ON `so` FOR EACH ROW BEGIN
    IF (OLD.hold_status != NEW.hold_status) THEN
        INSERT INTO so_hold_status_history (so_no, hold_status, create_on, create_at, create_by, modify_on, modify_at, modify_by)
            VALUES (NEW.so_no, NEW.hold_status, NEW.create_on, NEW.create_at, NEW.create_by, NEW.modify_on, NEW.modify_at, NEW.modify_by);
    END IF;
    IF (OLD.status <> NEW.status) THEN
        INSERT INTO order_status_history (`id`, `so_no`, `status`, `create_on`, `create_at`, `create_by`, `modify_on`, `modify_at`, `modify_by`) VALUES (NULL,NEW.so_no, NEW.status,NEW.modify_on,NEW.modify_at,NEW.modify_by,NEW.modify_on,NEW.modify_at,NEW.modify_by);
    END IF;
END //
DELIMITER ;
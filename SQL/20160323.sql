-- so table
delimiter ||
DROP TRIGGER IF EXISTS `tg_so_insert`;

CREATE TRIGGER `tg_so_insert` AFTER INSERT ON `so` FOR EACH ROW BEGIN
    INSERT INTO so_hold_status_history (so_no, hold_status, create_on, create_at, create_by, modify_on, modify_at, modify_by)
        VALUES (NEW.so_no, NEW.hold_status, NEW.create_on, NEW.create_at, NEW.create_by, NEW.modify_on, NEW.modify_at, NEW.modify_by);
    INSERT INTO order_status_history (`id`, `so_no`, `status`, `create_on`, `create_at`, `create_by`, `modify_on`, `modify_at`, `modify_by`)
        VALUES (NULL,NEW.so_no, NEW.status,NEW.create_on,NEW.create_at,NEW.create_by,NEW.create_on,NEW.create_at,NEW.create_by);
 END ||



delimiter ||
DROP TRIGGER IF EXISTS `tg_so_update`;

CREATE TRIGGER `tg_so_update` AFTER UPDATE ON `so` FOR EACH ROW BEGIN
    IF (OLD.hold_status != NEW.hold_status) THEN
        INSERT INTO so_hold_status_history (so_no, hold_status, create_on, create_at, create_by, modify_on, modify_at, modify_by)
            VALUES (NEW.so_no, NEW.hold_status, NEW.create_on, NEW.create_at, NEW.create_by, NEW.modify_on, NEW.modify_at, NEW.modify_by);
    END IF;
    IF (OLD.status <> NEW.status) THEN
        INSERT INTO order_status_history (`id`, `so_no`, `status`, `create_on`, `create_at`, `create_by`, `modify_on`, `modify_at`, `modify_by`) VALUES (NULL,NEW.so_no, NEW.status,NEW.modify_on,NEW.modify_at,NEW.modify_by,NEW.modify_on,NEW.modify_at,NEW.modify_by);
    UPDATE integrated_order_fulfillment SET `status` = NEW.`status` WHERE so_no = NEW.so_no;
    END IF;

    IF (OLD.refund_status <> NEW.refund_status) THEN
        UPDATE `integrated_order_fulfillment` SET refund_status = NEW.refund_status where so_no = NEW.so_no;
    END IF;
    IF(OLD.hold_status <> NEW.hold_status) THEN
        UPDATE `integrated_order_fulfillment` SET hold_status = NEW.hold_status where so_no = NEW.so_no;
    END IF;
    IF(OLD.expect_delivery_date <> NEW.expect_delivery_date) THEN
        UPDATE `integrated_order_fulfillment` SET expect_delivery_date = NEW.expect_delivery_date where so_no = NEW.so_no;
    END IF;

    IF (OLD.amount <> NEW.amount) THEN
        UPDATE `integrated_order_fulfillment` SET amount = NEW.amount where so_no = NEW.so_no;
    END IF;

    IF (OLD.split_so_group <> NEW.split_so_group) THEN
        UPDATE `integrated_order_fulfillment` SET split_so_group = NEW.split_so_group where so_no = NEW.so_no;
    END IF;
END ||


-- so_item_detail
delimiter ||
DROP TRIGGER IF EXISTS `tg_so_item_detail_insert`;

CREATE TRIGGER `tg_so_item_detail_insert` AFTER INSERT ON `so_item_detail` FOR EACH ROW BEGIN
    INSERT INTO `integrated_order_fulfillment` (so_no, line_no, sku, platform_id, platform_order_id, order_create_date, expect_delivery_date,product_name, website_status, delivery_name, delivery_country_id, delivery_type_id, payment_gateway_id, note,amount,refund_status, hold_status, qty, outstanding_qty, `status`, split_so_group, delivery_postcode)
    SELECT NEW.so_no, NEW.line_no, NEW.item_sku, so.platform_id, so.platform_order_id, so.order_create_date, so.expect_delivery_date, si.prod_name, si.website_status, so.delivery_name, so.delivery_country_id, so.delivery_type_id, NULL, NULL, so.amount, so.refund_status, so.hold_status, NEW.qty, NEW.outstanding_qty, so.`status`, so.split_so_group, so.delivery_postcode
    FROM so
    LEFT JOIN so_item as si ON so.so_no = si.so_no and NEW.line_no = si.line_no and NEW.item_sku = si.prod_sku
    WHERE so.so_no = NEW.so_no;

    UPDATE `integrated_order_fulfillment` SET order_total_sku = NEW.line_no where so_no = NEW.so_no;
END ||


delimiter ||
DROP TRIGGER IF EXISTS `tg_so_item_detail_update`;

CREATE TRIGGER `tg_so_item_detail_update` AFTER UPDATE ON `so_item_detail` FOR EACH ROW BEGIN
     IF(NEW.outstanding_qty <> OLD.outstanding_qty) THEN
        UPDATE integrated_order_fulfillment AS iof SET outstanding_qty = NEW.outstanding_qty where NEW.so_no = iof.so_no and NEW.line_no = iof.line_no and NEW.item_sku = iof.sku;
     END IF;
END ||

-- so_payment_status
delimiter ||
DROP TRIGGER IF EXISTS `tg_so_payment_status_insert`;

CREATE TRIGGER `tg_so_payment_status_insert` AFTER INSERT ON `so_payment_status` FOR EACH ROW BEGIN
    UPDATE integrated_order_fulfillment SET payment_gateway_id = NEW.payment_gateway_id where so_no = NEW.so_no;
END;

DROP TRIGGER IF EXISTS `tg_so_payment_status_update`;

CREATE TRIGGER `tg_so_payment_status_update` AFTER UPDATE ON `so_payment_status` FOR EACH ROW BEGIN
    IF (NEW.payment_gateway_id <> OLD.payment_gateway_id) THEN
        UPDATE integrated_order_fulfillment SET payment_gateway_id = NEW.payment_gateway_id where so_no = NEW.so_no;
    END IF;
END||
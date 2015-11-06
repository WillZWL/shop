ALTER TABLE `payment_option_set_content`
ADD COLUMN `priority`  smallint(2) NOT NULL DEFAULT 0 AFTER `ref_to_amt_exclusive`;

ALTER TABLE `payment_option_set_content`
ADD INDEX `idx_priority` (`priority`) USING BTREE ;


update payment_option_card set status=0;
update payment_option_card set status=1 where code in ('paypal', 'paypal_VSA', 'paypal_VSE', 'paypal_MSC', 'paypal_AMX');
update payment_option_card set status=1 where code in ('mb_VSA', 'mb_VSD', 'mb_VSE', 'mb_MSC', 'mb_GCB', 'mb_PWY20', 'mb_PSP', 'mb_CSI', 'mb_MAE');



insert into payment_option_set_content
(set_id, card_code, ref_currency, ref_from_amt, ref_to_amt_exclusive, priority, status, create_on, create_at, create_by, modify_at, modify_by)
VALUES
(1, "mb_VSA", "GBP", 0, 20000, 1, 1, now(), '127.0.0.1', 'oswald', '127.0.0.1', 'oswald'),
(1, "mb_MSC", "GBP", 0, 20000, 5, 1, now(), '127.0.0.1', 'oswald', '127.0.0.1', 'oswald'),
(1, "mb_VSD", "GBP", 0, 20000, 10, 1, now(), '127.0.0.1', 'oswald', '127.0.0.1', 'oswald');

delete from payment_option_set_content where set_id=1 and card_code="paypal_MSC";

update payment_option_set_content set card_code='paypal_AMX', priority=15 where id=1;
update payment_option_set_content set priority=99 where id=3;

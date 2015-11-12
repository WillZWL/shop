insert into schedule_job(schedule_job_id, name, last_access_time, status, create_on, create_at, create_by, modify_at, modify_by)
values('MONEYBOOKERS_ORDERS_VERIFICATION', 'MB check pending order', now(), 1, now(), '2130706433', 'oswald', '2130706433', 'oswald');

ALTER TABLE `so_payment_status`
ADD INDEX `idx_create_on` (`create_on`) USING BTREE ;


insert into payment_option_set
(id, set_id, name, status, create_on, create_at, create_by, modify_at, modify_by)
values
(3, 3, "AUD_Set", 1, now(), '127.0.0.1', 'oswald', '127.0.0.1', 'oswald'),
(4, 4, "NZD_Set", 1, now(), '127.0.0.1', 'oswald', '127.0.0.1', 'oswald');


insert into payment_option_set_content
(set_id, card_code, ref_currency, ref_from_amt, ref_to_amt_exclusive, priority, status, create_on, create_at, create_by, modify_at, modify_by)
VALUES
(3, "mb_VSA", "AUD", 0, 20000, 1, 1, now(), '127.0.0.1', 'oswald', '127.0.0.1', 'oswald'),
(3, "mb_MSC", "AUD", 0, 20000, 5, 1, now(), '127.0.0.1', 'oswald', '127.0.0.1', 'oswald'),
(3, "mb_VSD", "AUD", 0, 20000, 10, 1, now(), '127.0.0.1', 'oswald', '127.0.0.1', 'oswald'),
(3, "mb_PWY20", "AUD", 0, 20000, 10, 1, now(), '127.0.0.1', 'oswald', '127.0.0.1', 'oswald'),
(3, "paypal_AMX", "GBP", 0, 20000, 15, 1, now(), '127.0.0.1', 'oswald', '127.0.0.1', 'oswald'),
(3, "paypal", "GBP", 0, 20000, 99, 1, now(), '127.0.0.1', 'oswald', '127.0.0.1', 'oswald');


insert into payment_option_set_content
(set_id, card_code, ref_currency, ref_from_amt, ref_to_amt_exclusive, priority, status, create_on, create_at, create_by, modify_at, modify_by)
VALUES
(4, "mb_VSA", "AUD", 0, 1700, 1, 1, now(), '127.0.0.1', 'oswald', '127.0.0.1', 'oswald'),
(4, "mb_MSC", "AUD", 0, 1700, 5, 1, now(), '127.0.0.1', 'oswald', '127.0.0.1', 'oswald'),
(4, "mb_VSD", "AUD", 0, 1700, 10, 1, now(), '127.0.0.1', 'oswald', '127.0.0.1', 'oswald'),
(4, "paypal_AMX", "GBP", 1700, 15000, 15, 1, now(), '127.0.0.1', 'oswald', '127.0.0.1', 'oswald'),
(4, "paypal", "GBP", 1700, 15000, 99, 1, now(), '127.0.0.1', 'oswald', '127.0.0.1', 'oswald');


insert into payment_option
(id, platform_id, page, set_id, create_on, create_at, create_by, modify_at, modify_by)
values
(5, "WEBAU", "checkout", 3, now(), '127.0.0.1', 'oswald', '127.0.0.1', 'oswald'),
(6, "WEBNZ", "checkout", 4, now(), '127.0.0.1', 'oswald', '127.0.0.1', 'oswald');

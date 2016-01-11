insert into payment_option_set_content
(id, set_id, card_code, ref_currency, ref_from_amt, ref_to_amt_exclusive, priority, status, create_on, create_at, create_by, modify_at, modify_by)
VALUES
(18, 2, "mb_VSA", "GBP", 0, 20000, 1, 1, now(), '127.0.0.1', 'oswald', '127.0.0.1', 'oswald'),
(19, 2, "mb_VSD", "GBP", 0, 20000, 2, 1, now(), '127.0.0.1', 'oswald', '127.0.0.1', 'oswald'),
(20, 2, "mb_MSC", "GBP", 0, 20000, 3, 1, now(), '127.0.0.1', 'oswald', '127.0.0.1', 'oswald'),
(21, 2, "mb_GCB", "GBP", 0, 20000, 4, 1, now(), '127.0.0.1', 'oswald', '127.0.0.1', 'oswald'),
(22, 2, "paypal_AMX", "GBP", 0, 20000, 9, 1, now(), '127.0.0.1', 'oswald', '127.0.0.1', 'oswald'),
(23, 2, "paypal", "GBP", 0, 20000, 10, 1, now(), '127.0.0.1', 'oswald', '127.0.0.1', 'oswald');

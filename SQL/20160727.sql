INSERT INTO payment_option_card( code, payment_gateway_id, card_id, card_name, card_image, status, create_on, create_at, create_by, modify_at, modify_by)
SELECT CODE,
       payment_gateway_id,
       card_id,
       card_name,
       card_image,
       STATUS,
       now(),
       '127.0.0.1',
       'brave',
       '127.0.0.1',
       'brave'
FROM pmgw_card
WHERE CODE LIKE "gc_%";


INSERT INTO payment_option_set_content
(set_id, card_code, ref_currency, ref_from_amt, ref_to_amt_exclusive, priority, STATUS, create_on, create_at, create_by, modify_at, modify_by)
VALUES
# GB/set=1/GBP; gc_VSA/gc_MAC/gc_VSE/gc_VSD
(1, "gc_VSA", "GBP", 1000, 2000, 1, 1, now(), '2130706433', 'brave', '2130706433', 'brave'),
(1, "gc_MAC", "GBP", 1000, 2000, 2, 1, now(), '2130706433', 'brave', '2130706433', 'brave'),
(1, "gc_VSE", "GBP", 1000, 2000, 3, 1, now(), '2130706433', 'brave', '2130706433', 'brave'),
(1, "gc_VSD", "GBP", 1000, 2000, 4, 1, now(), '2130706433', 'brave', '2130706433', 'brave'),

# FR/set=2/EUR; gc_VSA/gc_MAC/gc_VSD
(2, "gc_VSA", "EUR", 1000, 2000, 1, 1, now(), '2130706433', 'brave', '2130706433', 'brave'),
(2, "gc_MAC", "EUR", 1000, 2000, 2, 1, now(), '2130706433', 'brave', '2130706433', 'brave'),
(2, "gc_VSD", "EUR", 1000, 2000, 3, 1, now(), '2130706433', 'brave', '2130706433', 'brave'),

# ES/set=5/EUR; /gc_VSA/gc_MAC/gc_VSD;
(5, "gc_VSA", "EUR", 1000, 2000, 1, 1, now(), '2130706433', 'brave', '2130706433', 'brave'),
(5, "gc_MAC", "EUR", 1000, 2000, 2, 1, now(), '2130706433', 'brave', '2130706433', 'brave'),
(5, "gc_VSD", "EUR", 1000, 2000, 3, 1, now(), '2130706433', 'brave', '2130706433', 'brave');

INSERT INTO `schedule_job` (`schedule_job_id`, `name`, `last_access_time`, `status`, `create_on`, `create_at`, `create_by`, `modify_on`, `modify_at`, `modify_by`)
VALUES
    ('GLOBAL_COLLECT_ORDERS_VERIFICATION', 'GC Check Pending Order', now(), '1', now(), 2130706433, 'brave', now(), 2130706433, 'brave');

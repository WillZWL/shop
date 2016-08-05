

update payment_option_set_content set `ref_to_amt_exclusive` = 2000 where set_id in (10,11,12) and status = 1;


INSERT INTO `payment_option_set_content` (`id`, `set_id`, `card_code`, `ref_currency`, `ref_from_amt`, `ref_to_amt_exclusive`, `priority`, `status`, `create_on`, `create_at`, `create_by`, `modify_on`, `modify_at`, `modify_by`)
VALUES
    (null, 10, 'Bank_Transfer', 'GBP', 2000.00, 200000.00, 10, 1, now(), 1270, 'feeling', now(), 1270, 'feeling'),
    (null, 11, 'Bank_Transfer', 'EUR', 2000.00, 200000.00, 10, 1, now(), 1270, 'feeling', now(), 1270, 'feeling'),
    (null, 12, 'Bank_Transfer', 'EUR', 2000.00, 200000.00, 10, 1, now(), 1270, 'feeling', now(), 1270, 'feeling');
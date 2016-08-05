

update payment_option_set_content set `ref_to_amt_exclusive` = 2000 where set_id in (1,2,5,6,7,9) and status = 1;


INSERT INTO `payment_option_set_content` (`id`, `set_id`, `card_code`, `ref_currency`, `ref_from_amt`, `ref_to_amt_exclusive`, `priority`, `status`, `create_on`, `create_at`, `create_by`, `modify_on`, `modify_at`, `modify_by`)
VALUES
    (null, 1, 'Bank_Transfer', 'GBP', 2000.00, 200000.00, 10, 1, now(), 1270, 'feeling', now(), 1270, 'feeling'),
    (null, 2, 'Bank_Transfer', 'EUR', 2000.00, 200000.00, 10, 1, now(), 1270, 'feeling', now(), 1270, 'feeling'),
    (null, 5, 'Bank_Transfer', 'EUR', 2000.00, 200000.00, 10, 1, now(), 1270, 'feeling', now(), 1270, 'feeling'),
    (null, 6, 'Bank_Transfer', 'EUR', 2000.00, 200000.00, 10, 1, now(), 1270, 'feeling', now(), 1270, 'feeling'),
    (null, 7, 'Bank_Transfer', 'EUR', 2000.00, 200000.00, 10, 1, now(), 1270, 'feeling', now(), 1270, 'feeling'),
    (null, 9, 'Bank_Transfer', 'EUR', 2000.00, 200000.00, 10, 1, now(), 1270, 'feeling', now(), 1270, 'feeling');


INSERT INTO `payment_option_card` (`id`, `code`, `payment_gateway_id`, `card_id`, `card_name`, `card_image`, `status`, `create_on`, `create_at`, `create_by`, `modify_on`, `modify_at`, `modify_by`)
VALUES
    (null, 'Bank_Transfer', 'm_bank_transfer', 'BankTransfer', 'Manual bank transfer', '90x45/m-bank-transfer-90x45.png', 1, now(), 1270, 'feeling', now(), 1270, 'feeling');
INSERT INTO `payment_option_set` (`id`, `name`, `status`, `create_on`, `create_at`, `create_by`, `modify_on`, `modify_at`, `modify_by`) VALUES (NULL, 'Bank Transfer Only', '1', now(), '2130706433', 'feeling', now(), '2130706433', 'feeling');

INSERT INTO `payment_option_set_content` (`id`, `set_id`, `card_code`, `ref_currency`, `ref_from_amt`, `ref_to_amt_exclusive`, `priority`, `status`, `create_on`, `create_at`, `create_by`, `modify_on`, `modify_at`, `modify_by`)
VALUES
(null, 13, 'Bank_Transfer', 'EUR', 2000.00, 200000.00, 10, 1, now(), 2130706433, 'feeling', now(), 2130706433, 'feeling');


update `payment_option` set `set_id` = 13 where `platform_id` in ('WEBIT','WEBBE','WEBPL','WEBNL');

update payment_option_set_content set `ref_from_amt` = 0.00 where card_code = 'Bank_Transfer';
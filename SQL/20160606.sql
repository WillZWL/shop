insert into payment_option_set
(name, status, create_on, create_at, create_by, modify_at, modify_by)
values ("NL EUR Set", 1, now(), '2130706433', 'oswald', '2130706433', 'oswald');

select id from payment_option_set where name="NL EUR Set" into @set_id;

insert into payment_option_set_content
(set_id, card_code, ref_currency, ref_from_amt, ref_to_amt_exclusive, priority, status, create_on, create_at, create_by, modify_at, modify_by)
VALUES
(@set_id, "mb_VSA", "EUR", 0, 20000, 1, 1, now(), '2130706433', 'oswald', '2130706433', 'oswald'),
(@set_id, "mb_VSD", "EUR", 0, 20000, 2, 1, now(), '2130706433', 'oswald', '2130706433', 'oswald'),
(@set_id, "mb_VSE", "EUR", 0, 20000, 3, 1, now(), '2130706433', 'oswald', '2130706433', 'oswald'),
(@set_id, "mb_MSC", "EUR", 0, 20000, 6, 1, now(), '2130706433', 'oswald', '2130706433', 'oswald'),
(@set_id, "mb_IDL", "EUR", 0, 20000, 9, 1, now(), '2130706433', 'oswald', '2130706433', 'oswald');

insert into payment_option
(platform_id, page, set_id, create_on, create_at, create_by, modify_at, modify_by)
values
("WEBNL", "checkout", @set_id, now(), '2130706433', 'oswald', '2130706433', 'oswald');
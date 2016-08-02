insert into payment_option_set(name, status, create_on, create_at, create_by, modify_at, modify_by)
values('GB - GC Set', 1, now(), '2130706433', 'oswald', '2130706433', 'oswald');
insert into payment_option_set(name, status, create_on, create_at, create_by, modify_at, modify_by)
values('FR - GC Set', 1, now(), '2130706433', 'oswald', '2130706433', 'oswald');
insert into payment_option_set(name, status, create_on, create_at, create_by, modify_at, modify_by)
values('ES - GC Set', 1, now(), '2130706433', 'oswald', '2130706433', 'oswald');


insert into payment_option_set_content (set_id, card_code, ref_currency, ref_from_amt, ref_to_amt_exclusive, priority, status, create_on, create_at, create_by, modify_at, modify_by)
values
(10, 'gc_VSA', 'GBP', 0, 8000, 1, 1, now(), '2130706433', 'oswald', '2130706433', 'oswald'),
(10, 'gc_MAC', 'GBP', 0, 8000, 3, 1, now(), '2130706433', 'oswald', '2130706433', 'oswald'),
(10, 'gc_VSE', 'GBP', 0, 8000, 5, 1, now(), '2130706433', 'oswald', '2130706433', 'oswald'),
(10, 'gc_VSD', 'GBP', 0, 8000, 7, 1, now(), '2130706433', 'oswald', '2130706433', 'oswald');

insert into payment_option_set_content (set_id, card_code, ref_currency, ref_from_amt, ref_to_amt_exclusive, priority, status, create_on, create_at, create_by, modify_at, modify_by)
values
(11, 'gc_VSA', 'GBP', 0, 8000, 1, 1, now(), '2130706433', 'oswald', '2130706433', 'oswald'),
(11, 'gc_MAC', 'GBP', 0, 8000, 3, 1, now(), '2130706433', 'oswald', '2130706433', 'oswald'),
(11, 'gc_VSE', 'GBP', 0, 8000, 5, 1, now(), '2130706433', 'oswald', '2130706433', 'oswald'),
(11, 'gc_VSD', 'GBP', 0, 8000, 7, 1, now(), '2130706433', 'oswald', '2130706433', 'oswald');

insert into payment_option_set_content (set_id, card_code, ref_currency, ref_from_amt, ref_to_amt_exclusive, priority, status, create_on, create_at, create_by, modify_at, modify_by)
values
(12, 'gc_VSA', 'GBP', 0, 8000, 1, 1, now(), '2130706433', 'oswald', '2130706433', 'oswald'),
(12, 'gc_MAC', 'GBP', 0, 8000, 3, 1, now(), '2130706433', 'oswald', '2130706433', 'oswald'),
(12, 'gc_VSE', 'GBP', 0, 8000, 5, 1, now(), '2130706433', 'oswald', '2130706433', 'oswald'),
(12, 'gc_VSD', 'GBP', 0, 8000, 7, 1, now(), '2130706433', 'oswald', '2130706433', 'oswald');


update payment_option set set_id=10
where platform_id='WEBGB' and page='checkout';
update payment_option set set_id=11
where platform_id='WEBFR' and page='checkout';
update payment_option set set_id=12
where platform_id='WEBES' and page='checkout';


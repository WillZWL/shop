insert into schedule_job(schedule_job_id, name, last_access_time, status, create_on, create_at, create_by, modify_at, modify_by)
values('MONEYBOOKERS_ORDERS_VERIFICATION', 'MB check pending order', now(), 1, now(), '2130706433', 'oswald', '2130706433', 'oswald');

ALTER TABLE `so_payment_status`
ADD INDEX `idx_create_on` (`create_on`) USING BTREE ;


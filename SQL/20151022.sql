insert into ftp_info
(name, server, username, password, port, pasv, create_on, create_at, create_by, modify_at, modify_by)
values('CV_EXCHANGE_RATE', 'chatandvision.com', 'forex', 'dIbDSRKJn7pkm1ppzttqvNUHbQWBi/I+1JOwIbf84BnSVhQGVXoTZQg5/miVLTW0JqaifaB1W236fFDBkI2JyA==', 21, 1, now(), '127.0.0.1','oswald', '127.0.0.1','oswald');

ALTER TABLE `exchange_rate_history`
ADD COLUMN `date`  datetime NULL AFTER `rate`;

ALTER TABLE `exchange_rate_history`
DROP INDEX `idx_currency_id` ,
ADD UNIQUE INDEX `idx_currency_id` (`from_currency_id`, `to_currency_id`, `date`) USING BTREE ;


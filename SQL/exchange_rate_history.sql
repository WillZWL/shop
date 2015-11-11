CREATE TABLE `exchange_rate_history` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `from_currency_id` CHAR(3) NOT NULL,
  `to_currency_id` CHAR(3) NOT NULL,
  `rate` DOUBLE(11,6) UNSIGNED NOT NULL DEFAULT '1.000000',
  `create_on` TIMESTAMP NOT NULL DEFAULT '0000-00-00 00:00:00',
  `create_at` INT(10) UNSIGNED NOT NULL DEFAULT '2130706433' COMMENT 'IP address, default 127.0.0.1',
  `create_by` VARCHAR(32) NOT NULL DEFAULT 'system',
  `modify_on` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `modify_at` INT(10) UNSIGNED NOT NULL DEFAULT '2130706433' COMMENT 'IP address',
  `modify_by` VARCHAR(32) NOT NULL DEFAULT 'system',
  PRIMARY KEY (`id`),
  UNIQUE KEY idx_currency_id (from_currency_id, to_currency_id)
) ENGINE=INNODB DEFAULT CHARSET=utf8;


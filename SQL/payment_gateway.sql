CREATE TABLE `payment_gateway` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `payment_gateway_id` varchar(20) NOT NULL,
  `name` varchar(255) NOT NULL,
  `ref_id` varchar(63) NOT NULL DEFAULT '',
  `status` tinyint(2) unsigned NOT NULL DEFAULT '0' COMMENT '0 = Inactive / 1 = Active',
  `create_on` TIMESTAMP NOT NULL DEFAULT '0000-00-00 00:00:00',
  `create_at` INT(10) UNSIGNED NOT NULL DEFAULT '2130706433' COMMENT 'IP address, default 127.0.0.1',
  `create_by` VARCHAR(32) NOT NULL DEFAULT 'system',
  `modify_on` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `modify_at` INT(10) UNSIGNED NOT NULL DEFAULT '2130706433' COMMENT 'IP address',
  `modify_by` VARCHAR(32) NOT NULL DEFAULT 'system',
  PRIMARY KEY (`id`),
  unique key idx_payment_gateway_id (`payment_gateway_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;
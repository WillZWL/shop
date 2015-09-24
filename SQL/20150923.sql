CREATE TABLE `payment_option` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `platform_id` varchar(7) COLLATE utf8_bin NOT NULL DEFAULT '',
  `page` varchar(32) COLLATE utf8_bin NOT NULL,
  `set_id` int(11) NOT NULL,
  `create_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `create_at` varchar(16) COLLATE utf8_bin NOT NULL,
  `create_by` varchar(32) COLLATE utf8_bin NOT NULL,
  `modify_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `modify_at` varchar(16) COLLATE utf8_bin NOT NULL,
  `modify_by` varchar(32) COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_platform_id` (`platform_id`,`page`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

CREATE TABLE `payment_option_set` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `set_id` int(11) NOT NULL,
  `name` varchar(64) NOT NULL,
  `status` smallint(1) NOT NULL,
  `create_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `create_at` varchar(16) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `create_by` varchar(32) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `modify_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `modify_at` varchar(16) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `modify_by` varchar(32) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_payment_set` (`set_id`) USING BTREE,
  KEY `idx_status` (`status`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `payment_option_set_content` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `set_id` int(11) NOT NULL,
  `card_code` varchar(20) NOT NULL,
  `ref_currency` varchar(3) NOT NULL,
  `ref_from_amt` double(10,2) NOT NULL,
  `ref_to_amt_exclusive` double(10,2) NOT NULL,
  `status` tinyint(2) unsigned NOT NULL DEFAULT '1' COMMENT '0 = Inactive / 1 = Active',
  `create_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `create_at` varchar(16) NOT NULL,
  `create_by` varchar(32) NOT NULL,
  `modify_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `modify_at` varchar(16) NOT NULL,
  `modify_by` varchar(32) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_set_id` (`set_id`) USING BTREE,
  KEY `idx_card_code` (`card_code`) USING BTREE,
  KEY `idx_currency` (`ref_currency`) USING BTREE,
  CONSTRAINT `fk_set_id` FOREIGN KEY (`set_id`) REFERENCES `payment_option_set` (`set_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `payment_option_card` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code` varchar(20) NOT NULL,
  `payment_gateway_id` varchar(20) NOT NULL DEFAULT 'paypal',
  `card_id` varchar(20) NOT NULL,
  `card_name` varchar(64) NOT NULL,
  `card_image` varchar(64) DEFAULT NULL,
  `status` tinyint(2) unsigned NOT NULL DEFAULT '1' COMMENT '0 = Inactive / 1 = Active',
  `create_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `create_at` varchar(16) NOT NULL,
  `create_by` varchar(32) NOT NULL,
  `modify_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `modify_at` varchar(16) NOT NULL,
  `modify_by` varchar(32) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*
ALTER TABLE `payment_gateway`
ADD INDEX `idx_payment_gateway_id_status` (`payment_gateway_id`, `status`) USING BTREE ;

ALTER TABLE `payment_option_card`
ADD UNIQUE INDEX `idx_code` (`code`) USING BTREE ,
ADD INDEX `idx_code_status` (`code`, `status`) USING BTREE ;

ALTER TABLE `payment_option_set_content`
ADD INDEX `idx_set_id_status` (`set_id`, `status`) ;

ALTER TABLE `payment_option`
ADD INDEX `idx_set_id` (`set_id`) USING BTREE ;

*/

insert into payment_option_card(code, payment_gateway_id, card_id, card_name, card_image, status, create_on, create_at, create_by, modify_at, modify_by)
select code, payment_gateway_id, card_id, card_name, card_image, status, now(), '127.0.0.1', 'oswald', '127.0.0.1', 'oswald' from pmgw_card where code like "paypal%" or code like "mb_%";

insert into payment_option_set
(id, set_id, name, status, create_on, create_at, create_by, modify_at, modify_by)
values
(1, 1, "GBP_Set", 1, now(), '127.0.0.1', 'oswald', '127.0.0.1', 'oswald'),
(2, 2, "EUR_Set", 1, now(), '127.0.0.1', 'oswald', '127.0.0.1', 'oswald');

insert into payment_option
(id, platform_id, page, set_id, create_on, create_at, create_by, modify_at, modify_by)
values
(1, "WEBGB", "checkout", 1, now(), '127.0.0.1', 'oswald', '127.0.0.1', 'oswald'),
(2, "WEBFR", "checkout", 2, now(), '127.0.0.1', 'oswald', '127.0.0.1', 'oswald'),
(3, "WEBES", "checkout", 2, now(), '127.0.0.1', 'oswald', '127.0.0.1', 'oswald'),
(4, "WEBIT", "checkout", 2, now(), '127.0.0.1', 'oswald', '127.0.0.1', 'oswald');

insert into payment_option_set_content
(id, set_id, card_code, ref_currency, ref_from_amt, ref_to_amt_exclusive, status, create_on, create_at, create_by, modify_at, modify_by)
VALUES
(1, 1, "paypal_VSA", "GBP", 0, 20000, 1, now(), '127.0.0.1', 'oswald', '127.0.0.1', 'oswald'),
(2, 1, "paypal_MSC", "GBP", 0, 20000, 1, now(), '127.0.0.1', 'oswald', '127.0.0.1', 'oswald'),
(3, 1, "paypal", "GBP", 0, 20000, 1, now(), '127.0.0.1', 'oswald', '127.0.0.1', 'oswald');


select platform_id, po.set_id, pos.name, posc.card_code, poc.payment_gateway_id, poc.card_id, poc.card_name, poc.card_image 
from payment_option po
inner join payment_option_set pos on pos.set_id=po.set_id and pos.status=1
inner join payment_option_set_content posc on posc.set_id=pos.set_id and posc.status=1
inner join payment_option_card poc on poc.code=posc.card_code and poc.status=1
inner join payment_gateway pg on pg.payment_gateway_id=poc.payment_gateway_id and pg.status=1
where po.platform_id='WEBGB' and po.page='checkout' group by poc.code;


update payment_option_card set card_image='90x45/btn_paypal.png' where code='paypal';

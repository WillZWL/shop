ALTER TABLE `product`
ADD COLUMN `accelerator_salesrpt_bd`  tinyint(2) NULL DEFAULT 0 AFTER `auto_restock`;

ALTER TABLE `product`
ADD COLUMN `product_warranty_type`  tinyint(2) NOT NULL DEFAULT 0 COMMENT '\'1 = Accessories, 2 = Waterproof, 3 = Main items, 4 = Action Camera, 5 = Drones, 6 = Refurbished, 7= No Warranty\'' AFTER `accelerator_salesrpt_bd`;

CREATE TABLE `platform_warranty` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `platform_id` varchar(10) DEFAULT '',
  `accessories` int(10) DEFAULT '0',
  `waterproof` int(10) DEFAULT '0',
  `main_items` int(10) DEFAULT '0',
  `action_camera` int(10) DEFAULT '0',
  `drones` int(10) DEFAULT '0',
  `refurbished` int(10) DEFAULT '0',
  `no_warranty` int(10) DEFAULT '0',
  `create_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `create_at` int(10) unsigned NOT NULL DEFAULT '2130706433' COMMENT 'IP address, default 127.0.0.1',
  `create_by` varchar(32) NOT NULL DEFAULT 'system',
  `modify_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `modify_at` int(10) unsigned NOT NULL DEFAULT '2130706433' COMMENT 'IP address',
  `modify_by` varchar(32) NOT NULL DEFAULT 'system',
  PRIMARY KEY (`id`),
  KEY `idx_platform_id` (`platform_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


INSERT INTO `platform_warranty` (`platform_id`, `accessories`, `waterproof`, `main_items`, `action_camera`, `drones`, `refurbished`, `no_warranty`, `create_on`)
VALUES
    ('WEBAU',6 ,12 ,12 ,12 ,12 ,6 ,0, now()),
    ('WEBBE',6 ,12 ,24 ,12 ,12 ,6 ,0, now()),
    ('WEBFR',6 ,12 ,24 ,12 ,12 ,6 ,0, now()),
    ('WEBGB',0 ,12 ,12 ,12 ,12 ,6 ,0, now()),
    ('WEBIT',6 ,12 ,24 ,12 ,12 ,6 ,0, now()),
    ('WEBNZ',6 ,12 ,12 ,12 ,12 ,6 ,0, now()),
    ('WEBPL',6 ,12 ,24 ,12 ,12 ,6 ,0, now()),
    ('WEBNL',6 ,12 ,24 ,12 ,12 ,6 ,0, now()),
    ('WEBES',6 ,12 ,24 ,12 ,12 ,6 ,0, now());
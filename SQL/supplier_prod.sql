CREATE TABLE `supplier_prod` (
  `id` int not null auto_increment,
  `supplier_id` int unsigned NOT NULL,
  `prod_sku` bigint unsigned NOT NULL,
  `currency_id` char(3) NOT NULL,
  `cost` double(15,2) NOT NULL,
  `lead_day` int(11) NOT NULL,
  `moq` int(4) NOT NULL,
  `order_default` tinyint(2) unsigned NOT NULL DEFAULT '1' COMMENT '0 = Not Default / 1 = Default',
  `region_default` bigint(20) unsigned DEFAULT NULL,
  `supplier_status` varchar(2) NOT NULL DEFAULT 'A' COMMENT 'A = Readily Available / O = Temp Out of Stock',
  `create_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `create_at` int(10) unsigned NOT NULL DEFAULT '2130706433' COMMENT 'IP address, default 127.0.0.1',
  `create_by` varchar(32) NOT NULL DEFAULT 'system',
  `modify_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `modify_at` int(10) unsigned NOT NULL DEFAULT '2130706433' COMMENT 'IP address',
  `modify_by` varchar(32) NOT NULL DEFAULT 'system',
  PRIMARY KEY (`id`),
  unique KEY idx_supplier_sku (`supplier_id`,`prod_sku`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


INSERT supplier_prod
(
  supplier_id,
  prod_sku,
  currency_id,
  cost,
  lead_day,
  moq,
  order_default,
  region_default,
  `supplier_status`
)

SELECT
    pp.supplier_id,
    p.sku,
    pp.currency_id,
    pp.cost,
    pp.lead_day,
    pp.moq,
    pp.order_default,
    pp.region_default,
    `supplier_status`
FROM supplier_prod_copy pp JOIN product p ON pp.prod_sku = p.sku_bak;

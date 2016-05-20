DROP TABLE IF EXISTS `auto_restock_log`;

CREATE TABLE `auto_restock_log` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `batch_id` int(11) NOT NULL DEFAULT '0',
  `sku` varchar(15) NOT NULL DEFAULT '',
  `vb_sku` varchar(15) NOT NULL DEFAULT '',
  `master_sku` varchar(15) NOT NULL DEFAULT '',
  `prod_name` varchar(255) NOT NULL DEFAULT '',
  `item_cost` decimal(15,2) NOT NULL DEFAULT '0.00',
  `website_quantity` int(11) NOT NULL DEFAULT '0',
  `display_quantity` int(11) NOT NULL DEFAULT '0',
  `supply_status` varchar(24) NOT NULL DEFAULT '',
  `create_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `create_at` varchar(16) NOT NULL,
  `create_by` varchar(32) NOT NULL,
  `modify_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `modify_at` varchar(16) NOT NULL,
  `modify_by` varchar(32) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

ALTER TABLE `site_config`
ADD COLUMN `api_implemented`  int(11) UNSIGNED NULL DEFAULT 0 COMMENT 'bit 0 = Google Shopping, bit 1 = Google Adwords' AFTER `domain_type`;

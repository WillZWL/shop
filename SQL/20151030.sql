ALTER TABLE `sku_mapping` ADD COLUMN `vb_sku` varchar(15) NOT NULL DEFAULT '' AFTER ext_sku;
ALTER TABLE `sku_mapping` ADD INDEX `idex_vb_sku` (`vb_sku`);
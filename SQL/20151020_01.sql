ALTER TABLE `supplier_prod`
ADD COLUMN `location`  varchar(4) NOT NULL DEFAULT '' AFTER `moq`,
ADD COLUMN `region`  varchar(4) NOT NULL DEFAULT '' AFTER `location`,
ADD COLUMN `surplus_qty`  int(5) NOT NULL DEFAULT 0 AFTER `region`;


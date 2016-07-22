ALTER TABLE `product`
ADD COLUMN `accelerator`  tinyint(2) NULL DEFAULT 0 AFTER `auto_restock`;

ALTER TABLE `brand`
ADD COLUMN `accelerator`  tinyint(2) NULL DEFAULT 0 AFTER `description`;

ALTER TABLE `brand`
ADD COLUMN `customer_code` varchar(20) DEFAULT '' AFTER `accelerator`;

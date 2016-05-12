ALTER TABLE `product`
ADD COLUMN `auto_restock`  tinyint(2) NOT NULL DEFAULT 0 AFTER `shipment_restricted_type`;
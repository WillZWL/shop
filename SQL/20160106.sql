ALTER TABLE `so`
ADD COLUMN `vat`  double(8,2) NOT NULL DEFAULT 0 AFTER `vat_percent`;


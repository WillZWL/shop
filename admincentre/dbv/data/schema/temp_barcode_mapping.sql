CREATE TABLE `temp_barcode_mapping` (
  `se_sku` smallint(5) NOT NULL,
  `master_sku` varchar(15) NOT NULL,
  `ean` varchar(32) DEFAULT NULL,
  `ean_us` varchar(32) DEFAULT NULL,
  `mpn` varchar(32) DEFAULT NULL,
  `upc` varchar(32) DEFAULT NULL,
  PRIMARY KEY (`se_sku`,`master_sku`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8
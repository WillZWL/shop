ALTER table platform_biz_var
ADD column `tax_theresholds` double(10,2) DEFAULT '0.00' AFTER `default_shiptype`;
ALTER TABLE `so`
ADD COLUMN `declared_value` double(11,2) NOT NULL DEFAULT '0.00' AFTER `delivery_charge`;
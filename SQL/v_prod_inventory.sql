CREATE ALGORITHM=UNDEFINED DEFINER=`atomv2`@`127.0.0.1` SQL SECURITY DEFINER VIEW `v_prod_inventory`
AS SELECT
   id, `inventory`.`prod_sku` AS `prod_sku`,sum(`inventory`.`inventory`) AS `inventory`
FROM `inventory` group by `inventory`.`prod_sku`;
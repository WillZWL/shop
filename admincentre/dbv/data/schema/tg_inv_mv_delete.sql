CREATE DEFINER=`atomvb_dev`@`localhost` TRIGGER `tg_inv_mv_delete` AFTER DELETE ON `inv_movement` FOR EACH ROW BEGIN 
	SELECT from_inv_dir, from_git_dir, to_inv_dir, to_git_dir INTO @ofi_dir, @ofg_dir, @oti_dir, @otg_dir FROM inv_status WHERE status = OLD.status AND type = OLD.type;
	IF !(@ofi_dir = 0 AND @ofg_dir = 0) THEN
		UPDATE inventory SET inventory = inventory + OLD.qty * -@ofi_dir, git = git + OLD.qty * -@ofg_dir, modify_on = NOW() WHERE prod_sku = OLD.sku AND warehouse_id = OLD.from_location;
	END IF;

	IF !(@oti_dir = 0 AND @otg_dir = 0) THEN
		UPDATE inventory SET inventory = inventory + OLD.qty * -@oti_dir, git = git + OLD.qty * -@otg_dir, modify_on = NOW() WHERE prod_sku = OLD.sku AND warehouse_id = OLD.to_location;
	END IF;
END
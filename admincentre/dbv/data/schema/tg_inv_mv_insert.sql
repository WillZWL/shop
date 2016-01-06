CREATE DEFINER=`atomvb_dev`@`localhost` TRIGGER `tg_inv_mv_insert` AFTER INSERT ON `inv_movement` FOR EACH ROW BEGIN
	SELECT from_inv_dir, from_git_dir, to_inv_dir, to_git_dir INTO @fi_dir, @fg_dir, @ti_dir, @tg_dir FROM inv_status WHERE status = NEW.status AND type = NEW.type;
	IF !(@fi_dir = 0 AND @fg_dir = 0) THEN
		UPDATE inventory SET inventory = inventory + NEW.qty * @fi_dir, git = git + NEW.qty * @fg_dir, modify_on = NOW() WHERE prod_sku = NEW.sku AND warehouse_id = NEW.from_location;
		IF (SELECT ROW_COUNT()) < 1 THEN
			INSERT INTO inventory (warehouse_id, prod_sku, inventory, git, create_on, create_at, create_by, modify_on, modify_at, modify_by)
						VALUES (NEW.to_location, NEW.sku, NEW.qty * @fi_dir, NEW.qty * @fg_dir, NOW(), '127.0.0.1', 'system', NOW(), '127.0.0.1', 'system');
		END IF;
	END IF;

	IF !(@ti_dir = 0 AND @tg_dir = 0) THEN
		UPDATE inventory SET inventory = inventory + NEW.qty * @ti_dir, git = git + NEW.qty * @tg_dir, modify_on = NOW() WHERE prod_sku = NEW.sku AND warehouse_id = NEW.to_location;
		IF (SELECT ROW_COUNT()) < 1 THEN
			INSERT INTO inventory (warehouse_id, prod_sku, inventory, git, create_on, create_at, create_by, modify_on, modify_at, modify_by)
						VALUES (NEW.to_location, NEW.sku, NEW.qty * @ti_dir, NEW.qty * @tg_dir, NOW(), '127.0.0.1', 'system', NOW(), '127.0.0.1', 'system');
		END IF;
	END IF;
END
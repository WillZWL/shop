CREATE DEFINER=`atomvb_dev`@`localhost` TRIGGER `tg_inv_mv_update` AFTER UPDATE ON `inv_movement` FOR EACH ROW BEGIN 
	SELECT from_inv_dir, from_git_dir, to_inv_dir, to_git_dir INTO @ofi_dir, @ofg_dir, @oti_dir, @otg_dir FROM inv_status WHERE status = OLD.status AND type = OLD.type;
	SELECT from_inv_dir, from_git_dir, to_inv_dir, to_git_dir INTO @nfi_dir, @nfg_dir, @nti_dir, @ntg_dir FROM inv_status WHERE status = NEW.status AND type = NEW.type;
	IF !(@ofi_dir = 0 AND @ofg_dir = 0) THEN
		UPDATE inventory SET inventory = inventory + OLD.qty * -@ofi_dir, git = git + OLD.qty * -@ofg_dir, modify_on = NOW() WHERE prod_sku = NEW.sku AND warehouse_id = OLD.from_location;
	END IF;

	IF !(@oti_dir = 0 AND @otg_dir = 0) THEN
		UPDATE inventory SET inventory = inventory + OLD.qty * -@oti_dir, git = git + OLD.qty * -@otg_dir, modify_on = NOW() WHERE prod_sku = NEW.sku AND warehouse_id = OLD.to_location;
	END IF;

	IF !(@nfi_dir = 0 AND @nfg_dir = 0) THEN
		UPDATE inventory SET inventory = inventory + NEW.qty * @nfi_dir, git = git + NEW.qty * @nfg_dir, modify_on = NOW() WHERE prod_sku = NEW.sku AND warehouse_id = NEW.from_location;
	END IF;

	IF !(@nti_dir = 0 AND @ntg_dir = 0) THEN
		UPDATE inventory SET inventory = inventory + NEW.qty * @nti_dir, git = git + NEW.qty * @ntg_dir, modify_on = NOW() WHERE prod_sku = NEW.sku AND warehouse_id = NEW.to_location;
		IF ((SELECT ROW_COUNT()) < 1 AND NEW.qty != 0)THEN
			INSERT INTO inventory (warehouse_id, prod_sku, inventory, git, create_on, create_at, create_by, modify_on, modify_at, modify_by)
						VALUES (NEW.to_location, NEW.sku, NEW.qty * @nti_dir, NEW.qty * @ntg_dir, NOW(), '127.0.0.1', 'system', NOW(), '127.0.0.1', 'system');
		END IF;
	END IF;
END
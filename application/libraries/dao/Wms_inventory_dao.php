<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once 'Base_dao.php';

class Wms_inventory_dao extends Base_dao {
	private $table_name="wms_inventory";
	private $vo_class_name="Wms_inventory_vo";
	private $seq_name="";
	private $seq_mapping_field="";

	public function __construct(){
		parent::__construct();
	}

	public function get_vo_classname(){
		return $this->vo_class_name;
	}

	public function get_table_name(){
		return $this->table_name;
	}

	public function get_seq_name(){
		return $this->seq_name;
	}

	public function get_seq_mapping_field(){
		return $this->seq_mapping_field;
	}

	public function empty_table()
	{
		return $this->db->empty_table($this->get_table_name());
	}

	public function renew_inventory($inv = array())
	{
		if (sizeof($inv) > 0)
		{
			$data = array();
			foreach ($inv as $warehouse_id=>$master_sku_list)
			{
				foreach($master_sku_list as $master_sku=>$qty)
				{
					$data[] = "('" . $warehouse_id . "','" . $master_sku . "'," . $qty['inventory'] . "," . $qty['git'] . ")";
				}
			}
			$sql = 'insert into ' . $this->get_table_name() . ' (warehouse_id, master_sku, inventory, git) values ' . implode(',', $data) . ';';

			$this->db->trans_start();
			if ($this->db->query($sql))
			{
				$this->db->trans_complete();
				return TRUE;
			}
			else
			{
				$this->db->trans_rollback();
				return FALSE;
			}
		}

		return TRUE;
	}

	public function get_inventory_list($where=array())
	{
		if($where["sku"] == "")
		{
			return FALSE;
		}

		$sql = "select w.warehouse_id, coalesce(inv.inventory, 0) as inventory, coalesce(inv.git, 0) as git from wms_warehouse w left join
				(
					SELECT wms.warehouse_id, SUM(wms.inventory) as inventory, SUM(wms.git) as git
					FROM wms_inventory wms INNER JOIN sku_mapping map ON wms.master_sku = map.ext_sku AND map.ext_sys = 'WMS' AND map.status = 1
					WHERE map.sku = ?
					GROUP BY warehouse_id
				) inv on w.warehouse_id = inv.warehouse_id
				ORDER BY w.warehouse_id
				";

		$this->include_vo();

		$rs =array();
		if($query = $this->db->query($sql, array($where["sku"])))
		{
			foreach($query->result($$this->get_vo_classname()) as $obj)
			{
				$rs[] = $obj;
			}
			return $rs;
		}
		return FALSE;
	}
}

/* End of file wms_inventory_dao.php */
/* Location: ./system/application/libraries/dao/Wms_inventory_dao.php */
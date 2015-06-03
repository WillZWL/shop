<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once 'Base_dao.php';

class Delivery_time_dao extends Base_dao
{
	private $table_name="delivery_time";
	private $vo_class_name="Delivery_time_vo";
	private $seq_name="";
	private $seq_mapping_field="";

	public function __construct()
	{
		parent::__construct();
	}

	public function get_vo_classname()
	{
		return $this->vo_class_name;
	}

	public function get_table_name()
	{
		return $this->table_name;
	}

	public function get_seq_name()
	{
		return $this->seq_name;
	}

	public function get_seq_mapping_field()
	{
		return $this->seq_mapping_field;
	}

	public function get_deliverytime_list($where=array())
	{
		$dto = "delivery_time_list_dto";
		$this->include_dto($dto);

		$this->db->select("
							dt.id, dt.scenarioid, dt.country_id, dt.ship_min_day, dt.ship_max_day, dt.del_min_day, dt.del_max_day, dt.margin, dt.status AS dt_status,
							dt.create_on, dt.create_at, dt.create_by, dt.modify_on, dt.modify_at, dt.modify_by,
							lookds.name, lookds.description, lookds.status AS lookupscenario_status
						", false);
		$this->db->from("delivery_time AS dt");
		$this->db->join("lookup_delivery_scenario AS lookds", "dt.scenarioid = lookds.id", "INNER");
		$this->db->where($where);
		$this->db->where("dt.status = 1 AND lookds.status = 1");
		$this->db->order_by("lookds.id ASC");

		$rs = array();
		if ($query = $this->db->get())
		{
			foreach ($query->result($dto) as $obj)
			{
				$rs[] = $obj;
			}
			return (object) $rs;
		}

		return FALSE;
	}

	public function get_deliverytime_obj($ctry_id, $scenarioid)
	{
		$dto = "delivery_time_list_dto";
		$this->include_dto($dto);

		$this->db->select("
							dt.id, dt.scenarioid, dt.country_id, dt.ship_min_day, dt.ship_max_day, dt.del_min_day, dt.del_max_day, dt.margin, dt.status AS dt_status,
							dt.create_on, dt.create_at, dt.create_by, dt.modify_on, dt.modify_at, dt.modify_by,
							lookds.name, lookds.description, lookds.status AS lookupscenario_status
						", false);
		$this->db->from("delivery_time AS dt");
		$this->db->join("lookup_delivery_scenario AS lookds", "dt.scenarioid = lookds.id", "INNER");
		$this->db->where("dt.status = 1 AND lookds.status = 1");
		$this->db->where("dt.country_id", $ctry_id);
		$this->db->where("dt.scenarioid", $scenarioid);
		$this->db->order_by("lookds.id ASC");
		$this->db->limit(1);

		if ($query = $this->db->get())
		{
			foreach ($query->result($dto) as $obj)
			{
				return (object) $obj;
			}
		}

		return FALSE;
	}

	public function get_delivery_scenario_list()
	{
		$this->db->from("lookup_delivery_scenario");
		$this->db->where("status = 1");
		$rs = array();

		if ($query = $this->db->get())
		{
			foreach ($query->result() as $row)
			{
				$rs[] = $row;
			}
			return (object) $rs;
		}

		return FALSE;

	}


	public function bulk_update_delivery_scenario_by_platform($platform_id, $scenarioid, $sku_list)
	{
		$ts = date("Y-m-d H:i:s");
		$ip = $_SERVER["REMOTE_ADDR"]?$_SERVER["REMOTE_ADDR"]:"127.0.0.1";
		$id = empty($_SESSION["user"]["id"])?"system":$_SESSION["user"]["id"];

		$this->db->trans_start();
		$where["platform_id"] = $platform_id;
		$where["sku IN ($sku_list)"] = null;
		$this->db->where($where);
		$this->db->update('price', array("delivery_scenarioid"=>$scenarioid, "modify_on"=>$ts, "modify_at"=>$ip, "modify_by"=>$id));
		$this->db->trans_complete();

		if ($this->db->trans_status() !== FALSE)
		{
			$affected = $this->db->affected_rows();
			return $affected;
		}
		else
		{
		    return FALSE;
		}

		return FALSE;
	}

}

/* End of file delivery_time_dao.php */
/* Location: ./system/application/libraries/dao/Delivery_time_dao.php */
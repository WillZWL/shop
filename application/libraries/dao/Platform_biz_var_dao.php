<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once 'Base_dao.php';

class Platform_biz_var_dao extends Base_dao
{
	private $table_name = "platform_biz_var";
	private $vo_class_name = "Platform_biz_var_vo";
	private $seq_name = "";
	private $seq_mapping_field = "";

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

	public function get_list_w_platform_name($where = array(), $option = array(), $classname = "Platform_biz_var_w_platform_name_dto")
	{
		$select_str = "pbv.*, s.name AS platform_name";
		$this->db->from('platform_biz_var AS pbv');
		$this->db->join('selling_platform AS s', 'pbv.selling_platform_id = s.id', 'INNER');
		$this->include_dto($classname);
		return $this->common_get_list($where, $option, $classname, $select_str);
	}

	public function get_pricing_tool_platform_list($sku, $platform_type, $classname = "Platform_biz_var_w_platform_name_dto")
	{
		$sql = "SELECT
					pbv.*, s.name AS platform_name, c.name AS platform_country
				FROM platform_biz_var AS pbv
				JOIN selling_platform AS s
					ON pbv.selling_platform_id = s.id
				JOIN country AS c
					ON c.id = pbv.platform_country_id
				LEFT JOIN price AS pr
					ON pr.platform_id = pbv.selling_platform_id AND pr.platform_id = s.id AND pr.sku = ?
				WHERE type = ? AND s.status = 1
				ORDER BY pr.listing_status = 'L' DESC, s.id ASC";

		if($result = $this->db->query($sql, array($sku, $platform_type)))
		{
			$this->include_dto($classname);
			$result_arr = array();

			foreach ($result->result("object", $classname) as $obj)
			{
				$result_arr[] = $obj;
			}
			return $result_arr;
		}

		return FALSE;
	}

	public function get_list_w_country_name($where = array(), $option = array(), $classname = "Platform_biz_var_w_platform_name_dto")
	{
		$select_str = "pbv.*, c.name AS platform_country";
		$this->db->from('platform_biz_var AS pbv');
		$this->db->join('selling_platform AS s', 'pbv.selling_platform_id = s.id', 'INNER');
		$this->db->join('country AS c', 'c.id = pbv.platform_country_id', 'INNER');
		$this->include_dto($classname);
		return $this->common_get_list($where, $option, $classname, $select_str);
	}

	public function get_unique_dest_country_list()
	{
		$sql = "SELECT DISTINCT(pbv.dest_country) AS country_id, c.name AS country_name, pbv.platform_currency_id
				FROM platform_biz_var pbv
				INNER JOIN country c
					ON c.id = pbv.dest_country
				";
		if($query = $this->db->query($sql))
		{
			foreach($query->result() as $row)
			{
				$res[] = array("country_id"=>$row->country_id, "country_name"=>$row->country_name, "currency_id"=>$row->platform_currency_id);
			}
			return $res;
		}
		return FALSE;
	}

	public function get_dest_country_w_delivery_type_list()
	{
		$sql = "SELECT sp.type, sp.id, pbv.dest_country country_id, c.name as country_name, pbv.platform_currency_id
				FROM selling_platform sp
				JOIN platform_biz_var pbv
					ON sp.id = pbv.selling_platform_id
				JOIN country c
					ON c.id = pbv.dest_country
				";
		if($query = $this->db->query($sql))
		{
			foreach($query->result() as $row)
			{
				$res[$row->type][] = array("country_id"=>$row->country_id, "country_name"=>$row->country_name, "currency_id"=>$row->platform_currency_id);
			}
			return $res;
		}
		return FALSE;
	}

	public function get_free_delivery_limit($platform_id)
	{
		$sql = "SELECT free_delivery_limit
				FROM platform_biz_var
				WHERE selling_platform_id = ?";

		if($query = $this->db->query($sql, array($platform_id)))
		{
			return $query->row()->free_delivery_limit;
		}
	}

}

/* End of file platform_biz_var_dao.php */
/* Location: ./system/application/libraries/dao/Platform_biz_var_dao.php */
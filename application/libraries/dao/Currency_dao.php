<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once 'Base_dao.php';

class Currency_dao extends Base_dao
{
	private $table_name = "currency";
	private $vo_class_name = "Currency_vo";
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

	public function get_by_platform($platform)
	{
		$this->db->from('currency c');
		$this->db->join('platform_biz_var pbv',"pbv.platform_currency_id = c.id AND pbv.selling_platform_id = '$platform'",'INNER');
		$this->db->select('c.*');
		if($query = $this->db->get())
		{
			$this->include_vo();
			foreach($query->result("object",$this->get_vo_class_name()) as $obj)
			{
				$tmp = $obj;
			}

			return $tmp;
		}
		return FALSE;
	}

	public function get_sign($platform = "")
	{
		$sql = "SELECT c.sign
				FROM currency c
				JOIN platform_biz_var p
					ON p.platform_currency_id = c.id
					AND p.selling_platform_id = ?
				LIMIT 1";

		if($query = $this->db->query($sql, $platform))
		{
			return $query->row()->sign;
		}
		return FALSE;

	}

	public function get_round_up($currency_id)
	{
		$this->db->select('round_up');
		if ($query = $this->db->get_where($this->get_table_name(), array("id"=>$currency_id), 1))
		{
			return $query->row()->round_up;
		}
		else
		{
			return FALSE;
		}
	}

	public function get_active_currency_list()
	{
		$sql = "SELECT c.*
				FROM selling_platform sp
				JOIN platform_biz_var pbv
					ON pbv.selling_platform_id = sp.id
				JOIN currency c
					ON c.id = pbv.platform_currency_id
				WHERE sp.status = 1
				GROUP BY c.id, c.name, c.description, c.sign, c.round_up, c.sign_pos, c.dec_place, c.dec_point, c.thousands_sep";

		$result = $this->db->query($sql);

		$this->include_vo();

		$result_arr = array();
		$classname = $this->get_vo_classname();

		foreach ($result->result($classname) as $obj)
		{
			array_push($result_arr, $obj);
		}

		return $result_arr;
	}

}

/* End of file currency_dao.php */
/* Location: ./system/application/libraries/dao/Currency_dao.php */
<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once 'Base_dao.php';

class Custom_classification_dao extends Base_dao {
	private $table_name="custom_classification";
	private $vo_class_name="Custom_classification_vo";
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

	public function get_custom_class_list_w_platform_id($platform_id = "WEBHK")
	{
		$sql = "SELECT *
				FROM custom_classification cc
				JOIN platform_biz_var pbv
					ON cc.country_id = pbv.platform_country_id
				WHERE pbv.selling_platform_id = ?
				";

		if($result = $this->db->query($sql, array($platform_id)))
		{
			$this->include_vo();

			$result_arr = array();
			$classname = $this->get_vo_classname();

			foreach ($result->result("object", $classname) as $obj)
			{
				array_push($result_arr, $obj);
			}
			return $result_arr;
		}
		return FALSE;

	}

	public function get_option($where)
	{
		$sql = "SELECT distinct country_id, code, duty_pcent, description
				FROM custom_classification cc";

		$query = $this->db->query($sql);
		if (!$query)
		{
			return FALSE;
		}
		$array = $query->result_array();
		return $array;

	}

}

/* End of file custom_classification_dao.php */
/* Location: ./system/application/libraries/dao/Custom_classification_dao.php */
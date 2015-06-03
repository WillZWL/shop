<?php

include_once 'Base_dao.php';

class Product_identifier_dao extends Base_dao
{
	private $table_name="product_identifier";
	private $vo_classname="Product_identifier_vo";
	private $seq_name="";
	private $seq_mapping_field="";

	public function __construct()
	{
		parent::__construct();
	}

	public function get_table_name()
	{
		return $this->table_name;
	}

	public function get_vo_classname()
	{
		return $this->vo_classname;
	}

	public function get_seq_name()
	{
		return $this->seq_name;
	}

	public function get_seq_mapping_field()
	{
		return $this->seq_mapping_field;
	}


	public function get_product_identifier_list_grouped_by_country($where=array())
	{
		$this->db->from('product_identifier pi');

		if ($where)
		{
			$this->db->where($where);
		}

		$rs = array();

		if ($query = $this->db->get())
		{
			$this->include_vo($this->get_vo_classname());
			foreach ($query->result($this->get_vo_classname()) as $obj)
			{
				$rs[$obj->get_country_id()] = array("ean"=>$obj->get_ean(), "mpn"=>$obj->get_mpn(), "upc"=>$obj->get_upc(), "status"=>$obj->get_status());
			}
			return $rs;
		}

		return FALSE;
	}
}

/* End of file product_identifier_dao.php */
/* Location: ./app/libraries/dao/Product_identifier_dao.php */
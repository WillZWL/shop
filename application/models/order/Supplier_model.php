<?php
class Supplier_model extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
		$this->load->library('service/supplier_service');
		$this->load->library('service/currency_service');
	}

	public function get_list($dao, $where=array(), $option=array())
	{
		$dao = "get_".$dao;
		return $this->supplier_service->$dao()->get_list($where, $option);
	}

	public function get_service_list($service, $where=array(), $option=array())
	{
		$service = $service."_service";
		return $this->$service->get_list($where, $option);
	}

	public function get_num_rows($dao, $where=array())
	{
		$dao = "get_".$dao;
		return $this->supplier_service->$dao()->get_num_rows($where);
	}

	public function get_supplier_list($where=array(), $option=array())
	{
		return $this->supplier_service->get_dao()->get_list_w_name($where, $option);
	}

	public function get_supplier_list_total($where=array())
	{
		return $this->supplier_service->get_dao()->get_list_w_name($where, array("num_rows"=>1));
	}

	public function get($dao, $where=array())
	{
		$dao = "get_".$dao;
		return $this->supplier_service->$dao()->get($where);
	}

	public function update($dao, $obj)
	{
		$dao = "get_".$dao;
		return $this->supplier_service->$dao()->update($obj);
	}

	public function add($dao, $obj)
	{
		$dao = "get_".$dao;
		return $this->supplier_service->$dao()->insert($obj);
	}

	public function include_vo($dao)
	{
		$dao = "get_".$dao;
		return $this->supplier_service->$dao()->include_vo();
	}
}

/* End of file supplier_model.php */
/* Location: ./system/application/models/supplier_model.php */
?>
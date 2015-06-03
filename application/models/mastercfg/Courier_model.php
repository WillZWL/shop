<?php
class Courier_model extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
		$this->load->library('service/courier_service');
		$this->load->library('service/currency_service');
	}

	public function get_courier_list($where=array(), $option=array())
	{
		$data["courierlist"] = $this->courier_service->get_list($where, $option);
		$data["total"] = $this->courier_service->get_num_rows($where);
		return $data;
	}

	public function get_courier($where=array())
	{
		return $this->courier_service->get($where);
	}

	public function update_courier($obj)
	{
		return $this->courier_service->update($obj);
	}

	public function include_courier_vo()
	{
		return $this->courier_service->include_vo();
	}

	public function add_courier(Base_vo $obj)
	{
		return $this->courier_service->insert($obj);
	}

	public function get_currency_list($where=array())
	{
		return $this->currency_service->get_list($where, $option);
	}
}

/* End of file courier_model.php */
/* Location: ./system/application/models/courier_model.php */
?>
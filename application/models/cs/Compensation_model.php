<?php
class Compensation_model extends CI_Model
{

	public function __construct()
	{
		parent::__construct();
		$this->load->library('service/so_service');
		$this->load->library('service/so_compensation_service');
		$this->load->library('service/so_compensation_history_service');
		$this->load->library('service/compensation_reason_service');
	}

	public function get_orders_eligible_for_compensation($where=array(), $option=array())
	{
		return array("list"=>$this->get_compensation_order_list($where, $option),
					 "total"=>$this->get_compensation_order_num_rows($where, $option));
	}

	public function get_compensation_order_list($where = array(), $option = array())
	{
		$option["array_list"] = 1;
		return $this->so_compensation_service->get_orders_eligible_for_compensation($where, $option);
	}

	public function get_compensation_order_num_rows($where = array(), $option = array())
	{
		$option["num_rows"] = 1;
		return $total = $this->so_compensation_service->get_orders_eligible_for_compensation($where, $option);
	}

	public function get_so($where = array())
	{
		return $this->so_service->get_dao()->get($where);
	}

	public function update_so($obj)
	{
		return $this->so_service->get_dao()->update($obj);
	}

	public function get_item_list($where=array())
	{
		return $this->so_service->get_soid_dao()->get_list_w_prodname($where, array("sortby"=>"line_no ASC"));
	}

	public function get_reason($where = array())
	{
		return $this->so_service->get_sohr_dao()->get($where);
	}

	public function insert_reason($obj)
	{
		return $this->so_service->get_sohr_dao()->insert($obj);
	}

	public function get_history($where = array())
	{
		return $this->so_compensation_history_service->get($where);
	}

	public function get_history_list($where = array(), $option = array())
	{
		return $this->so_compensation_history_service->get_list($where, $option);
	}

	public function insert_history($obj)
	{
		return $this->so_compensation_history_service->insert($obj);
	}

	public function update_history($obj)
	{
		return $this->so_compensation_history_service->update($obj);
	}

	public function get_num_rows_compensation($where = array())
	{
		return $this->so_compensation_service->get_num_rows($where);
	}

	public function get_compensation($where = array())
	{
		return $this->so_compensation_service->get($where);
	}

	public function get_compensation_list($where = array(), $option = array())
	{
		return $this->so_compensation_service->get($where, $option);
	}

	public function insert_compensation($obj)
	{
		return $this->so_compensation_service->insert($obj);
	}

	public function update_compensation($obj)
	{
		return $this->so_compensation_service->update($obj);
	}

	public function get_request_compensation_so($where = array(), $option = array())
	{
		return array("list"=>$this->get_request_compensation_so_list($where, $option),
			 "total"=>$this->get_request_compensation_so_num_rows($where, $option));
	}

	public function get_request_compensation_so_list($where = array(), $option = array())
	{
		$option['array_list'] = 1;
		return $this->so_compensation_service->get_compensation_so_list($where, $option);
	}

	public function get_request_compensation_so_num_rows($where = array(), $option = array())
	{
		$option['num_rows'] = 1;
		return $this->so_compensation_service->get_compensation_so_list($where, $option);
	}

	public function get_order_compensated_item($where = array(), $option = array())
	{
		return $this->so_compensation_service->get_order_compensated_item($where, $option);
	}

	public function _trans_start()
	{
		$this->so_compensation_service->get_dao()->trans_start();
	}

	public function _trans_complete()
	{
		$this->so_compensation_service->get_dao()->trans_complete();
	}

	public function get_notification_email($compensation_id)
	{
		return $this->so_compensation_history_service->get_notification_email($compensation_id);
	}

	public function get_compensation_reason_list()
	{
		return $this->compensation_reason_service->get_list();
	}

	public function get_compensation_reason($where = array())
	{
		return $this->compensation_reason_service->get($where);
	}
}
?>
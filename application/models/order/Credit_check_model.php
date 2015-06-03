<?php
class Credit_check_model extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
		$this->load->library('service/so_service');
		$this->load->library('service/refund_service');
		$this->load->library('service/client_service');
		$this->load->library('service/quick_search_service');
	}

	public function get_list($dao="dao", $where=array(), $option=array())
	{
		$dao = "get_".$dao;
		return $this->so_service->$dao()->get_list($where, $option);
	}

	public function get_num_rows($dao="dao", $where=array())
	{
		$dao = "get_".$dao;
		return $this->so_service->$dao()->get_num_rows($where);
	}

	public function get($dao="dao", $where=array())
	{
		$dao = "get_".$dao;
		return $this->so_service->$dao()->get($where);
	}

	public function update($dao="dao", $obj)
	{
		$dao = "get_".$dao;
		return $this->so_service->$dao()->update($obj);
	}

	public function add($dao="dao", $obj)
	{
		$dao = "get_".$dao;
		return $this->so_service->$dao()->insert($obj);
	}

	public function include_vo($dao)
	{
		$dao = "get_".$dao;
		return $this->so_service->$dao()->include_vo();
	}

	public function _trans_start($dao)
	{
		$this->so_service->$dao()->trans_start();
	}

	public function _trans_complete($dao)
	{
		$this->so_service->$dao()->trans_complete();
	}

	public function add_refund_item($obj)
	{
		$this->refund_service->get_ritem_dao()->insert($obj);
	}

	public function add_refund($obj)
	{
		$this->refund_service->get_dao()->insert($obj);
	}

	public function add_refund_history($obj)
	{
		$this->refund_service->get_history_dao()->insert($obj);
	}

	public function create_refund($so_no="")
	{
		return $so_no==""?FALSE:$this->refund_service->create_refund($so_no);
	}

	public function create_refund_from_communication_center($so_no="", $refund_parameter=array())
	{
		if (($so_no == '') || (empty($refund_parameter)))
		{
			return FALSE;
		}
		else
		{
			return $this->refund_service->create_refund_from_communication_center($so_no, $refund_parameter);
		}
	}

	public function get_event_dto()
	{
		return $this->refund_service->get_event_dto();
	}

	public function get_client($where = array())
	{
		return $this->client_service->get_dao()->get($where);
	}

	public function get_credit_check_list($where=array(),$option=array(),$type="")
	{
		return $this->so_service->get_credit_check_list($where,$option,$type);
	}

	public function get_credit_check_list_count($where=array(),$option=array(),$type="")
	{
		return $this->so_service->get_dao()->get_credit_check_list($where, $option,$type);
	}

	public function fire_cs_request($so_no="", $reason="")
	{
		$this->so_service->fire_cs_request($so_no,$reason);
	}

	public function get_pmgw_card_list($where=array(), $option=array())
	{
		return $this->quick_search_service->get_pmgw_card_dao()->get_list($where=array(), $option=array());
	}

	public function get_order_note($where=array(), $option=array())
	{
		return $this->quick_search_service->get_order_note($where, $option);
	}

	public function add_order_note($so_no, $notes)
	{
		$obj = $this->quick_search_service->get_order_notes_dao()->get();
		$obj->set_so_no($so_no);
		$obj->set_type('O');
		$obj->set_note($notes);
		return $this->quick_search_service->get_order_notes_dao()->insert($obj);
	}
}

/* End of file credit_check_model.php */
/* Location: ./system/application/models/credit_check_model.php */
?>
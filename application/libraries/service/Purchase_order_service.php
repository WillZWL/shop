<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once "Base_service.php";

class Purchase_order_service extends Base_service
{
	private $pm_dao;

	public function __construct()
	{
		parent::__construct();
		include_once(APPPATH."libraries/dao/Purchase_order_dao.php");
		$this->set_dao(new Purchase_order_dao());
		include_once (APPPATH."libraries/dao/Po_message_dao.php");
		$this->set_pm_dao(new Po_message_dao());
		include_once(APPPATH."libraries/service/Event_service.php");
		$this->set_event_srv(new Event_service());
	}


	public function check_status($where=array(),$update=1)
	{
		if(empty($where))
		{
			return FALSE;
		}
		else
		{
			$obj = $this->get_dao()->get(array("po_number"=>$where["po_number"]));
			if($obj === FALSE)
			{
			echo $this->db->_error_message();
				return FALSE;
			}
			include_once "po_item_service.php";
			$po_item_svc = New Po_item_service();
			$poiobj = $po_item_svc->get_dao()->get($where);
			if($poiobj === FALSE)
			{
				echo $this->db->_error_message();
				return FALSE;
			}

			if($poiobj->get_order_qty() > $poiobj->get_shipped_qty())
			{
				if($obj->get_status() == 'FS')
				{
					$obj->set_status('PS');
					$ret = $this->get_dao()->update($obj);
					if($ret === FALSE)
					{
						echo $this->db->_error_message();
						return FALSE;
					}
				}
			}

			$status = $this->get_dao()->get_complete_status($where["po_number"]);
			if($obj->get_status() == 'FS' && $status["completed"] == $status["total"])
			{
				if($update)
				{
					$obj->set_status('C');
					return $this->get_dao()->update($obj);
				}
				else
				{
					return 'C';
				}
			}
			else
			{
				if($obj->get_status() == 'PS' && $status["completed"] == $status["total"] && !$update)
				{
					return 'C';
				}
			}

			return TRUE;
		}
	}

	public function send_notice($info=array())
	{
		if(!is_array($info) || !count($info))
		{
			exit;
		}
		$message = "";
		foreach($info as $obj_array)
		{
			$imobj = $obj_array["im"];
			$poisobj = $obj_array["pois"];
			$message .= "In shipment ".$poisobj->get_sid()."- ".$imobj->get_sku().",". $poisobj->get_qty() ."were shipped but only ".$poisobj->get_received_qty()." were received.<br>";
		}

		include_once APPPATH."libraries/dto/event_email_dto.php";
		$dto = new Event_email_dto();

		$replace["title"] = "Notice for receiving discripencies";
		$replace["message"] = $message;

		$dto->set_event_id("notification");
		$dto->set_mail_from('do_not_reply@valuebasket.com');
		$dto->set_mail_to(array("sourcing@aln.hk","simon@aln.hk","fiona@aln.hk"));
		//$dto->set_mail_to("itsupport@eservicesgroup.net");
		$dto->set_tpl_id("general_alert");
		$dto->set_replace($replace);
		$this->get_event_srv()->fire_event($dto);

	}

	public function get_pm_dao()
	{
		return $this->pm_dao;
	}

	public function set_pm_dao(Base_dao $dao)
	{
		$this->pm_dao = $dao;
	}

	public function get_event_srv()
	{
		return $this->event_srv;
	}

	public function set_event_srv(Base_service $event_srv)
	{
		$this->event_srv = $event_srv;
	}

}
?>
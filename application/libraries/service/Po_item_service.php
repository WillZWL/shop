<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once "Base_service.php";

class Po_item_service extends Base_service
{
	public function __construct()
	{
		parent::__construct();
		include_once(APPPATH."libraries/dao/Po_item_dao.php");
		$this->set_dao(new Po_item_dao());
	}

	public function update_qty($qty,$where)
	{
		$obj = $this->get_dao()->get($where);
		if($obj === FALSE)
		{
			echo $this->db->_error_message();
			return FALSE;
		}
		else
		{
			$obj->set_shipped_qty($obj->get_shipped_qty() + $qty);
			return $this->get_dao()->update($obj);
		}
	}

	public function check_outstanding($input,$po_number, $line_number)
	{
		return ($input > $this->get_dao()->get_outstanding($po_number,$line_number)?1:0);
	}
}

?>
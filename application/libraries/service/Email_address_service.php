<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once "Base_service.php";

class Email_address_service extends Base_service
{

	public function __construct()
	{
		parent::__construct();
		include_once(APPPATH."libraries/dao/Email_address_dao.php");
		$this->set_dao(new Email_address_dao());
	}

	public function get_email_address($func_id)
	{
		return $this->get_dao()->get_email_address($func_id);
	}

	public function get_email_address_list($func_id, $type = "array")
	{
		return $this->get_dao()->get_email_address_list($func_id, $type);
	}
}

/* End of file email_address_service.php */
/* Location: ./system/application/libraries/service/Email_address_service.php */
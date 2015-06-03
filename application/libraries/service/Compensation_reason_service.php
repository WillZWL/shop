<?php

include_once "Base_service.php";

class Compensation_reason_service extends Base_service
{

	public function __construct()
	{
		parent::__construct();
		include_once(APPPATH."libraries/dao/Compensation_reason_dao.php");
		$this->set_dao(new Compensation_reason_dao());
	}
}

/* End of file compensation_reason_service.php */
/* Location: ./app/libraries/service/Compensation_reason_service.php */
<?php

include_once "Base_service.php";

class So_risk_service extends Base_service
{

	public function __construct()
	{
		parent::__construct();
		include_once(APPPATH."libraries/dao/So_risk_dao.php");
		$this->set_dao(new So_risk_dao());
	}
}

/* End of file so_risk_service.php */
/* Location: ./app/libraries/service/So_risk_service.php */
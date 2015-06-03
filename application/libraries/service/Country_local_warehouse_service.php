<?php

include_once "Base_service.php";

class Country_local_warehouse_service extends Base_service
{

	public function __construct()
	{
		parent::__construct();
		include_once(APPPATH."libraries/dao/Country_local_warehouse_dao.php");
		$this->set_dao(new Country_local_warehouse_dao());
	}
}

/* End of file country_local_warehouse_service.php */
/* Location: ./app/libraries/dao/Country_local_warehouse_service.php */
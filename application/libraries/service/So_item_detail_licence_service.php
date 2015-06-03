<?php

include_once "Base_service.php";

class So_item_detail_licence_service extends Base_service
{

	public function __construct()
	{
		parent::__construct();
		include_once(APPPATH."libraries/dao/So_item_detail_licence_dao.php");
		$this->set_dao(new So_item_detail_licence_dao());
	}
}

/* End of file so_item_detail_licence_dao.php */
/* Location: ./app/libraries/dao/So_item_detail_licence_dao.php */
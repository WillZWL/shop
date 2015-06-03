<?php

include_once "Base_service.php";

class So_release_order_service extends Base_service
{
	public function __construct()
	{
		parent::__construct();
		include_once(APPPATH."libraries/dao/So_release_order_dao.php");
		$this->set_dao(new So_release_order_dao());
	}

	public function get_release_order_history_list($where = array(), $option = array())
	{
		return $this->get_dao()->get_list($where,$option);
	}
}

/* End of file so_release_order_service */
/* Location: ./app/libraries/service/So_release_order_service.php */
<?php

include_once "Base_service.php";

class So_payment_query_log_service extends Base_service
{

	public function __construct()
	{
		parent::__construct();
		include_once(APPPATH."libraries/dao/So_payment_query_log_dao.php");
		$this->set_dao(new So_payment_query_log_dao());
	}

	public function add_log($so_no, $type, $text)
	{
		$vo = $this->get();
		$vo->set_so_no($so_no);
		$vo->set_text_type($type);
		$vo->set_text($text);
		$this->insert($vo);
	}
}

/* End of file so_payment_query_log_service.php */
/* Location: ./app/libraries/service/So_payment_query_log_service.php */
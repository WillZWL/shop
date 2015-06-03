<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once "Base_service.php";

class T3m_filelog_service extends Base_service
{

	public function __construct()
	{
		parent::__construct();
		include_once APPPATH."libraries/dao/T3m_filelog_dao.php";
		$this->set_dao(new T3m_filelog_dao());
	}

}


?>
<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once "Base_service.php";

class Language_service extends Base_service {

	public function __construct(){
		parent::__construct();
		include_once(APPPATH."libraries/dao/Language_dao.php");
		$this->set_dao(new Language_dao());
	}

	public function get_name_w_id_key()
	{
		$llist = $this->get_dao()->get_list(array("status"=>1),array("limit"=>-1));
		$ret = array();
		foreach($llist as $lobj)
		{
			$ret[$lobj->get_id()] = $lobj->get_name();
		}

		return $ret;
	}
}

/* End of file Language_service.php */
/* Location: ./system/application/libraries/service/Language_service.php */
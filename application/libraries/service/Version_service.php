<?php

include_once "Base_service.php";

class Version_service extends Base_service
{

	public function __construct()
	{
		parent::__construct();
		include_once APPPATH."libraries/dao/Version_dao.php";
		$this->set_dao(new Version_dao());
	}

	public function insert($obj)
	{
		return $this->get_dao()->insert($obj);
	}

	public function update($obj)
	{
		return $this->get_dao()->update($obj);
	}

	public function get($where = array())
	{
		return $this->get_dao()->get($where);
	}

	public function get_new()
	{
		return $this->get_dao()->get();
	}

	public function get_list($where=array(), $option=array())
	{
		return $this->get_dao()->get_list($where, $option);
	}

	public function get_list_cnt($where=array())
	{
		return $this->get_dao()->get_list_cnt($where);
	}
}


?>
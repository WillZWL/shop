<?php

include_once "Base_service.php";

class Ixten_reprice_rule_service extends Base_service
{

	public function __construct()
	{
		parent::__construct();
		include_once(APPPATH."libraries/dao/Ixten_reprice_rule_dao.php");
		$this->set_dao(new Ixten_reprice_rule_dao());
	}

	public function insert($obj)
	{
		return $this->get_dao()->insert($obj);
	}

	public function update($obj)
	{
		return $this->get_dao()->update($obj);
	}

	public function q_delete($obj)
	{
		return $this->get_dao()->q_delete(array("platform_id"=>$obj->get_platform_id(), "id"=>$obj->get_id()));
	}

	public function get_list($where=array(),$option=array())
	{
		return $this->get_dao()->get_list_index($where, $option);
	}

	public function get_ixten_reprice_rule_list()
	{
		return $this->get_dao()->get_ixten_reprice_rule_list();
	}
}

/* End of file ixten_reprice_rule_service.php */
/* Location: ./app/libraries/service/Ixten_reprice_rule_service.php */
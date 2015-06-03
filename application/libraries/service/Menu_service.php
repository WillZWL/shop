<?php

include_once "Base_service.php";

class Menu_service extends Base_service
{

	public function __construct()
	{
		parent::__construct();
		include_once APPPATH."libraries/dao/Menu_dao.php";
		$this->set_dao(new Menu_dao());
	}

	public function get($where=array())
	{
		return $this->get_dao()->get($where);
	}

	public function get_list($where=array(), $option=array())
	{
		return $this->get_dao()->get_list($where, $option);
	}

	public function insert($obj)
	{
		return $this->get_dao()->insert($obj);
	}

	public function update($obj)
	{
		return $this->get_dao()->update($obj);
	}

	public function get_list_w_name($where=array(), $option=array())
	{
		return $this->get_dao()->get_list_w_name($where, $option);
	}

	public function get_fm_list_w_name($lang_id="en", $where=array(), $option=array())
	{
		return $this->get_dao()->get_fm_list_w_name($lang_id, $where, $option);
	}

	public function get_footer_menu_list($lang_id="en", $where=array(), $option=array())
	{
		$list = $this->get_dao()->get_fm_list_w_name($lang_id, $where, $option);
		foreach($list as $obj)
		{
			//firephp($obj);
			if($obj->get_level() == 0)
			{
				$rs['menu_list'][] = $obj;
			}
			elseif($obj->get_level() == 1)
			{
				$rs['menu_item_list'][$obj->get_parent_id()][] = $obj;
			}
		}
		return $rs;
	}
}

?>
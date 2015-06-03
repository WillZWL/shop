<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Base_service
{

	var $_dao;

	public function __construct()
	{
	}


	public function set_dao(Base_dao $dao)
	{
		$this->_dao = $dao;
		$this->db = $dao->db;
	}

	public function get_dao()
	{
		return $this->_dao;
	}

	public function get_list($where = array(), $option = array(), $classname = "")
	{
		if ($this->_dao instanceof Base_dao)
		{
			return $this->_dao->get_list($where, $option, $classname);
		}
		else
		{
			return FALSE;
		}
	}

	public function get_num_rows($where=array())
	{
		if ($this->_dao instanceof Base_dao)
		{
			return $this->_dao->get_num_rows($where);
		}
		else
		{
			return FALSE;
		}
	}

	public function get_db_time()
	{
		if ($this->_dao instanceof Base_dao)
		{
			return $this->_dao->get_db_time($where);
		}
		else
		{
			return FALSE;
		}
	}

	public function get($where = array(), $classname = "")
	{
		if ($this->_dao instanceof Base_dao) {
			return $this->_dao->get($where, $classname);
		} else {
			return FALSE;
		}
	}

	public function insert(Base_vo $obj)
	{
		if ($this->_dao instanceof Base_dao) {
			return $this->_dao->insert($obj);
		} else {
			return FALSE;
		}
	}

	public function update(Base_vo $obj, $where=array())
	{
		if ($this->_dao instanceof Base_dao) {
			return $this->_dao->update($obj, $where);
		} else {
			return FALSE;
		}
	}

	public function delete(Base_vo $obj)
	{
		if ($this->_dao instanceof Base_dao) {
			return $this->_dao->delete($obj);
		} else {
			return FALSE;
		}
	}

	public function q_delete($where=array())
	{
		if ($this->_dao instanceof Base_dao)
		{
			return $this->_dao->q_delete($where);
		}
		else
		{
			return FALSE;
		}
	}

	public function get_max_modify($table_list)
	{
		if ($this->_dao instanceof Base_dao)
		{
			return $this->_dao->get_max_modify($table_list);
		}
		else
		{
			return FALSE;
		}
	}

	public function include_vo()
	{
		if ($this->_dao instanceof Base_dao)
		{
			return $this->_dao->include_vo();
		}
		else
		{
			return FALSE;
		}
	}

	public function include_dto($dto)
	{
		if ($this->_dao instanceof Base_dao)
		{
			return $this->_dao->include_dto($dto);
		}
		else
		{
			return FALSE;
		}
	}

	public function get_email_address($func_id)
	{
		include_once(APPPATH."libraries/dao/Email_address_dao.php");
		$email_addr_dao = new Email_address_dao();
		return $email_addr_dao->get_email_address($func_id);
	}

	public function get_email_address_list($func_id, $type = "array")
	{
		include_once(APPPATH."libraries/dao/Email_address_dao.php");
		$email_addr_dao = new Email_address_dao();
		return $email_addr_dao->get_email_address_list($func_id, $type);
	}
}


/* End of file base_service.php */
/* Location: ./system/application/libraries/service/Base_service.php */
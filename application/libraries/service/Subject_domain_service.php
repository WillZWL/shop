<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once "Base_service.php";

class Subject_domain_service extends Base_service {

	private $sub_dd_dao;
	private $sub_ddl_dao;

	function __construct()
	{
		parent::__construct();
		include_once(APPPATH."libraries/dao/Subject_domain_dao.php");
		$this->set_dao(new Subject_domain_dao());
		include_once(APPPATH."libraries/dao/Subject_domain_detail_dao.php");
		$this->set_sub_dd_dao(new Subject_domain_detail_dao());
		include_once(APPPATH."libraries/dao/Subject_domain_detail_label_dao.php");
		$this->set_sub_ddl_dao(new Subject_domain_detail_label_dao());
	}

	public function get_sub_dd_dao()
	{
		return $this->sub_dd_dao;
	}

	public function set_sub_dd_dao(Base_dao $dao)
	{
		$this->sub_dd_dao = $dao;
	}

	public function get_sub_ddl_dao()
	{
		return $this->sub_ddl_dao;
	}

	public function set_sub_ddl_dao(Base_dao $dao)
	{
		$this->sub_ddl_dao = $dao;
	}

	/*
	public function get_value_w_subject_subkey($subject="", $subkey="")
	{
		return $this->get_sub_dd_dao()->get_value_w_subject_subkey($subject, $subkey);
	}
	*/

	public function value_of($subject="", $subkey="", $lang_id="")
	{
		return $this->get_sub_ddl_dao()->value_of($subject, $subkey, $lang_id);
	}

	public function get_subj_list_w_subj_lang($subject="", $lang_id="")
	{
		return $this->get_sub_ddl_dao()->get_subj_list_w_subj_lang($subject, $lang_id);
	}

	public function get_list_w_subject($subject=array(), $option=array())
	{
		return $this->get_sub_dd_dao()->get_list_w_subject($subject, $option);
	}
}


/* End of file Subject_domain_service.php */
/* Location: ./system/application/libraries/service/Subject_domain_service.php */

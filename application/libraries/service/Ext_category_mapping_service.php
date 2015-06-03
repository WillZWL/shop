<?php if(! defined('BASEPATH')) exit('No Direct script access allowed');

include_once "Base_service.php";

class Ext_category_mapping_service extends Base_service
{
	private $category_mapping_dao;
	private $category_mapping_srv;
	public function __construct()
	{
		parent::__construct();
		include_once(APPPATH."libraries/dao/Ext_category_mapping_dao.php");
		//include_once(APPPATH."libraries/dao/")
		$this->set_dao(new Ext_catetory_mapping_dao());

		include_once(APPPATH."libraries/dao/Category_mapping_dao.php");
		$this->category_mapping_dao=new Category_mapping_dao();

		include_once(APPPATH."libraries/service/Category_mapping_service.php");
		$this->category_mapping_srv=new Category_mapping_service();
	}

	public function get_cat_list($where=array(), $option=array(), $classname="")
	{
		return $this->dao->get_cat_list($where=array(), $option=array(), $classname="");
	}

	public function get_category_mapping_dao()
	{
		return $this->category_mapping_dao;
	}

	public function get_category_mapping_srv()
	{
		return $this->category_mapping_srv;
	}

	public function get_google_category_mapping_list($where = array(), $option  =array())
	{
		return $this->get_dao()->get_google_category_mapping_list($where, $option);
	}

	public function get_category_combination($where = array(), $option  =array())
	{
		return $this->get_dao()->get_category_combination($where, $option);
	}

}

?>
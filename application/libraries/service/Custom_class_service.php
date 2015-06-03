<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once "Base_service.php";

class Custom_class_service extends Base_service {

	function __construct(){
		parent::__construct();
		include_once(APPPATH."libraries/dao/Custom_classification_dao.php");
		$this->set_dao(new Custom_classification_dao());
		include_once(APPPATH."libraries/dao/Product_custom_classification_dao.php");
		$this->set_pcc_dao(new Product_custom_classification_dao());
		include_once(APPPATH."libraries/dao/Custom_classification_mapping_dao.php");
		$this->set_ccm_dao(new Custom_classification_mapping_dao());
	}

	public function get_option($where=array())
	{
		return $this->get_dao()->get_option($where);
	}

	public function get_pcc_dao()
	{
		return $this->pcc_dao;
	}

	public function set_pcc_dao(Base_dao $dao)
	{
		$this->pcc_dao = $dao;
	}

	public function get_ccm_dao()
	{
		return $this->ccm_dao;
	}

	public function set_ccm_dao(Base_dao $dao)
	{
		$this->ccm_dao = $dao;
	}

	public function get_pcc($where=array())
	{
		return $this->get_pcc_dao()->get($where);
	}

	public function update_pcc($obj)
	{
		return $this->get_pcc_dao()->update($obj);
	}

	public function include_pcc_vo()
	{
		return $this->get_pcc_dao()->include_vo();
	}

	public function add_pcc(Base_vo $obj)
	{
		return $this->get_pcc_dao()->insert($obj);
	}

	public function get_pcc_list($where=array(), $option=array())
	{
		$data["pcclist"] = $this->get_pcc_dao()->get_pcc_list($where, $option);
		$option["num_rows"] = 1;
		$data["total"] = $this->get_pcc_dao()->get_pcc_list($where, $option);
		return $data;
	}

	public function get_ccm_list($where=array(), $option=array())
	{
		$data["pcclist"] = $this->get_ccm_dao()->get_list($where, $option);
		$data["total"] = $this->get_ccm_dao()->get_num_rows($where);
		return $data;
	}

	public function get_full_pcc_by_sku($where=array(), $option=array())
	{
		return  $this->get_pcc_dao()->get_all_pcc_list($where, $option);
	}

	public function get_hs_by_subcat_and_country($where=array(), $option=array())
	{
		return $this->get_ccm_dao()->get_hs_by_subcat_and_country($where, $option);
	}
}

/* End of file custom_class_service.php */
/* Location: ./system/application/libraries/service/Custom_class_service.php */
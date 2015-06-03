<?php

class Skype_promotion_model extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
		$this->load->library('service/skype_promotion_service');
		$this->load->library('service/selling_platform_service');
	}

	public function __autoload()
	{
		$this->skype_promotion_service->get_dao()->include_vo();
	}

	public function get_count($catid="",$mode="", $platform="")
	{
		return $this->skype_promotion_service->get_count($catid,$mode,$platform);

	}

	public function get_vo()
	{
		return $this->skype_promotion_service->get_dao()->get();
	}

	public function get_skype_promotion($catid="", $rank="", $platform="")
	{
		return $this->skype_promotion_service->get_skype_promotion($catid, $rank, $platform);
	}

	public function insert($obj)
	{
		return $this->skype_promotion_service->insert($obj);
	}

	public function update($obj)
	{
		return $this->skype_promotion_service->update($obj);
	}

    public function get_product_list($where=array(), $option=array())
    {
		return $this->skype_promotion_service->get_product_list($where, $option);
    }

	public function get_product_list_total($where=array(),$option = array())
	{
		$option["num_rows"] = 1;
		return $this->skype_promotion_service->get_product_list_total($where, $option);
	}

	public function get_list_w_name($catid,$mode,$type="SP",$platform)
	{
		return $this->skype_promotion_service->get_list_w_name($catid, $mode, $type, $platform);
	}

	public function delete_sp($where=array())
	{
		return $this->skype_promotion_service->delete_sp($where);
	}

	public function trans_start()
	{
		$this->skype_promotion_service->trans_start();
	}

	public function trans_complete()
	{
		$this->skype_promotion_service->trans_complete();
	}

	public function get_list_limit()
	{
		return $this->skype_promotion_service->get_limit();
	}
	public function gen_listing()
	{
		$this->skype_promotion_service->gen_listing();
	}
}



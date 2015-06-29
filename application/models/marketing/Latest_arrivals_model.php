<?php

class Latest_arrivals_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('service/latest_arrivals_service');
        $this->load->library('service/selling_platform_service');
    }

    public function __autoload()
    {
        $this->latest_arrivals_service->get_dao()->include_vo();
    }

    public function get_count($catid="",$mode="",$platform="")
    {
        return $this->latest_arrivals_service->get_count($catid,$mode,$platform);
    }

    public function get_vo()
    {
        return $this->latest_arrivals_service->get_dao()->get();
    }

    public function get_latest_arrivals($catid="", $rank="", $platform="")
    {
        return $this->latest_arrivals_service->get_latest_arrivals($catid, $rank, $platform);
    }

    public function insert($obj)
    {
        return $this->latest_arrivals_service->insert($obj);
    }

    public function update($obj)
    {
        return $this->latest_arrivals_service->update($obj);
    }

    public function get_product_list($where=array(), $option=array())
    {
        return $this->latest_arrivals_service->get_product_list($where, $option);
    }

    public function get_product_list_total($where=array(),$option = array("num_rows"=>1))
    {
        return $this->latest_arrivals_service->get_product_list_total($where, $option);
    }

    public function get_list_w_name($catid,$mode,$type="LA",$platform)
    {
        return $this->latest_arrivals_service->get_list_w_name($catid,$mode,$type,$platform);
    }

    public function delete_bs($where=array())
    {
        return $this->latest_arrivals_service->delete_bs($where);
    }

    public function trans_start()
    {
        $this->latest_arrivals_service->trans_start();
    }

    public function trans_complete()
    {
        $this->latest_arrivals_service->trans_complete();
    }

    public function get_cat_list_index($where, $option, $type="LA")
    {
        $result =  $this->latest_arrivals_service->get_dao()->get_index_list($where,$option,$type);
        $count = $this->latest_arrivals_service->get_dao()->get_index_list($where,array("num_rows"=>1),$type);
        return array("list"=>$result, "total"=>$count);
    }

    public function get_display_list($catid)
    {
        return $this->latest_arrivals_service->display_list($catid);
    }

    public function get_list_limit()
    {
        return $this->latest_arrivals_service->get_limit();
    }

    public function gen_listing()
    {
        $this->latest_arrivals_service->gen_listing();
    }

    public function get_platform_id_list($where,$option)
    {
        return $this->selling_platform_service->get_list($where, $option);
    }
}



<?php

class Best_seller_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        //$this->load->library('service/category_service');
        $this->load->library('service/best_seller_service');
        $this->load->library('service/selling_platform_service');
    }

    public function __autoload()
    {
        $this->best_seller_service->get_dao()->include_vo();
    }

    public function get_count($catid="",$mode="", $platform="")
    {
        return $this->best_seller_service->get_count($catid,$mode, $platform);

    }

    public function get_vo()
    {
        return $this->best_seller_service->get_dao()->get();
    }

    public function get_best_seller($catid="", $rank="", $platform="")
    {
        return $this->best_seller_service->get_best_seller($catid, $rank, $platform);
    }

    public function insert($obj)
    {
        return $this->best_seller_service->insert($obj);
    }

    public function update($obj)
    {
        return $this->best_seller_service->update($obj);
    }

    public function get_product_list($where=array(), $option=array())
    {
        return $this->best_seller_service->get_product_list($where, $option);
    }

    public function get_product_list_total($where=array(),$option = array())
    {
        $option["num_rows"] = 1;
        return $this->best_seller_service->get_product_list_total($where, $option);
    }

    public function get_list_w_name($catid,$mode,$type="BS",$platform)
    {
        return $this->best_seller_service->get_list_w_name($catid, $mode, $type, $platform);
    }

    public function delete_bs($where=array())
    {
        return $this->best_seller_service->delete_bs($where);
    }

    public function trans_start()
    {
        $this->best_seller_service->trans_start();
    }

    public function trans_complete()
    {
        $this->best_seller_service->trans_complete();
    }
    public function get_cat_list_index($where, $option, $type="BS")
    {
        $result =  $this->best_seller_service->get_dao()->get_index_list($where,$option,$type);
        $count = $this->best_seller_service->get_dao()->get_index_list($where,array("num_rows"=>1),$type);
        return array("list"=>$result, "total"=>$count);
    }

    public function get_display_list($catid)
    {
        return $this->best_seller_service->display_list($catid);
    }

    public function get_list_limit()
    {
        return $this->best_seller_service->get_limit();
    }

    public function gen_listing()
    {
        $this->best_seller_service->gen_listing();
    }

    public function get_platform_id_list($where,$option)
    {
        return $this->selling_platform_service->get_list($where, $option);
    }
}



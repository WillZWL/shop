<?php

class On_sale_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('service/on_sale_service');
        $this->load->library('service/selling_platform_service');
    }

    public function __autoload()
    {
        $this->on_sale_service->get_dao()->include_vo();
    }

    public function get_count($catid="",$mode="",$platform="")
    {
        return $this->on_sale_service->get_count($catid,$mode,$platform);
    }

    public function get_vo()
    {
        return $this->on_sale_service->get_dao()->get();
    }

    public function get_on_sale($catid="", $rank="", $platform="")
    {
        return $this->on_sale_service->get_on_sale($catid, $rank, $platform);
    }

    public function insert($obj)
    {
        return $this->on_sale_service->insert($obj);
    }

    public function update($obj)
    {
        return $this->on_sale_service->update($obj);
    }

    public function get_product_list($where=array(), $option=array())
    {
        return $this->on_sale_service->get_product_list($where, $option);
    }

    public function get_product_list_total($where=array(),$option = array("num_rows"=>1))
    {
        return $this->on_sale_service->get_product_list_total($where, $option);
    }

    public function get_list_w_name($catid,$mode,$type="CL",$platform)
    {
        return $this->on_sale_service->get_list_w_name($catid,$mode,$type,$platform);
    }

    public function delete_cl($where=array())
    {
        return $this->on_sale_service->delete_cl($where);
    }

    public function trans_start()
    {
        $this->on_sale_service->trans_start();
    }

    public function trans_complete()
    {
        $this->on_sale_service->trans_complete();
    }

    public function get_cat_list_index($where, $option, $type="LA")
    {
        $result =  $this->on_sale_service->get_dao()->get_index_list($where,$option,$type);
        $count = $this->on_sale_service->get_dao()->get_index_list($where,array("num_rows"=>1),$type);
        return array("list"=>$result, "total"=>$count);
    }

    public function get_display_list($catid)
    {
        return $this->on_sale_service->display_list($catid);
    }

    public function get_list_limit()
    {
        return $this->on_sale_service->get_limit();
    }

    public function gen_listing()
    {
        $this->on_sale_service->gen_listing();
    }

    public function get_platform_id_list($where,$option)
    {
        return $this->selling_platform_service->get_list($where, $option);
    }
}



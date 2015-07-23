<?php

class Search_model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->load->library('service/product_search_service');
        $this->load->library('service/display_banner_service');
        $this->load->library('service/display_category_banner_service');
        $this->load->library('service/product_service');
    }

    public function get_product_search_list($where = array(), $option = array())
    {
        return $this->product_search_service->get_product_search_list($where, $option);
    }

    public function get_product_search_list_wo_keyword($where = array(), $option = array())
    {
        return $this->product_search_service->search_without_keyword($where, $option);
    }

    public function get_publish_banner($display_id, $position_id, $country_id, $lang_id, $usage = "PB")
    {
        return $this->display_banner_service->get_publish_banner($display_id, $position_id, $country_id, $lang_id, $usage = "PB");
    }

    public function get_publish_cat_banner($catid, $display_id, $position_id, $country_id, $lang_id, $usage = "PB")
    {
        return $this->display_category_banner_service->get_publish_banner($catid, $display_id, $position_id, $country_id, $lang_id, $usage = "PB");
    }

    public function get_product_banner($sku = "", $display_id = "", $position_id = "", $lang_id = "en")
    {
        return $this->product_service->get_product_banner($sku, $display_id, $position_id, $lang_id);
    }

    public function get_product_search_list_for_ss_live_price($platform_id, $sku, $with_rrp)
    {
        return $this->product_search_service->get_product_search_list_for_ss_live_price($platform_id, $sku, $with_rrp);
    }
}

?>
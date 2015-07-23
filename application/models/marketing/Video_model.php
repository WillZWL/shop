<?php

class Video_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('service/product_service');
        $this->load->library('service/video_service');
        $this->load->library('service/selling_platform_service');
        $this->load->library('service/country_service');
        $this->load->library('service/language_service');
        $this->load->library('service/category_service');
        $this->load->library('service/platform_biz_var_service');
    }

    public function get($where = array())
    {
        return $this->product_service->get_pv_dao()->get($where);
    }

    public function get_list($where = array(), $option = array())
    {
        return $this->product_service->get_pv_dao()->get_list($where, $option);
    }

    public function get_vo()
    {
        return $this->product_service->get_pv_dao()->get();
    }

    public function insert($obj)
    {
        return $this->product_service->get_pv_dao()->insert($obj);
    }

    public function update($obj)
    {
        return $this->product_service->get_pv_dao()->update($obj);
    }

    public function get_product_list($where = array(), $option = array())
    {
        return $this->video_service->get_product_list($where, $option);
    }

    public function get_product_list_total($where = array(), $option = array())
    {
        $option["num_rows"] = 1;
        return $this->video_service->get_product_list_total($where, $option);
    }

    public function get_video_list($where = array(), $option = array())
    {
        return $this->video_service->get_video_list($where, $option);
    }

    public function get_video_list_w_country($sku = "", $country_arr = array())
    {
        return $this->video_service->get_video_list_w_country($sku, $country_arr);
    }

    public function get_num_rows($where = array(), $option = array())
    {
        return $this->video_service->get_num_rows($where, $option);
    }

    public function get_num_rows_w_country($sku = "", $country_arr = array())
    {
        return $this->video_service->get_num_rows_w_country($sku, $country_arr);
    }

    public function trans_start()
    {
        $this->product_service->get_pv_dao()->trans_start();
    }

    public function trans_complete()
    {
        $this->product_service->get_pv_dao()->trans_complete();
    }

    public function add_product_video($obj)
    {
        return $this->product_service->get_pv_dao()->insert($obj);
    }

    public function update_product_video($obj)
    {
        return $this->product_service->get_pv_dao()->update($obj);
    }

    public function del_product_video($where = array())
    {
        return $this->product_service->get_pv_dao()->q_delete($where);
    }

    public function get_platform_id_list($where = array(), $option = array())
    {
        return $this->selling_platform_service->get_list($where, $option);
    }

    public function get_country_list($where = array(), $option = array())
    {
        return $this->country_service->get_list($where, $option);
    }

    public function get_lang_list($where = array(), $option = array())
    {
        return $this->language_service->get_list($where, $option);
    }

    public function get_cat_list($where = array(), $option = array())
    {
        return $this->category_service->get_list($where, $option);
    }

    public function get_cat_num_rows($where = array(), $option = array())
    {
        return $this->category_service->get_num_rows($where, $option);
    }

    public function get_platform_biz_var($where = array())
    {
        return $this->platform_biz_var_service->get($where);
    }

    public function get_platform_biz_var_w_country($country = array())
    {
        return $this->platform_biz_var_service->get_platform_biz_var_w_country($country);
    }

    public function get_video_obj($where = array())
    {
        return $this->product_service->get_pv_dao()->get($where);
    }
}


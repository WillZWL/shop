<?php

class Customer_service_info_model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->load->library('service/customer_service_info_service');
        $this->load->library('service/selling_platform_service');
        $this->load->library('service/country_service');
        $this->load->library('service/language_service');
    }

    public function get($where = array())
    {
        return $this->customer_service_info_service->get($where);
    }

    public function get_list($where = array(), $option = array())
    {
        return $this->customer_service_info_service->get_list($where, $option);
    }

    public function get_platform($where = array())
    {
        return $this->selling_platform_service->get($where);
    }

    public function get_platform_list($where = array(), $option = array())
    {
        return $this->selling_platform_service->get_list($where, $option);
    }

    public function get_platform_list_w_country_id($country_id = "")
    {
        return $this->selling_platform_service->get_platform_list_w_country_id($country_id);
    }

    public function get_platform_list_w_lang_id($lang_id = "")
    {
        return $this->selling_platform_service->get_platform_list_w_lang_id($lang_id);
    }

    public function get_country_list($where = array(), $option = array())
    {
        return $this->country_service->get_list($where, $option);
    }

    public function get_language_list($where = array(), $option = array())
    {
        return $this->language_service->get_list($where, $option);
    }

    public function get_country_language_list()
    {
        return $this->country_service->get_country_language_list();
    }

    public function insert($obj)
    {
        return $this->customer_service_info_service->insert($obj);
    }

    public function update($obj)
    {
        return $this->customer_service_info_service->update($obj);
    }
}

?>
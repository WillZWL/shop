<?php

class Latency_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('service/platform_biz_var_service');
        $this->load->library('service/selling_platform_service');
    }

    public function get_selling_platform_list()
    {
        return $this->platform_biz_var_service->get_selling_platform_list();
    }

    public function get_currency_list()
    {
        return $this->platform_biz_var_service->get_currency_list();
    }

    public function get_platform_biz_var($id = "")
    {
        return $this->platform_biz_var_service->get_platform_biz_var($id);
    }

    public function update($data)
    {
        return $this->platform_biz_var_service->update($data);
    }

    public function add($data)
    {
        return $this->platform_biz_var_service->insert($data);
    }

    public function __autoload()
    {
        $this->platform_biz_var_service->load_vo();
    }

    public function check_platform($id)
    {
        return $this->selling_platform_service->get_dao()->get(array("id" => $id));
    }
}

?>
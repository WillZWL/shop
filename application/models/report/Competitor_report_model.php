<?php

class Competitor_report_model extends CI_Model
{
    private $tool_path;

    public function __construct()
    {
        parent::__construct();
        $this->tool_path = $tool_path;
        $this->load->library('service/country_service');
    }


    public function get_sell_country_list()
    {
        return $this->country_service->get_sell_country_list();
    }

    public function get_list($service, $where = array(), $option = array())
    {
        $service = $service . "_service";
        return $this->$service->get_list($where, $option);
    }


    public function get($service, $where = array())
    {
        $service = $service . "_service";
        return $this->$service->get($where);
    }

    public function update($service, $obj)
    {
        $service = $service . "_service";
        return $this->$service->update($obj);
    }

    public function include_vo($service)
    {
        $service = $service . "_service";
        return $this->$service->include_vo();
    }

    public function include_dto($service, $dto)
    {
        $service = $service . "_service";
        return $this->$service->include_dto($dto);
    }

    public function add($service, $obj)
    {
        $service = $service . "_service";
        return $this->$service->insert($obj);
    }


}

?>
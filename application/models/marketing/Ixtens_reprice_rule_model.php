<?php

class Ixtens_reprice_rule_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('service/ixten_reprice_rule_service');
        $this->load->library('service/selling_platform_service');
    }

    public function update($obj)
    {
        return $this->ixten_reprice_rule_service->update($obj);
    }

    public function insert($obj)
    {
        return $this->ixten_reprice_rule_service->insert($obj);
    }

    public function q_delete($obj)
    {
        return $this->ixten_reprice_rule_service->q_delete($obj);
    }

    public function get($where=array())
    {
        return $this->ixten_reprice_rule_service->get($where);
    }

    public function get_list($where=array(), $option=array())
    {
        return $this->ixten_reprice_rule_service->get_list($where, $option);
    }

    public function get_dao()
    {
        return $this->ixten_reprice_rule_service->get_dao();
    }

    public function get_amazon_platform_list()
    {
        return $this->selling_platform_service->get_list(array("type"=>"AMAZON"));
    }
}
?>
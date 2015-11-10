<?php

class Faqadmin_model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->load->library("service/faqadmin_service");
    }

    public function get_list_cnt($where = array(), $option = array())
    {
        return array("list" => $this->faqadmin_service->get_list_cnt($where, $option), "cnt" => $this->faqadmin_service->get_list($where, array("cnt" => 1)));
    }

    public function get($where = array())
    {
        return $this->faqadmin_service->get($where);
    }

    public function update($obj)
    {
        return $this->faqadmin_service->update($obj);
    }

    public function insert($obj)
    {
        return $this->faqadmin_service->insert($obj);
    }

    public function get_content($platform_id = 'WSGB')
    {
        return $this->faqadmin_service->get_content($platform_id);
    }
}


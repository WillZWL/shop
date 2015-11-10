<?php

class Language_model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->load->library("service/language_service");
    }

    public function get_name_w_id_key()
    {
        return $this->language_service->get_name_w_id_key();
    }

    public function get_list()
    {
        return $this->language_service->get_dao()->get_list(array("status" => 1), array("limit" => -1));
    }

}

?>
<?php

class Ebay_model extends CI_Model
{

    public function Ebay_model($platform_type = NULL)
    {
        parent::__construct();
        $this->load->library('service/ebay_service');
    }
}

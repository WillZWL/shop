<?php

class Chargeback_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('service/flex_service');
        $this->load->library('service/chargeback_service');
    }

}

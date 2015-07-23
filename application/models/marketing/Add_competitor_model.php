<?php

class Add_competitor_model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->load->library('service/competitor_service');
    }

}

?>
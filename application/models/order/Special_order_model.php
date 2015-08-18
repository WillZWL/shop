<?php

class Special_order_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('service/so_service');
        $this->load->library('service/product_service');
    }

}

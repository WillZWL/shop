<?php

class Inventory_adjustment_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('service/inventory_service');
        $this->load->library('service/inv_movement_service.php');
        $this->load->library('service/region_service.php');
        $this->load->library('service/warehouse_service.php');
        $this->load->library('service/product_service.php');
    }
}



?>
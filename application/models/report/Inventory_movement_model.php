<?php

class Inventory_movement_model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->load->library('service/rpt_inventory_movement_service');
    }

    public function get_csv($sku, $start_date, $end_date)
    {
        return $this->rpt_inventory_movement_service->get_csv($sku, $start_date, $end_date);
    }
}



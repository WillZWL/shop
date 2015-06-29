<?php

class Stock_valuation_model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->load->library('service/rpt_stock_valuation_service');
    }

    public function get_csv($sku, $prod_name)
    {
        return $this->rpt_stock_valuation_service->get_csv($sku, $prod_name);
    }
}



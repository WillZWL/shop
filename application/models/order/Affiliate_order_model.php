<?php

class Affiliate_order_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('service/kelkoo_order_service');
        $this->load->library('service/affiliate_service');
    }

    public function kelkoo_report($country_id = "all", $day_diff = 0)
    {
        $this->kelkoo_order_service->gen_data($country_id, $day_diff);
    }

    public function affiliate_delay_report($affiliate_prefix = "all", $day_diff = 0)
    {
        $this->affiliate_service->gen_delay_orders_data($affiliate_prefix, $day_diff);
    }


}

?>
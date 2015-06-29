<?php

class Adroll_product_feed_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('service/adroll_product_feed_service');
    }

    public function send_report($platform_id)
    {
            $this->adroll_product_feed_service->send_report($platform_id);
    }
}


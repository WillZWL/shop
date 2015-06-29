<?php

class Order_held_for_cc_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('service/order_held_for_cc_service');
    }

    public function send_report($duration)
    {
            $this->order_held_for_cc_service->send_report($duration);
    }
}


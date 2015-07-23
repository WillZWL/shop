<?php

class Payment_gateway_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('service/payment_gateway_service');
    }

    public function get_list($where = array(), $option = array())
    {
        return $this->payment_gateway_service->get_list($where, $option);
    }

    public function get_num_rows($where = array())
    {
        return $this->payment_gateway_service->get_num_rows($where);
    }
}

<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Fulfillment_report_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('service/rpt_fulfillment_service');
    }

    public function get_csv($where = array(), $option)
    {

        $header = $this->get_fulfillment_service()->get_header();

        $data = $this->get_fulfillment_service()->get_data($where, $option);

        return $header . $data;

    }

    public function get_fulfillment_service()
    {
        $this->load->library('service/rpt_fulfillment_service');
        return $this->rpt_fulfillment_service;
    }
}



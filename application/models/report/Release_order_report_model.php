<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Release_order_report_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('service/release_order_report_service');
    }

    public function get_csv($where, $option = array())
    {
        return $this->release_order_report_service->get_csv($where, $option);
    }

    public function get_obj_list($where, $option = array())
    {
        return $this->release_order_report_service->get_obj_list($where, $option);
    }
}



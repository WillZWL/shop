<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Skype_report_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('service/rpt_skype_service');
    }

    public function get_skype_report($start_date = "", $end_date = "", $where = array())
    {
        return $this->get_report_service()->get_skype_report($start_date, $end_date, $where);
    }

    public function get_report_service()
    {
        $this->load->library('service/rpt_skype_service');
        return $this->rpt_skype_service;
    }

}

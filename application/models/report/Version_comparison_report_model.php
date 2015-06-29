<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Version_comparison_report_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function get_csv()
    {
        return $this->get_report_service()->get_csv();
    }

    public function get_report_service()
    {
        $this->load->library('service/rpt_version_comparison_service');
        return $this->rpt_version_comparison_service;
    }
}



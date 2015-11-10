<?php

class Sourcing_region_report_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('service/rpt_sourcing_region_report_service');
    }

    public function get_csv($where = array())
    {
        return $this->rpt_sourcing_region_report_service->get_csv($where);
    }

}

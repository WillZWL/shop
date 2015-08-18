<?php

class Compensation_report_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('service/rpt_compensation_report_service');
    }

    public function get_csv($where = array())
    {
        return $this->rpt_compensation_report_service->get_csv($where);
    }

    public function get_obj_list($where = array(), $option = array())
    {
        return $this->rpt_compensation_report_service->get_obj_list($where, $option);
    }
}

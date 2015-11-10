<?php

class Refund_report_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('service/rpt_refund_report_service');
    }

    public function get_csv($where)
    {
        return $this->rpt_refund_report_service->get_csv($where);
    }

}

?>
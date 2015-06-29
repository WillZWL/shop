<?php
class Voucher_report_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('service/rpt_voucher_report_service');
    }

    public function get_csv($start_date, $end_date, $where)
    {
        return $this->rpt_voucher_report_service->get_csv($start_date, $end_date, $where);
    }

}
?>
<?php

class Failed_transaction_model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->load->library('service/rpt_failed_transaction_service');
    }

    public function get_csv($start_date, $end_date)
    {
        return $this->rpt_failed_transaction_service->get_csv($start_date, $end_date);
    }
}

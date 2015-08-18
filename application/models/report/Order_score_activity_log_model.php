<?php

class Order_score_activity_log_model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->load->library('service/rpt_order_score_activity_log_service');
    }

    public function get_csv($start_date, $end_date, $order_score, $order_status)
    {
        return $this->rpt_order_score_activity_log_service->get_csv($start_date, $end_date, $order_score, $order_status);
    }

}

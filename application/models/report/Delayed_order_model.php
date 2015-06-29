<?php
class Delayed_order_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('service/rpt_delayed_order_service');
    }

    public function get_csv($start_date, $end_date, $where)
    {
        return $this->rpt_delayed_order_service->get_csv($start_date, $end_date, $where);
    }

}
?>
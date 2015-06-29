<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once "Report_service.php";

class Rpt_order_score_activity_log_service extends Report_service
{
    private $so_service;

    public function __construct()
    {
        parent::__construct();
        include_once(APPPATH . "libraries/service/So_service.php");
        $this->set_so_service(new So_service());
        $this->set_output_delimiter(',');
    }

    public function get_csv($start_date, $end_date, $order_score, $order_status)
    {
        set_time_limit(300);
        $arr = $this->get_data($start_date, $end_date, $order_score, $order_status);
        return $this->convert($arr);
    }

    public function get_data($start_date = '', $end_date = '', $order_score = '', $order_status = '')
    {
        $where = array();

        if (!empty($start_date)) {
            $where["sps.modify_on >= '$start_date 00:00:00'"] = null;
        }

        if (!empty($end_date)) {
            $where["sps.modify_on <= '$end_date 23:59:59'"] = null;
        }

        if (!empty($order_score)) {
            $where["sps.score"] = $order_score;
        }

        if (!empty($order_status)) {
            $where["s.status"] = $order_status;
        }

        return $this->get_so_service()->get_dao()->get_order_score_activity_log_report($where, array("limit" => -1));
    }

    public function get_so_service()
    {
        return $this->so_service;
    }

    public function set_so_service($value)
    {
        $this->so_service = $value;
        return $this;
    }

    protected function get_default_vo2xml_mapping()
    {
        return '';
    }

    protected function get_default_xml2csv_mapping()
    {
        return APPPATH . 'data/rpt_order_score_activity_log_xml2csv.txt';
    }
}

/* End of file rpt_order_score_activity_log_service.php */
/* Location: ./system/application/libraries/service/Rpt_order_score_activity_log_service.php */
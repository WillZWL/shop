<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once "Report_service.php";

class Rpt_failed_transaction_service extends Report_service
{
    private $so_service;

    public function __construct()
    {
        parent::__construct();
        include_once(APPPATH."libraries/service/So_service.php");
        $this->set_so_service(new So_service());
        $this->set_output_delimiter(',');
    }

    public function set_so_service($value)
    {
        $this->so_service = $value;
        return $this;
    }

    public function get_so_service()
    {
        return $this->so_service;
    }

    public function get_data($start_date, $end_date, $option=array())
    {
        $where = array();

        if (!empty($start_date))
        {
            $where['so.order_create_date >='] = $start_date." 00:00:00";
        }

        if (!empty($end_date))
        {
            $where['so.order_create_date <='] = $end_date." 23:59:59";
        }
        $where['(sops.payment_status = "N" OR sops.payment_status = "F")'] = null;
        $option["limit"] = -1;
        return $this->get_so_service()->get_dao()->get_so_w_payment($where, $option);
    }

    public function get_so_w_payment($where=array(), $option=array())
    {
        return $this->get_so_service()->get_dao()->get_so_w_payment($where, $option);
    }

    public function get_csv($start_date, $end_date)
    {
        $arr = $this->get_data($start_date, $end_date);
        return $this->convert($arr);
    }

    protected function get_default_vo2xml_mapping()
    {
        return '';
    }

    protected function get_default_xml2csv_mapping()
    {
        return '';
    }
}

/* End of file rpt_failed_transaction_service.php */
/* Location: ./system/application/libraries/service/Rpt_failed_transaction_service.php */
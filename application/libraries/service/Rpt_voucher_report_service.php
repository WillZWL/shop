<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once "Report_service.php";


class Rpt_voucher_report_service extends Report_service
{
    private $so_service;

    public function __construct()
    {
        parent::__construct();
        include_once(APPPATH . "libraries/service/So_service.php");
        $this->set_so_service(new So_service());
        $this->set_output_delimiter(',');
    }

    public function get_csv($start_date, $end_date, $where)
    {
        $arr = $this->get_data($start_date, $end_date, $where);
        return $this->convert($arr, false);
    }

    public function get_data($start_date, $end_date, $where)
    {
        if (!empty($start_date)) {
            $start_date .= " 00:00:00";
        }

        if (!empty($end_date)) {
            $end_date .= " 23:59:59";
        }
        //$where['(sops.payment_status = "N" OR sops.payment_status = "F")'] = null;
        //$option["limit"] = -1;
        return $this->get_so_service()->get_dao()->get_voucher_report_item_list($start_date, $end_date, $where);
    }

    public function get_so_service()
    {
        return $this->so_service;
    }

//  public function get_delay_report_item_list($start_date, $end_date, $where, $option=array())
//  {
//      return $this->get_so_service()->get_dao()->get_delay_report_item_list($start_date, $end_date, $where, $option);
//  }

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
        return '';
    }
}

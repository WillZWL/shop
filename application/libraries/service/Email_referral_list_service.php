<?php
defined('BASEPATH') OR exit('No direct script access allowed');

//include_once "Base_service.php";
include_once "Report_service.php";

class Email_referral_list_service extends Report_service
{
    public $dex_service;
    public $delivery_option_service;
    public $encrypt;

    public function __construct()
    {
        parent::__construct();
        include_once(APPPATH."libraries/dao/Email_referral_list_dao.php");
        $this->set_dao(new Email_referral_list_dao());
        $this->set_output_delimiter(',');
    }

    public function get_all_email_referral_list($where,$option)
    {
        return $this->get_dao()->get_all_email_referral_list($where, $option);
    }

    public function get_csv($where, $option)
    {
        $arr = $this->get_dao()->get_all_email_referral_list($where, $option);
        $data = $this->convert($arr);
        return $data;
    }

    protected function get_default_vo2xml_mapping()
    {
        return '';
    }

    protected function get_default_xml2csv_mapping()
    {
        return '';
    }

    public function get_all_orders_report($start_date, $end_date, $so_number)
    {
        return $this->_get_all_orders($start_date, $end_date, $so_number);
    }

    public function get_all_orders_export_report($start_date, $end_date, $so_number)
    {
        $report = $this->_get_all_orders($start_date, $end_date, $so_number);
//      var_dump($report);
        $out_xml = new Vo_to_xml($report);
        $out_xls = new Xml_to_csv('', APPPATH . 'data/all_orders_report.txt', TRUE, ',');
        $data = $this->dex_service->convert($out_xml, $out_xls);
        return $data;
    }

    private function _get_all_orders($start_date, $end_date, $so_number)
    {
        $report = $this->get_dao()->get_all_orders_report($start_date, $end_date, $so_number);
        $delivery_data = end($this->delivery_option_service->get_list_w_key(array("lang_id"=>"en")));
        for ($i=0;$i<sizeof($report);$i++)
        {
            $report[$i]->set_ship_service_level($delivery_data[$report[$i]->get_delivery_mode()]->get_display_name());
            $report[$i]->set_password($this->encrypt->decode($report[$i]->get_password()));
//          $report[$i]->set_bill_name($report[$i]->get_bill_name());
            $report[$i]->set_bill_address($report[$i]->get_bill_address());
            $report[$i]->set_delivery_address($report[$i]->get_delivery_address());
//mb_status
            $report[$i]->set_payment_status($report[$i]->get_payment_status());
//order create date
            $report[$i]->set_order_create_date_time($report[$i]->get_order_create_date_time());
            $report[$i]->set_hold_date_time($report[$i]->get_hold_date_time());
        }
        return $report;
    }
}

/* End of file email_referral_list_service.php */
/* Location: ./system/application/libraries/service/Email_referral_list_service.php */

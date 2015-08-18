<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once "Base_service.php";

class All_orders_service extends Base_service
{
    public $dex_service;
    public $delivery_option_service;
    public $encrypt;

    public function __construct()
    {
        $CI =& get_instance();
        $this->load = $CI->load;
        include_once(APPPATH . "libraries/dao/So_dao.php");
        $this->set_dao(new So_dao());
        include_once(APPPATH . "libraries/service/Data_exchange_service.php");
        $this->dex_service = new Data_exchange_service();
        include_once(APPPATH . "libraries/service/Delivery_option_service.php");
        $this->delivery_option_service = new Delivery_option_service();
        include_once(BASEPATH . "libraries/Encrypt.php");
        $this->encrypt = new CI_Encrypt();
    }

    public function get_all_orders_report($start_date, $end_date, $so_number, $order_type = "", $psp_gateway = "", $hold_reason = "", $currency = "")
    {
        return $this->_get_all_orders($start_date, $end_date, $so_number, $order_type, $psp_gateway, $hold_reason, $currency);
    }

    private function _get_all_orders($start_date, $end_date, $so_number, $order_type = "", $psp_gateway = "", $hold_reason = "", $currency = "")
    {
        $report = $this->get_dao()->get_all_orders_report($start_date, $end_date, $so_number, $order_type, $psp_gateway, $hold_reason, $currency);
        $delivery_data = end($this->delivery_option_service->get_list_w_key(array("lang_id" => "en")));
        for ($i = 0; $i < sizeof($report); $i++) {
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

    public function get_all_orders_export_report($start_date, $end_date, $so_number, $order_type, $psp_gateway, $hold_reason, $currency)
    {
        $report = $this->_get_all_orders($start_date, $end_date, $so_number, $order_type, $psp_gateway, $hold_reason, $currency);
//      var_dump($report);
        $out_xml = new Vo_to_xml($report);
        $out_xls = new Xml_to_csv('', APPPATH . 'data/all_orders_report.txt', TRUE, ',');
        $data = $this->dex_service->convert($out_xml, $out_xls);
        return $data;
    }
}


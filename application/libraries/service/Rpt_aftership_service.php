<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once "Report_service.php";

class Rpt_aftership_service extends Report_service
{
    public function __construct()
    {
        parent::__construct();
        $this->set_output_delimiter(',');
        include_once(APPPATH . "libraries/service/So_service.php");
        $this->set_so_service(new So_service());
    }

    public function set_so_service($value)
    {
        $this->so_service = $value;
        return $this;
    }

    public function get_data($from_date = '', $to_date = '', $where = array())
    {
        $where["so.dispatch_date >"] = $from_date . ' 00:00:00';
        $where["so.dispatch_date < "] = $to_date . ' 23:59:59';
        $where["so.status"] = 6;
        $arr = $this->get_so_service()->get_aftership_data($where, $option);
        $data .= $this->convert($arr) . "\n";

        return $data;
    }

    public function get_so_service()
    {
        return $this->so_service;
    }

    public function get_aftership_report_for_ftp($from_date = '', $to_date = '', $where = array())
    {
        $where["so.dispatch_date >"] = $from_date . ' 00:00:00';
        $where["so.dispatch_date < "] = $to_date . ' 23:59:59';
        $where["so.status"] = 6;
        $where["sosh.courier_id is not null"] = null;
        $where["sosh.tracking_no is not null"] = null;
        $arr = $this->get_so_service()->get_aftership_report_for_ftp($where, $option);

        $header = "tracking_number,courier,order_id,customer_name,email,destination_country\n";
        $data = "";

        foreach ($arr as $obj) {
            $courier = strtolower(trim($obj->get_courier()));

            if (preg_match('/deutsch-post/', $courier) || preg_match('/deutsche-post/', $courier)) {
                $courier = "deutsch-post";
                $dispatch_date = $obj->get_dispatch_date();
                $date_str = date("m:d:Y", strtotime($dispatch_date));
                $tracking_number = trim($obj->get_trackingno()) . ":" . $date_str;
            } else {
                $tracking_number = trim($obj->get_trackingno());
            }


            $order_id = trim($obj->get_so_no());
            $customer_name = trim($obj->get_bill_name());
            $email = trim($obj->get_clientemail());
            $destination_country = trim($obj->get_country_code());
            $data .= $tracking_number . "," . $courier . "," . $order_id . "," . $customer_name . "," . $email . "," . $destination_country . "\n";
        }

        return $header . $data;
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



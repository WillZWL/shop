<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once "Report_service.php";

class Rpt_wow_email_service extends Report_service
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

    public function get_data()
    {
        $where = $option = array();
        $where["a.courier_id NOT LIKE '%HK%POST%'"] = NULL;
        $where["a.courier_id IN ('DHL','TOLL','DHLBBX','DPD','ARAMEX','TOLL2','FEDEX')"] = NULL;
        $where["so.biz_type NOT IN ('SPECIAL', 'MANUAL')"] = NULL;
        $where["DATE(so.dispatch_date)"] = date("Y-m-d");
        $where["so.delivery_country_id"] = "GB";
        $where["so.status"] = 6;
        $arr = $this->so_service->get_wow_email_list_data($where, $option);
        $data = $this->convert($arr) . "\n";

        return $data;
    }

    public function get_so_service()
    {
        return $this->so_service;
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



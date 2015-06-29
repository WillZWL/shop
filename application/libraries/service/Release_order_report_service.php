<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once "Report_service.php";

class Release_order_report_service extends Report_service
{
    public function __construct()
    {
        parent::__construct();
        include_once(APPPATH . "libraries/dao/Release_order_report_dao.php");
        $this->set_dao(new Release_order_report_dao());
        $this->set_output_delimiter('|');
    }

    public function get_csv($where, $option = array())
    {
        $arr = $this->get_dao()->order_release_activity_log($where, $option);
        $data = $this->convert($arr);
        return $data;
    }

    public function get_obj_list($where, $option = array())
    {
        set_time_limit(300);
        $arr = $this->get_dao()->order_release_activity_log($where, $option);
        return $arr;
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



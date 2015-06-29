<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once "Report_service.php";


class Rpt_refund_report_service extends Report_service
{
    private $so_service;

    public function __construct()
    {
        parent::__construct();
        include_once(APPPATH."libraries/service/Refund_service.php");
        $this->set_refund_service(new Refund_service());
        include_once APPPATH."libraries/dao/Refund_history_dao.php";
        $this->set_history_dao(new Refund_history_dao());
        include_once(APPPATH."libraries/service/Price_service.php");
        $this->set_price_service(new Price_service());
        include_once(APPPATH."libraries/service/Country_service.php");
        $this->set_country_service(new Country_service());
        $this->set_output_delimiter(',');
    }

    public function set_price_service($value)
    {
        $this->price_service = $value;
        return $this;
    }

    public function get_price_service()
    {
        return $this->price_service;
    }

    public function set_refund_service($value)
    {
        $this->refund_service = $value;
        return $this;
    }

    public function get_refund_service()
    {
        return $this->refund_service;
    }

    public function set_history_dao($value)
    {
        $this->history_dao = $value;
        return $this;
    }

    public function get_history_dao()
    {
        return $this->history_dao;
    }

    public function set_country_service($value)
    {
        $this->country_service = $value;
        return $this;
    }

    public function get_country_service()
    {
        return $this->country_service;
    }

    public function get_data($where)
    {
        set_time_limit(300);
        $res = $this->get_refund_service()->get_dao()->get_refund_report_content($where, array("limit"=>-1));

        return $res;
    }

    public function get_csv($where)
    {
        $arr = $this->get_data($where);
        foreach($arr as $obj)
        {
            $refund_type = $obj->get_refund_type();
            switch($refund_type)
            {
                case "R":
                    $obj->set_refund_type("Refund");
                    break;
                case "C":
                    $obj->set_refund_type("CashBack");
                    break;
                default:
            }

            $refund_status = $obj->get_refund_status();
            switch($refund_status)
            {
                case "N":
                    $obj->set_refund_status("NEW");
                    break;
                case "CS":
                    break;
                case "LG":
                    $obj->set_refund_status("LOGISTICS");
                    break;
                case "AC":
                    $obj->set_refund_status("ACCOUNT");
                    break;
                case "D":
                    $obj->set_refund_status("DENIED");
                    break;
                case "C":
                    $obj->set_refund_status("COMPLETED");
                    break;
                default:
            }

            if($obj->get_reason_cat() == "O")
            {
                if($rh_obj = $this->get_history_dao()->get(array("refund_id"=>$obj->get_refund_id(), "status"=>"N")))
                {
                    $obj->set_description("Others: " . $rh_obj->get_notes());
                }
            }

            $search = array(chr(10), chr(13));
            $replace = array(" ", " ");
            $refund_reason = str_replace($search, $replace, $obj->get_description());
            $refund_reason = trim($refund_reason);
            $obj->set_description($refund_reason);

            $refund_comment = str_replace($search, $replace, $obj->get_notes());
            $refund_comment = trim($refund_comment);
            $obj->set_notes($refund_comment);
        }
        return $this->convert($arr);
    }

    protected function get_default_vo2xml_mapping()
    {
        return '';
    }

    protected function get_default_xml2csv_mapping()
    {
        return APPPATH . 'data/refund_report_xml2csv.txt';
    }
}

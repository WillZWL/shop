<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once "Base_service.php";

class Rpt_skype_service extends Base_service
{
    private $so_service;

    public function __construct()
    {
        $CI =& get_instance();
        $this->load = $CI->load;
        include_once(APPPATH . "libraries/dao/So_dao.php");
        $this->set_so_dao(new So_dao());
        include_once(APPPATH . "libraries/service/Data_exchange_service.php");
        $this->set_dex_service(new Data_exchange_service());
    }

    public function set_so_dao(Base_dao $dao)
    {
        $this->so_dao = $dao;
    }

    public function set_dex_service($srv)
    {
        $this->dex_service = $srv;
    }

    public function get_skype_report($start_date = "", $end_date = "", $where = array())
    {
        $sr_list = $this->get_so_dao()->get_skype_report_list($start_date, $end_date, $where);
        $start_date = strtotime($start_date);
        $end_date = strtotime($end_date);
        if ($sr_list) {
            foreach ($sr_list AS $skype_record) {
                $skype_array[$skype_record->get_bill_country_id()][$skype_record->get_period()] = $skype_record;
                $sheet_name["sheet_list"][$skype_record->get_bill_country_id()] = $skype_record->get_bill_country_id();
            }
            foreach ($skype_array AS $record_country => $record_date_array) {
                for ($ts = $start_date; $ts <= $end_date; $ts += 86400) {
                    $match = 0;
                    $cur_date = date('Y-m-d', $ts);
                    if (isset($skype_array[$record_country][$cur_date])) {
                        $modify_sr[] = $skype_array[$record_country][$cur_date];
                    } else {
                        $empty_obj = $this->get_sr_dto();
                        $empty_obj->set_bill_country_id($record_country);
                        $empty_obj->set_period($cur_date);
                        $modify_sr[] = $empty_obj;
                    }
                }
            }
            $sheet_name["sheet_key"] = "bill_country_id";
            $out_xml = new Vo_to_xml($modify_sr);
            $out_xls = new Xml_to_xls('', APPPATH . 'data/skype_report.txt', TRUE, $sheet_name, FALSE);
            $file_path = $this->get_dex_service()->convert($out_xml, $out_xls);
            return $file_path;
        }
    }

    public function get_so_dao()
    {
        return $this->so_dao;
    }

    public function get_sr_dto()
    {
        return $this->get_so_dao()->get(array(), "Skype_report_dto");
    }

    public function get_dex_service()
    {
        return $this->dex_service;
    }

}



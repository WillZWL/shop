<?php
namespace ESG\Panther\Service;

class RptOrderNotInRiaReportService extends ReportService
{
    public function __construct()
    {
        parent::__construct();
        //$this->setOutputDelimiter(',');
    }

    public function getCsv($where = array())
    {
        set_time_limit(300);
        $arr = $this->getDao('So')->getOrderNotInRiaReport($where, array("limit" => -1));
        return $this->convert($arr);
    }

    public function getObjList($where = array(), $option = array())
    {
        set_time_limit(300);
        $arr = $this->getDao('So')->getOrderNotInRiaReport($where, $option);
        return $arr;
    }

    protected function get_default_vo2xml_mapping()
    {
        return '';
    }

    protected function get_default_xml2csv_mapping()
    {
        return APPPATH . 'data/order_not_in_ria_report_xml2csv.txt';
    }
}

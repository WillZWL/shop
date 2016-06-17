<?php
namespace ESG\Panther\Service;

class EmailReferralListService extends ReportService
{
    public function __construct()
    {
        parent::__construct();
        $this->setOutputDelimiter(',');
    }

    public function getAllEmailReferralList($where, $option)
    {
        return $this->getDao('EmailReferralList')->getAllEmailReferralList($where, $option);
    }

    public function getCsv($where = [])
    {
        set_time_limit(300);
        $arr = $this->getDao('EmailReferralList')->getAllEmailReferralList($where, array("limit" => -1));
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



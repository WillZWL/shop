<?php 

namespace ESG\Panther\Service;

use ESG\Panther\Dao\InterfacePendingCourierDao;

class RptCourierTrackingnoReportService extends ReportService
{
	public function __construct()
    {
        parent::__construct();
        $this->_pengdingCourierDao=new  InterfacePendingCourierDao() ;
    }

    public function getCsv($where = array())
    {
        set_time_limit(300);
		$courierBatchOrderDto = $this->_pengdingCourierDao->getCourierOrderByBatch($where,array("limit"=>-1));
        $this->setOutputDelimiter(',');
		return $this->convert($courierBatchOrderDto);
    }

    protected function get_default_vo2xml_mapping()
    {
        return '';
    }

    protected function get_default_xml2csv_mapping()
    {
        return APPPATH . 'data/courier_trackingno_report_xml2csv.txt';
    }
}

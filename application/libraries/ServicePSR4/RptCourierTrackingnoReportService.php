<?php 

namespace ESG\Panther\Service;

use ESG\Panther\Dao\InterfacePendingCourierDao;

class RptCourierTrackingnoReportService extends BaseService
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
		return $this->convert($courierBatchOrderDto);
    }

    protected function getDefaultVo2xmlMapping()
    {
        return '';
    }

    protected function getDefaultXml2csvMapping()
    {
        return APPPATH . 'data/courier_trackingno_report_xml2csv.txt';
    }
}

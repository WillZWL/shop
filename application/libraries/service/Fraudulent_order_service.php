<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once "Report_service.php";

class Fraudulent_order_service extends Report_service
{
    public function __construct()
    {
        parent::__construct();
        include_once(APPPATH."libraries/dao/Fraudulent_order_dao.php");
        $this->set_dao(new fraudulent_order_dao());
        $this->set_output_delimiter(',');
    }

    public function get_csv($start_date, $end_date, $where)
    {
        $arr = $this->get_dao()->get_fraud_order($start_date, $end_date, $where);
        $data = $this->convert($arr);
        return $data;
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

/* End of file fraudulent_order_service.php */
/* Location: ./system/application/libraries/service/Fraudulent_order_service.php */
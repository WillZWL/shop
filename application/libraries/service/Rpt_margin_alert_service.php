<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once "Report_service.php";

class Rpt_margin_alert_service extends Report_service
{
    private $price_margin_service;
    private $selling_platform_service;

    public function __construc()
    {
        parent::__construct();
        include_once(APPPATH . "libraries/service/Price_margin_service.php");
        $this->set_price_margin_service(new Price_margin_service());
        $this->set_selling_platform_service(new Selling_platform_service());
        $this->set_output_delimiter(',');
    }

    public function get_csv($sku, $start_date, $end_date)
    {
        $arr = $this->get_data($sku, $start_date, $end_date);
        return $this->convert($arr);
    }

    public function get_data($start_date, $end_date, $platform_id = '')
    {
        $where = array();

        if (!empty($platform_id)) {
            $where['platform_id'] = $platform_id;
        }

        if (!empty($start_date)) {
            $where['start_date'] = $start_date;
        }

        if (!empty($end_date)) {
            $where['end_date'] = $end_date;
        }

        $this->refresh_margin($platform_id);
    }

    protected function refresh_margin($platform_id = '')
    {
        $platform_list = array();

        if (!empty($platform_id) && is_string($platform_id)) {
            $platform_list = array($platform_id);
        } else {
            $platform_list = (array)$this->get_selling_platform_service()->get_list();
        }

        foreach ($platform_list as $temp_platform_id) {
            $this->get_price_margin_service()->refresh_margin($temp_platform_id);
        }
    }

    public function get_selling_platform_service()
    {
        return $this->selling_platform_service;
    }

    public function set_selling_platform_service($value)
    {
        $this->selling_platform_service = $value;
        return $this;
    }

    public function get_price_margin_service()
    {
        return $this->price_margin_service;
    }

    public function set_price_margin_service($value)
    {
        $this->price_margin_service = $value;
        return $this;
    }

    protected function get_default_vo2xml_mapping()
    {
        return '';
    }

    protected function get_default_xml2csv_mapping()
    {
        return APPPATH . 'data/rpt_margin_alert_xml2csv.txt';
    }
}

/* End of file rpt_margin_alert_service.php */
/* Location: ./app/libraries/service/Rpt_margin_alert_service.php */
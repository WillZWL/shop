<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once "Report_service.php";

class Rpt_version_comparison_service extends Report_service
{
    private $price_dao;

    public function __construct()
    {
        parent::__construct();
        $CI =& get_instance();
        $this->load = $CI->load;
        include_once(APPPATH . "libraries/dao/Price_dao.php");
        $this->set_price_dao(new Price_dao());
        include_once(APPPATH . "libraries/service/Data_exchange_service.php");
        $this->set_dex_service(new Data_exchange_service());
        $this->set_output_delimiter(',');
    }

    public function set_dex_service($srv)
    {
        $this->dex_service = $srv;
    }

    public function get_dex_service()
    {
        return $this->dex_service;
    }

    public function get_csv()
    {
        $list = $this->get_price_dao()->get_version_copmarison_list();

        return $this->convert($list, true);
    }

    public function get_price_dao()
    {
        return $this->price_dao;
    }

    public function set_price_dao(Base_dao $dao)
    {
        $this->price_dao = $dao;
    }

    protected function get_default_vo2xml_mapping()
    {
        return '';
    }

    protected function get_default_xml2csv_mapping()
    {
        return APPPATH . 'data/version_comparison_xml2csv.txt';
    }

}

/* End of file rpt_version_comparison_service.php */
/* Location: ./system/application/libraries/service/Rpt_version_comparison_service.php */
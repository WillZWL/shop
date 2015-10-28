<?php
namespace ESG\Panther\Service;

abstract class ReportService extends BaseService
{
    private $dex_service;
    private $delimiter;
    private $vo2xml_mapping;
    private $xml2csv_mapping;

    public function __construct()
    {
        parent::__construct();
        $CI =& get_instance();
        $this->load = $CI->load;
        include_once(APPPATH . "libraries/service/Data_exchange_service.php");
        $this->set_dex_service(new Data_exchange_service());
        $this->vo2xml_mapping = $this->get_default_vo2xml_mapping();
        $this->xml2csv_mapping = $this->get_default_xml2csv_mapping();
    }

    abstract protected function get_default_vo2xml_mapping();

    abstract protected function get_default_xml2csv_mapping();

    public function set_output_delimiter($str = '')
    {
        if (is_object($str)) {
            return; // Nothing should be set.
        }

        $this->delimiter = $str;
        settype($this->delimiter, 'string');
    }

    public function convert($list = array(), $first_line_headling = TRUE)
    {
        $out_xml = new Vo_to_xml($list, $this->get_vo2xml_mapping());
        $out_csv = new Xml_to_csv("", $this->get_xml2csv_mapping(), $first_line_headling, $this->get_output_delimiter());

        return $this->get_dex_service()->convert($out_xml, $out_csv);
    }

    public function get_vo2xml_mapping()
    {
        return $this->vo2xml_mapping;
    }

    public function set_vo2xml_mapping($mapping = '')
    {
        $this->vo2xml_mapping = $mapping;
    }

    public function get_xml2csv_mapping()
    {
        return $this->xml2csv_mapping;
    }

    public function set_xml2csv_mapping($mapping = '')
    {
        $this->xml2csv_mapping = $mapping;
    }

    public function get_output_delimiter()
    {
        return $this->delimiter;
    }

    public function get_dex_service()
    {
        return $this->dex_service;
    }

    public function set_dex_service($srv)
    {
        $this->dex_service = $srv;
    }
}



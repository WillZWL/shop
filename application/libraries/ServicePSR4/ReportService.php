<?php
namespace ESG\Panther\Service;

abstract class ReportService extends BaseService
{
    private $dex_service;
    private $delimiter;
    private $vo2xmlMapping;
    private $xml2csvMapping;

    public function __construct()
    {
        parent::__construct();
        $CI =& get_instance();
        $this->load = $CI->load;

        $this->voToXml = new VoToXml;
        $this->xmlToCsv = new XmlToCsv;

        $this->vo2xmlMapping = $this->get_default_vo2xml_mapping();
        $this->xml2csvMapping = $this->get_default_xml2csv_mapping();
    }

    abstract protected function get_default_vo2xml_mapping();

    abstract protected function get_default_xml2csv_mapping();

    public function setOutputDelimiter($str = '')
    {
        if (is_object($str)) {
            return; // Nothing should be set.
        }

        $this->delimiter = $str;
        settype($this->delimiter, 'string');
    }

    public function convert($list = array(), $firstLineHeadling = TRUE)
    {
        $this->voToXml->VoToXml($list, $this->getVo2xmlMapping());
        $this->xmlToCsv->XmlToCsv("", $this->getXml2csvMapping(), $firstLineHeadling, $this->getOutputDelimiter());
        return $this->getService('DataExchange')->convert($this->voToXml, $this->xmlToCsv);
    }

    public function getVo2xmlMapping()
    {
        return $this->vo2xmlMapping;
    }

    public function setVo2xmlMapping($mapping = '')
    {
        $this->vo2xmlMapping = $mapping;
    }

    public function getXml2csvMapping()
    {
        return $this->xml2csvMapping;
    }

    public function setXml2csvMapping($mapping = '')
    {
        $this->xml2csvMapping = $mapping;
    }

    public function getOutputDelimiter()
    {
        return $this->delimiter;
    }
}



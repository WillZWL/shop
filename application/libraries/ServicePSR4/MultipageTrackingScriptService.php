<?php
namespace ESG\Panther\Service;

class MultipageTrackingScriptService extends BaseService
{
    private $country_id = "";

    public function getCountryId()
    {
        if ($this->country_id == "") {
            die("country ID not set for Multipage_tracking_script_service");
        }

        return $this->country_id;
    }

    public function setCountryId($value)
    {
        $this->country_id = $value;
    }

    public function jsonEncodeNoQuote($array)
    {
        $ret_code = "";

        foreach ($array as $key => $value) {
            $ret_code .= "$key:\"$value\",\r\n";
        }

        $ret_code = trim($ret_code, ",");
        return "{" . $ret_code . "}";
    }

    public function getFixedCode()
    {
    }

    public function getVariableCode($page_type, $param)
    {
    }
}

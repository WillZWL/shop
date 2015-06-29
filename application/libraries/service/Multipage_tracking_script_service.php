<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once "Base_service.php";

class Multipage_tracking_script_service extends Base_service
{
    private $country_id = "";

    public function get_country_id()
    {
        if ($this->country_id == "") {
            die("country ID not set for Multipage_tracking_script_service");
        }

        return $this->country_id;
    }

    public function set_country_id($value)
    {
        $this->country_id = $value;
    }

    public function json_encode_no_quote($array)
    {
        $ret_code = "";

        foreach ($array as $key => $value) {
            $ret_code .= "$key:\"$value\",\r\n";
        }

        $ret_code = trim($ret_code, ",");
        return "{" . $ret_code . "}";
    }

    public function get_fixed_code()
    {
    }

    public function get_variable_code($page_type, $param)
    {
    }
}

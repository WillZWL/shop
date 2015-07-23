<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once "Base_service.php";

class Domain_platform_service extends Base_service
{

    private $default_platform_id;

    public function __construct()
    {
        parent::__construct();
        include_once(APPPATH . "libraries/dao/Domain_platform_dao.php");
        $this->set_dao(new Domain_platform_dao());
        include_once(APPPATH . "libraries/service/Context_config_service.php");
        $this->set_config(new Context_config_service());
        $this->default_platform_id = $this->get_config()->value_of("default_platform_id");
    }

    public function set_config($value)
    {
        $this->config = $value;
    }

    public function get_config()
    {
        return $this->config;
    }

    public function get_domain_platform_w_lang($type = "object")
    {
        return $this->get_dao()->get_domain_platform_w_lang($type);
    }

    public function get_domain_platform_w_lang_override($platform_type, $result_type = "object")
    {
        return $this->get_dao()->get_domain_platform_w_lang_override($platform_type, $result_type);
    }

    public function get_country_platform_domain(&$country_id, $domain_type)
    {
        if (!$country_id || $country_id == "ZZ") {
            $default_dp = $this->get_by_default_platform_id($domain_type);
            $country_id = $default_dp["platform_country_id"];
            return array("domain" => $default_dp["domain"], "currency" => $default_dp["platform_currency_id"]);
        } else {
            $country_dp = $this->get_by_country_id($country_id, $_SESSION["domain_platform"]["type"], $domain_type, "array");

            if (!$country_dp || $country_dp["language_id"] != "en") {
                $default_dp = $this->get_by_default_platform_id($domain_type);
                $country_id = $default_dp["platform_country_id"];
                return array("domain" => $default_dp["domain"], "currency" => $default_dp["platform_currency_id"]);
            } else {
                return array("domain" => $country_dp["domain"], "currency" => $country_dp["platform_currency_id"]);
            }
        }
    }

    public function get_by_default_platform_id($domain_type)
    {
        return $this->get_by_platform_id($this->default_platform_id, $domain_type);
    }

    public function get_by_platform_id($platform_id, $domain_type)
    {
        return $this->get_dao()->get_by_platform_id($platform_id, $domain_type, "array");
    }

    public function get_by_country_id($country_id, $platform_type, $domain_type, $result_type = "object")
    {
        return $this->get_dao()->get_by_country_id($country_id, $platform_type, $domain_type, $result_type);
    }
}



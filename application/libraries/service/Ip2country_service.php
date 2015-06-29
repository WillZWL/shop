<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

include_once "Base_service.php";

class Ip2country_service extends Base_service
{

    private $http;
    private $http_info;

    function __construct()
    {
        parent::__construct();
        include_once(APPPATH . "libraries/service/Context_config_service.php");
        $this->set_config_srv(new Context_config_service());
        include_once(APPPATH . "libraries/service/Http_connector.php");
        $this->set_http(new Http_connector());
        include_once(APPPATH . "libraries/service/Http_info_service.php");
        $this->set_http_info(new Http_info_service());
    }

    public function set_config_srv(Base_service $srv)
    {
        $this->config_srv = $srv;
    }

    public function get_info_by_ip($ip)
    {
        $ip2country_tool = $this->get_config_srv()->value_of("ip2country_provider");
        if (!is_null($ip2country_tool)) {
            if ($http_obj = $this->get_http_info()->get(array("name" => $ip2country_tool, "type" => "P"))) {
                $http = $this->get_http();
                $http->set_remote_site($http_obj->get_server() . $ip);
                $http->get_hcs()->set_timeout(5);

                if ($rs = $http->get_content()) {
                    list($ip, $country) = explode("||", $rs);

                    switch ($ip2country_tool) {
                        case 'maxmind' :
                            if ($country == '') $country = 'ZZ';
                            break;
                    }

                    return array("ip" => $ip, "country_id" => strtoupper(trim($country)));
                } else {
                    return FALSE;
                }
            }
            return FALSE;
        }
        return FALSE;
    }

    public function get_config_srv()
    {
        return $this->config_srv;
    }

    public function get_http_info()
    {
        return $this->http_info;
    }

    public function set_http_info($value)
    {
        $this->http_info = $value;
    }

    public function get_http()
    {
        return $this->http;
    }

    public function set_http($value)
    {
        $this->http = $value;
    }
}



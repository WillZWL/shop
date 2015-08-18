<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

include_once "Base_service.php";

class Quova_service extends Base_service
{

    private $http;
    private $http_info;

    function __construct()
    {
        parent::__construct();
        include_once(APPPATH . "libraries/service/Http_connector.php");
        $this->set_http(new Http_connector());
        include_once(APPPATH . "libraries/service/Http_info_service.php");
        $this->set_http_info(new Http_info_service());
    }

    public function get_info_by_ip($ip)
    {

        if ($http_obj = $this->get_http_info()->get(array("name" => 'quova', "type" => "P"))) {

            $http = $this->get_http();
            $http->set_remote_site($http_obj->get_server() . $ip);
            $http->get_hcs()->set_timeout(5);

            if ($rs = $http->get_content()) {
                list($ip, $country) = explode("||", $rs);
                return array("ip" => $ip, "country_id" => strtoupper(trim($country)));
            } else {
                return FALSE;
            }
        }
        return FALSE;
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



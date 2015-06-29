<?php if (! defined('BASEPATH')) exit('No direct script access allowed');

include_once "Base_service.php";

class Ipligence_service extends Base_service
{

    function __construct()
    {
        parent::__construct();
        include_once(APPPATH."libraries/dao/Ipligence_dao.php");
        $this->set_dao(new Ipligence_dao());
    }

    public function get_info_by_ip($ip)
    {
        $ia_ip = sprintf("%u", ip2long($ip));
        $where["ip_from <= "] = $ia_ip;
        $where["ip_to >= "] = $ia_ip;
        return $this->get($where);
    }

    public function get_http()
    {
        return $this->http;
    }

    public function set_http($value)
    {
        $this->http = $value;
    }

    public function get_http_info()
    {
        return $this->http_info;
    }

    public function set_http_info($value)
    {
        $this->http_info = $value;
    }

    public function get_config()
    {
        return $this->config;
    }

    public function set_config($value)
    {
        $this->config = $value;
    }

}

/* End of file ipligence_service.php */
/* Location: ./system/application/libraries/service/Ipligence_service.php */
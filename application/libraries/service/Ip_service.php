<?php if (! defined('BASEPATH')) exit('No direct script access allowed');

include_once "Base_service.php";

class Ip_service extends Base_service
{

	private $ip2country_srv;
	private $ipligence_srv;
	private $config;

	function __construct()
	{
		parent::__construct();
		include_once(APPPATH."libraries/service/Ip2country_service.php");
		$this->set_ip2country_srv(new Ip2country_service());
		include_once(APPPATH."libraries/service/Ipligence_service.php");
		$this->set_ipligence_srv(new Ipligence_service());
	}

	public function get_country_id_by_ip($ip)
	{
		$ip2country = $this->get_ip2country_srv()->get_info_by_ip($ip);

		if ($ip2country == FALSE || $ip2country["country_id"] == "ZZ")
		{
			if ($obj = $this->get_ipligence_srv()->get_info_by_ip($ip))
			{
				return $obj->get_country_code();
			}

			return FALSE;
		}
		else
		{
			return $ip2country["country_id"];
		}
	}

	public function get_ip2country_srv()
	{
		return $this->ip2country_srv;
	}

	public function set_ip2country_srv($value)
	{
		$this->ip2country_srv = $value;
	}

	public function get_ipligence_srv()
	{
		return $this->ipligence_srv;
	}

	public function set_ipligence_srv($value)
	{
		$this->ipligence_srv = $value;
	}
}

/* End of file ip_service.php */
/* Location: ./system/application/libraries/service/Ip_service.php */
<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use GeoIp2\Database\Reader;

class Country_selection
{
	private $sub_domain = '';
	private $base_domain = '';
	private $domain_suffix = '';
	private $country_code = '';
	private $lang = '';

	public function __construct()
	{
		$this->set_domain_component();
		// var_dump($_SERVER);die;
	}

	public function set_domain_component()
	{
		$this->base_domain = config_item('base_domain');

		if ($this->base_domain) {
			$pos = stripos($_SERVER['HTTP_HOST'], $this->base_domain);
			if ($pos !== FALSE) {
				$this->sub_domain = substr($_SERVER['HTTP_HOST'], 0, $pos);
				$this->domain_suffix = substr($_SERVER['HTTP_HOST'], $pos+strlen($this->base_domain));
			}
		} else {
			// TODO
			// not set base domain
		}
	}


	public function get_country_code()
	{
		if (isset($_COOKIE['country_code'])) {
			$country_code = $_COOKIE['country_code'];
		} else {
			$country_code = $this->get_country_code_by_ip2country_provider();
		}

		return $country_code;
	}

	public function get_country_code_by_ip2country_provider($ip = '')
	{
		$country_code = '';

		try {
			$reader = new Reader(config_item('maxmind_db_path'));

			$ip = $ip ? $ip : $_SERVER['REMOTE_ADDR'];
			$record = $reader->city($ip);
			$country_code = $record->country->isoCode;
			$reader->close();
		} catch (Exception $e) {
			// IP not in database
		}

		if (empty($country_code)) {
			$country_code = 'SG';
		}
		$_SESSION['country_code_from_hook'] = $country_code;

		return $country_code;
	}

	public function get_rewrite_domain_by_country()
	{
		$country_code = strtoupper($this->get_country_code());

		switch ($country_code) {
			case 'AU':
				$this->domain_suffix = '.com.au';
				break;
			case 'NZ':
				$this->domain_suffix = '.co.nz';
				break;
			case 'SG':
				$this->domain_suffix = '.com.sg';
				break;
			case 'FR':
				$this->domain_suffix = '.fr';
				break;
			case 'MX':
				$this->domain_suffix = '.com.mx';
				break;
			case 'NL':
				$this->domain_suffix = '.nl';
				break;
			case 'PH':
				$this->domain_suffix = '.com.ph';
				break;
			case 'BE':
				$this->domain_suffix = '.be';
				break;
			case 'IT':
				$this->domain_suffix = '.it';
				break;
			case 'RU':
				$this->domain_suffix = '.ru';
				break;
			case 'PT':
				$this->domain_suffix = '.pt';
				break;
			case 'PL':
				$this->domain_suffix = '.pl';
				break;

			default:
				$this->domain_suffix = '.com';
				break;
		}

		return $this->sub_domain . $this->base_domain . $this->domain_suffix;
	}

	public function redirect_url()
	{
		$protocol = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == "on") ? "https://" : "http://";

		$current_domain = $_SERVER['HTTP_HOST'];
		$rewrite_domain = $this->get_rewrite_domain_by_country();

		if ($current_domain != $rewrite_domain) {
			header("Location: ". $protocol.$rewrite_domain.$_SERVER['REQUEST_URI']);
			exit;
		}
	}

	public function validate_country_code($country_code)
	{
		if ( ! preg_match("/^[A-Za-z]{2}$/", $country_code)) {
			return FALSE;
		} else {
			return $country_code;
		}
	}

}

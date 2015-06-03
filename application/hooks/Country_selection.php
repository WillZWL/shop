<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use GeoIp2\Database\Reader;

class Country_selection
{
	private $country_code = '';
	private $lang = '';

	public function __construct()
	{
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

	public function get_country_code_by_ip2country_provider()
	{
		$country_code = '';

		$reader = new Reader('/vagrant_data/GeoLite2-City.mmdb');
		try {
			$record = $reader->city('192.168.0.1');
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
				$domain = 'vb.com.au';
				break;
			case 'NZ':
				$domain = 'vb.co.nz';
				break;
			case 'SG':
				$domain = 'vb.com.sg';
				break;
			case 'FR':
				$domain = 'vb.fr';
				break;
			case 'MX':
				$domain = 'vb.com.mx';
				break;
			case 'NL':
				$domain = 'vb.nl';
				break;
			case 'PH':
				$domain = 'vb.com.ph';
				break;
			case 'BE':
				$domain = 'vb.be';
				break;
			case 'IT':
				$domain = 'vb.it';
				break;
			case 'RU':
				$domain = 'vb.ru';
				break;
			case 'PT':
				$domain = 'vb.pt';
				break;
			case 'PL':
				$domain = 'vb.pl';
				break;

			default:
				$domain = 'vb.com';
				break;
		}

		return $domain;
	}

	public function redirect_domain()
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

	public function get_domain_suffix()
	{
		$pos = strrpos($_SERVER['HTTP_HOST'], '.');
		if ($pos === FALSE) {
			$domain_suffix = '.com';
		} else {
			$domain_suffix = substr($_SERVER['HTTP_HOST'], $pos);
		}

		return $domain_suffix;
	}
}
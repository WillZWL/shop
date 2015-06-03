<?php
include_once 'Base_dto.php';

class Domain_platform_w_lang_dto extends Base_dto
{

	//class variable
	private $domain;
	private $platform_id;
	private $site_name;
	private $short_name;
	private $domain_type;
	private $status = '1';
	private $platform_country_id;
	private $language_id;
	private $platform_currency_id;
	private $type;
	private $create_on = '0000-00-00 00:00:00';
	private $create_at = '127.0.0.1';
	private $create_by;
	private $modify_on;
	private $modify_at = '127.0.0.1';
	private $modify_by;

	//instance method
	public function get_domain()
	{
		return $this->domain;
	}

	public function set_domain($value)
	{
		$this->domain = $value;
		return $this;
	}

	public function get_platform_id()
	{
		return $this->platform_id;
	}

	public function set_platform_id($value)
	{
		$this->platform_id = $value;
		return $this;
	}

	public function get_site_name()
	{
		return $this->site_name;
	}

	public function set_site_name($value)
	{
		$this->site_name = $value;
		return $this;
	}

	public function get_short_name()
	{
		return $this->short_name;
	}

	public function set_short_name($value)
	{
		$this->short_name = $value;
		return $this;
	}

	public function get_domain_type()
	{
		return $this->domain_type;
	}

	public function set_domain_type($value)
	{
		$this->domain_type = $value;
		return $this;
	}

	public function get_status()
	{
		return $this->status;
	}

	public function set_status($value)
	{
		$this->status = $value;
		return $this;
	}

	public function get_platform_country_id()
	{
		return $this->platform_country_id;
	}

	public function set_platform_country_id($value)
	{
		$this->platform_country_id = $value;
		return $this;
	}

	public function get_language_id()
	{
		return $this->language_id;
	}

	public function set_language_id($value)
	{
		$this->language_id = $value;
		return $this;
	}

	public function get_platform_currency_id()
	{
		return $this->platform_currency_id;
	}

	public function set_platform_currency_id($value)
	{
		$this->platform_currency_id = $value;
		return $this;
	}

	public function get_type()
	{
		return $this->type;
	}

	public function set_type($value)
	{
		$this->type = $value;
		return $this;
	}

	public function get_create_on()
	{
		return $this->create_on;
	}

	public function set_create_on($value)
	{
		$this->create_on = $value;
		return $this;
	}

	public function get_create_at()
	{
		return $this->create_at;
	}

	public function set_create_at($value)
	{
		$this->create_at = $value;
		return $this;
	}

	public function get_create_by()
	{
		return $this->create_by;
	}

	public function set_create_by($value)
	{
		$this->create_by = $value;
		return $this;
	}

	public function get_modify_on()
	{
		return $this->modify_on;
	}

	public function set_modify_on($value)
	{
		$this->modify_on = $value;
		return $this;
	}

	public function get_modify_at()
	{
		return $this->modify_at;
	}

	public function set_modify_at($value)
	{
		$this->modify_at = $value;
		return $this;
	}

	public function get_modify_by()
	{
		return $this->modify_by;
	}

	public function set_modify_by($value)
	{
		$this->modify_by = $value;
		return $this;
	}

}
/* End of file domain_platform_w_lang_dto.php */
/* Location: ./system/application/libraries/dto/domain_platform_w_lang_dto.php */
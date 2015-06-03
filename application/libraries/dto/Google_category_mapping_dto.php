<?php
include_once 'Base_dto.php';

class Google_category_mapping_dto extends Base_dto
{
	//class variable
	private $category_id;
	private $name;
	private $ext_id;
	private $country_id;
	private $google_category_name;
	private $main_category;

	public function __construct()
	{
		parent::__construct();
	}

	//instance method
	public function get_category_id()
	{
		return $this->category_id;
	}

	public function set_category_id($value)
	{
		$this->category_id = $value;
	}

	public function get_name()
	{
		return $this->name;
	}

	public function set_name($value)
	{
		$this->name = $value;
	}

	public function get_ext_id()
	{
		return $this->ext_id;
	}

	public function set_ext_id($value)
	{
		$this->ext_id = $value;
	}

	public function get_country_id()
	{
		return $this->country_id;
	}

	public function set_country_id($value)
	{
		$this->country_id = $value;
	}

	public function get_google_category_name()
	{
		return $this->google_category_name;
	}

	public function set_google_category_name($value)
	{
		$this->google_category_name = $value;
	}

	public function get_main_category()
	{
		return $this->main_category;
	}

	public function set_main_category($value)
	{
		$this->main_category = $value;
	}


}
?>
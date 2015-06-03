<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once "Base_dto.php";

class Display_video_list_dto extends Base_dto {

	//class variable
	private $id;
	private $sku;
	private $name;
	private $country_id;
	private $lang_id;
	private $type;
	private $src;
	private $ref_id;
	private $description;
	private $view_count;
	private $status;
	private $create_on;
	private $create_at;
	private $create_by;
	private $modify_on;
	private $modify_at;
	private $modify_by;
	private $cat_name;
	private $sub_cat_name;
	private $brand_name;
	private $count;

	//instance method
	public function get_id()
	{
		return $this->id;
	}

	public function set_id($value)
	{
		$this->id = $value;
	}

	public function get_sku()
	{
		return $this->sku;
	}

	public function set_sku($value)
	{
		$this->sku = $value;
	}

	public function get_name()
	{
		return $this->name;
	}

	public function set_name($value)
	{
		$this->name = $value;
	}

	public function get_country_id()
	{
		return $this->country_id;
	}

	public function set_country_id($value)
	{
		$this->country_id = $value;
	}

	public function get_lang_id()
	{
		return $this->lang_id;
	}

	public function set_lang_id($value)
	{
		$this->lang_id = $value;
	}

	public function get_type()
	{
		return $this->type;
	}

	public function set_type($value)
	{
		$this->type = $value;
	}

	public function get_src()
	{
		return $this->src;
	}

	public function set_src($value)
	{
		$this->src = $value;
	}

	public function get_ref_id()
	{
		return $this->ref_id;
	}

	public function set_ref_id($value)
	{
		$this->ref_id = $value;
	}

	public function get_description()
	{
		return $this->description;
	}

	public function set_description($value)
	{
		$this->description = $value;
	}

	public function get_view_count()
	{
		return $this->view_count;
	}

	public function set_view_count($value)
	{
		$this->view_count = $value;
	}

	public function get_status()
	{
		return $this->status;
	}

	public function set_status($value)
	{
		$this->status = $value;
	}

	public function get_create_on()
	{
		return $this->create_on;
	}

	public function set_create_on($value)
	{
		$this->create_on = $value;
	}

	public function get_create_at()
	{
		return $this->create_at;
	}

	public function set_create_at($value)
	{
		$this->create_at = $value;
	}

	public function get_create_by()
	{
		return $this->create_by;
	}

	public function set_create_by($value)
	{
		$this->create_by = $value;
	}

	public function get_modify_on()
	{
		return $this->modify_on;
	}

	public function set_modify_on($value)
	{
		$this->modify_on = $value;
	}

	public function get_modify_at()
	{
		return $this->modify_at;
	}

	public function set_modify_at($value)
	{
		$this->modify_at = $value;
	}

	public function get_modify_by()
	{
		return $this->modify_by;
	}

	public function set_modify_by($value)
	{
		$this->modify_by = $value;
	}

	public function get_cat_id()
	{
		return $this->cat_id;
	}

	public function set_cat_id($value)
	{
		$this->cat_id = $value;
	}

	public function get_cat_name()
	{
		return $this->cat_name;
	}

	public function set_cat_name($value)
	{
		$this->cat_name = $value;
	}

	public function get_sub_cat_name()
	{
		return $this->sub_cat_name;
	}

	public function set_sub_cat_name($value)
	{
		$this->sub_cat_name = $value;
	}

	public function get_sub_cat_id()
	{
		return $this->sub_cat_id;
	}

	public function set_sub_cat_id($value)
	{
		$this->sub_cat_id = $value;
	}

	public function get_brand_name()
	{
		return $this->brand_name;
	}

	public function set_brand_name($value)
	{
		$this->brand_name = $value;
	}

	public function get_count()
	{
		return $this->count;
	}

	public function set_count($value)
	{
		$this->count = $value;
	}

}

/* End of file display_video_list_dto.php */
/* Location: ./system/application/libraries/dto/display_video_list_dto.php */
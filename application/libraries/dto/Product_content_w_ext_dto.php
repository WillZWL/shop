<?php
include_once 'Base_dto.php';

class Product_content_w_ext_dto extends Base_dto
{

	//class variable
	private $prod_sku;
	private $lang_id;
	private $prod_name;
	private $short_desc;
	private $contents;
	private $keywords;
	private $detail_desc;
	private $extra_info;
	private $feature;
	private $specification;
	private $requirement;
	private $create_on = '0000-00-00 00:00:00';
	private $create_at = '127.0.0.1';
	private $create_by;
	private $modify_on;
	private $modify_at = '127.0.0.1';
	private $modify_by;

	//instance method
	public function get_prod_sku()
	{
		return $this->prod_sku;
	}

	public function set_prod_sku($value)
	{
		$this->prod_sku = $value;
		return $this;
	}

	public function get_lang_id()
	{
		return $this->lang_id;
	}

	public function set_lang_id($value)
	{
		$this->lang_id = $value;
		return $this;
	}

	public function get_prod_name()
	{
		return $this->prod_name;
	}

	public function set_prod_name($value)
	{
		$this->prod_name = $value;
		return $this;
	}

	public function get_short_desc()
	{
		return $this->short_desc;
	}

	public function set_short_desc($value)
	{
		$this->short_desc = $value;
		return $this;
	}

	public function get_contents()
	{
		return $this->contents;
	}

	public function set_contents($value)
	{
		$this->contents = $value;
		return $this;
	}

	public function get_keywords()
	{
		return $this->keywords;
	}

	public function set_keywords($value)
	{
		$this->keywords = $value;
		return $this;
	}

	public function get_detail_desc()
	{
		return $this->detail_desc;
	}

	public function set_detail_desc($value)
	{
		$this->detail_desc = $value;
		return $this;
	}

	public function get_extra_info()
	{
		return $this->extra_info;
	}

	public function set_extra_info($value)
	{
		$this->extra_info = $value;
		return $this;
	}

	public function get_feature()
	{
		return $this->feature;
	}

	public function set_feature($value)
	{
		$this->feature = $value;
		return $this;
	}

	public function get_specification()
	{
		return $this->specification;
	}

	public function set_specification($value)
	{
		$this->specification = $value;
		return $this;
	}

	public function get_requirement()
	{
		return $this->requirement;
	}

	public function set_requirement($value)
	{
		$this->requirement = $value;
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

/* End of file product_content_w_ext_dto.php */
/* Location: ./system/application/libraries/dto/product_content_w_ext_dto.php */
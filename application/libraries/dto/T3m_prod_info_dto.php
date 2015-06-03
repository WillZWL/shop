<?php

include_once "Base_dto.php";

class T3m_prod_info_dto extends Base_dto
{
	private $sku;
	private $cat_name;
	private $sub_cat_name;
	private $sub_sub_cat_name;
	private $cat_id;
	private $sub_cat_id;
	private $sub_sub_cat_id;
	private $name;

	public function __construct()
	{
		parent::__construct();
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

	public function get_cat_name()
	{
		return $this->cat_name;
	}

	public function set_cat_name($value)
	{
		$this->cat_name = $value;
	}

	public function get_cat_id()
	{
		return $this->cat_id;
	}

	public function set_cat_id($value)
	{
		$this->cat_id = $value;
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

	public function get_sub_sub_cat_name()
	{
		return $this->sub_sub_cat_name;
	}

	public function set_sub_sub_cat_name($value)
	{
		$this->sub_sub_cat_name = $value;
	}

	public function get_sub_sub_cat_id()
	{
		return $this->sub_sub_cat_id;
	}

	public function set_sub_sub_cat_id($value)
	{
		$this->sub_sub_cat_id = $value;
	}
}

?>
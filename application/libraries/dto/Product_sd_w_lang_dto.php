<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once "Base_dto.php";

class Product_sd_w_lang_dto extends Base_dto
{

	//class variable
	private $psg_name;
	private $ps_id;
	private $cat_id;
	private $ps_name;
	private $unit_id;
	private $prod_sku;
	private $text;
	private $start_value;
	private $start_standardize_value;
	private $end_value;
	private $end_standardize_value;


	public function get_psg_name()
	{
		return $this->psg_name;
	}

	public function set_psg_name($value)
	{
		$this->psg_name = $value;
	}

	public function get_ps_id()
	{
		return $this->ps_id;
	}

	public function set_ps_id($value)
	{
		$this->ps_id = $value;
	}

	public function get_cat_id()
	{
		return $this->cat_id;
	}

	public function set_cat_id($value)
	{
		$this->cat_id = $value;
	}

	public function get_ps_name()
	{
		return $this->ps_name;
	}

	public function set_ps_name($value)
	{
		$this->ps_name = $value;
	}

	public function get_unit_id()
	{
		return $this->unit_id;
	}

	public function set_unit_id($value)
	{
		$this->unit_id = $value;
	}

	public function get_prod_sku()
	{
		return $this->prod_sku;
	}

	public function set_prod_sku($value)
	{
		$this->prod_sku = $value;
	}

	public function get_text()
	{
		return $this->text;
	}

	public function set_text($value)
	{
		$this->text = $value;
	}

	public function get_start_value()
	{
		return $this->start_value;
	}

	public function set_start_value($value)
	{
		$this->start_value = $value;
	}

	public function get_start_standardize_value()
	{
		return $this->start_standardize_value;
	}

	public function set_start_standardize_value($value)
	{
		$this->start_standardize_value = $value;
	}

	public function get_end_value()
	{
		return $this->end_value;
	}

	public function set_end_value($value)
	{
		$this->end_value = $value;
	}

	public function get_end_standardize_value()
	{
		return $this->end_standardize_value;
	}

	public function set_end_standardize_value($value)
	{
		$this->end_standardize_value = $value;
	}
}

/* End of file product_spec_with_sku_dto.php */
/* Location: ./system/application/libraries/dto/product_spec_with_sku_dto.php */
<?php defined('BASEPATH') OR exit('No direct script access allowed');

include_once "product_cost_dto.php";

class Product_cost_change_dto extends Product_cost_dto
{
	private $cost_diff;
	private $pcent_chg;
	private $note;
	private $is_new;

	public function __construct()
	{
		parent::Product_cost_dto();
	}

	public function get_cost_diff()
	{
		return $this->cost_diff;
	}

	public function set_cost_diff($value)
	{
		$this->cost_diff = $value;
	}

	public function get_pcent_chg()
	{
		return $this->pcent_chg;
	}

	public function set_pcent_chg($value)
	{
		$this->pcent_chg = $value;
	}

	public function get_note()
	{
		return $this->note;
	}

	public function set_note($value)
	{
		$this->note = $value;
	}

	public function get_is_new()
	{
		return $this->is_new;
	}

	public function set_is_new($value)
	{
		$this->is_new = $value;
	}
}

/* End of file product_cost_change_dto.php */
/* Location: ./system/application/libraries/dto/product_cost_change_dto.php */
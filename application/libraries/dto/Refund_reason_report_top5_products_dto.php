<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once "Base_dto.php";

class Refund_reason_report_top5_products_dto extends Base_dto
{
	private $item_sku;
	private $item_name;
	private $frequency;

	public function __construct()
	{
		parent::__construct();
	}

	public function get_item_sku()
	{
		return $this->item_sku;
	}

	public function set_item_sku($value)
	{
		$this->item_sku = $value;
	}

	public function get_item_name()
	{
		return $this->item_name;
	}

	public function set_item_name($value)
	{
		$this->item_name = $value;
	}

	public function get_frequency()
	{
		return $this->frequency;
	}

	public function set_frequency($value)
	{
		$this->frequency = $value;
	}

}

?>
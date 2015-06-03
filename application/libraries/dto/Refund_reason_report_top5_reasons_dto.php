<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once "Base_dto.php";

class Refund_reason_report_top5_reasons_dto extends Base_dto
{
	private $id;
	private $reason;
	private $frequency;

	public function __construct()
	{
		parent::__construct();
	}

	public function get_id()
	{
		return $this->id;
	}

	public function set_id($value)
	{
		$this->id = $value;
	}

	public function get_reason()
	{
		return $this->reason;
	}

	public function set_reason($value)
	{
		$this->reason = $value;
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
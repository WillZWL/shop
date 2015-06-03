<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once "Base_dto.php";

class Import_info_dto extends Base_dto
{
	protected $trans_id;
	protected $batch_id;
	protected $status;
	protected $failed_reason;
	protected $has_error = false;
	protected $column;
	protected $error_code;
	protected $error_message;

	public function get_trans_id()
	{
		return $this->trans_id;
	}

	public function set_trans_id($value)
	{
		$this->trans_id = $value;
	}

	public function get_batch_id()
	{
		return $this->batch_id;
	}

	public function set_batch_id($value)
	{
		$this->batch_id = $value;
	}

	public function get_status()
	{
		return $this->status;
	}

	public function set_status($value)
	{
		$this->status = $value;
	}

	public function get_failed_reason()
	{
		return $this->failed_reason;
	}

	public function set_failed_reason($value)
	{
		$this->failed_reason = $value;
	}

	public function get_has_error()
	{
		return $this->has_error;
	}

	public function set_has_error($value)
	{
		$this->has_error = $value;
	}

	public function get_column()
	{
		return $this->column;
	}

	public function set_column($value)
	{
		$this->column = $value;
	}

	public function get_error_code()
	{
		return $this->error_code;
	}

	public function set_error_code($value)
	{
		$this->error_code = $value;
	}

	public function get_error_message()
	{
		return $this->error_message;
	}

	public function set_error_message($value)
	{
		$this->error_message = $value;
	}
}

<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once "Base_dto.php";

class Banner_cat_list_dto extends Base_dto {

	//class variable
	private $id;
	private $name;
	private $level;
	private $pv_cnt;
	private $pb_cnt;
	private $status;
	private $num_of_record;

	//instance method

	public function get_id()
	{
		return $this->id;
	}

	public function set_id($value)
	{
		$this->id = $value;
	}

	public function get_name()
	{
		return $this->name;
	}

	public function set_name($value)
	{
		$this->name = $value;
	}

	public function get_level()
	{
		return $this->level;
	}

	public function set_level($value)
	{
		$this->level = $value;
	}

	public function get_pv_cnt()
	{
		return $this->pv_cnt;
	}

	public function set_pv_cnt($value)
	{
		$this->pv_cnt = $value;
	}

	public function get_pb_cnt()
	{
		return $this->pb_cnt;
	}

	public function set_pb_cnt($value)
	{
		$this->pb_cnt = $value;
	}

	public function get_status()
	{
		return $this->status;
	}

	public function set_status($value)
	{
		$this->status = $value;
	}

	public function get_count_row()
	{
		return $this->count_row;
	}

	public function set_count_row($value)
	{
		$this->count_row = $value;
	}
}

/* End of file brand_w_region_dto.php */
/* Location: ./system/application/libraries/dto/adbanner_category_dto.php */
<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once "Base_dto.php";

class Full_cps_with_cat_id_dto extends Base_dto
{

	//class variable
	private $psg_id;
	private $psg_name;
	private $ps_func_id;
	private $ps_name;
	private $unit_type_id;
	private $unit_type_name;
	private $cat_id;
	private $unit_id;
	private $unit_name;
	private $priority;
	private $status;

	public function get_psg_id()
	{
		return $this->psg_id;
	}

	public function set_psg_id($value)
	{
		$this->psg_id = $value;
	}

	public function get_psg_name()
	{
		return $this->psg_name;
	}

	public function set_psg_name($value)
	{
		$this->psg_name = $value;
	}

	public function get_ps_func_id()
	{
		return $this->ps_func_id;
	}

	public function set_ps_func_id($value)
	{
		$this->ps_func_id = $value;
	}

	public function get_ps_name()
	{
		return $this->ps_name;
	}

	public function set_ps_name($value)
	{
		$this->ps_name = $value;
	}

	public function get_unit_type_id()
	{
		return $this->unit_type_id;
	}

	public function set_unit_type_id($value)
	{
		$this->unit_type_id = $value;
	}

	public function get_unit_type_name()
	{
		return $this->unit_type_name;
	}

	public function set_unit_type_name($value)
	{
		$this->unit_type_name = $value;
	}

	public function get_cat_id()
	{
		return $this->cat_id;
	}

	public function set_cat_id($value)
	{
		$this->cat_id = $value;
	}

	public function get_unit_id()
	{
		return $this->unit_id;
	}

	public function set_unit_id($value)
	{
		$this->unit_id = $value;
	}

	public function get_unit_name()
	{
		return $this->unit_name;
	}

	public function set_unit_name($value)
	{
		$this->unit_name = $value;
	}

	public function get_priority()
	{
		return $this->priority;
	}

	public function set_priority($value)
	{
		$this->priority = $value;
	}

	public function get_status()
	{
		return $this->status;
	}

	public function set_status($value)
	{
		$this->status = $value;
	}
}

/* End of file full_cps_with_cat_id_dto.php */
/* Location: ./system/application/libraries/dto/full_cps_with_cat_id_dto.php */
<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once "Base_dto.php";

class Role_access_dto extends Base_dto {

	//class variable
	private $rights_id;
	private $rights;

	function __construct(){
		parent::__construct();
	}

	//instance method
	public function get_rights_id()
	{
		return $this->rights_id;
	}

	public function set_rights_id($value)
	{
		$this->rights_id = $value;
	}

	public function get_rights()
	{
		return $this->rights;
	}

	public function set_rights($value)
	{
		$this->rights = $value;
	}
}

/* End of file role_access_dto.php */
/* Location: ./system/application/libraries/dto/role_access_dto.php */
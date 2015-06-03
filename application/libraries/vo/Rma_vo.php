<?php
include_once 'Base_vo.php';

class Rma_vo extends Base_vo
{

	//class variable
	private $id;
	private $so_no;
	private $client_id;
	private $forename;
	private $surname;
	private $address_1;
	private $address_2;
	private $postcode;
	private $city;
	private $state;
	private $country_id;
	private $product_returned;
	private $category;
	private $serial_no;
	private $components;
	private $reason;
	private $action_request = '0';
	private $details;
	private $shipfrom;
	private $status = '0';
	private $create_on = '0000-00-00 00:00:00';
	private $create_at = '127.0.0.1';
	private $create_by;
	private $modify_on;
	private $modify_at = '127.0.0.1';
	private $modify_by;

	//primary key
	private $primary_key = array("id");

	//auo increment
	private $increment_field = "id";

	//instance method
	public function get_id()
	{
		return $this->id;
	}

	public function set_id($value)
	{
		$this->id = $value;
		return $this;
	}

	public function get_so_no()
	{
		return $this->so_no;
	}

	public function set_so_no($value)
	{
		$this->so_no = $value;
		return $this;
	}

	public function get_client_id()
	{
		return $this->client_id;
	}

	public function set_client_id($value)
	{
		$this->client_id = $value;
		return $this;
	}

	public function get_forename()
	{
		return $this->forename;
	}

	public function set_forename($value)
	{
		$this->forename = $value;
		return $this;
	}

	public function get_surname()
	{
		return $this->surname;
	}

	public function set_surname($value)
	{
		$this->surname = $value;
		return $this;
	}

	public function get_address_1()
	{
		return $this->address_1;
	}

	public function set_address_1($value)
	{
		$this->address_1 = $value;
		return $this;
	}

	public function get_address_2()
	{
		return $this->address_2;
	}

	public function set_address_2($value)
	{
		$this->address_2 = $value;
		return $this;
	}

	public function get_postcode()
	{
		return $this->postcode;
	}

	public function set_postcode($value)
	{
		$this->postcode = $value;
		return $this;
	}

	public function get_city()
	{
		return $this->city;
	}

	public function set_city($value)
	{
		$this->city = $value;
		return $this;
	}

	public function get_state()
	{
		return $this->state;
	}

	public function set_state($value)
	{
		$this->state = $value;
		return $this;
	}

	public function get_country_id()
	{
		return $this->country_id;
	}

	public function set_country_id($value)
	{
		$this->country_id = $value;
		return $this;
	}

	public function get_product_returned()
	{
		return $this->product_returned;
	}

	public function set_product_returned($value)
	{
		$this->product_returned = $value;
		return $this;
	}

	public function get_category()
	{
		return $this->category;
	}

	public function set_category($value)
	{
		$this->category = $value;
		return $this;
	}

	public function get_serial_no()
	{
		return $this->serial_no;
	}

	public function set_serial_no($value)
	{
		$this->serial_no = $value;
		return $this;
	}

	public function get_components()
	{
		return $this->components;
	}

	public function set_components($value)
	{
		$this->components = $value;
		return $this;
	}

	public function get_reason()
	{
		return $this->reason;
	}

	public function set_reason($value)
	{
		$this->reason = $value;
		return $this;
	}

	public function get_action_request()
	{
		return $this->action_request;
	}

	public function set_action_request($value)
	{
		$this->action_request = $value;
		return $this;
	}

	public function get_details()
	{
		return $this->details;
	}

	public function set_details($value)
	{
		$this->details = $value;
		return $this;
	}

	public function get_shipfrom()
	{
		return $this->shipfrom;
	}

	public function set_shipfrom($value)
	{
		$this->shipfrom = $value;
		return $this;
	}

	public function get_status()
	{
		return $this->status;
	}

	public function set_status($value)
	{
		$this->status = $value;
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

	public function _get_primary_key()
	{
		return $this->primary_key;
	}

	public function _get_increment_field()
	{
		return $this->increment_field;
	}

}
?>
<?php
include_once "base_vo.php";

class Entity_vo extends Base_vo
{

    private $entity_id;

    //class variable
    private $name;
    private $country_id;
    private $business_registration_no;
    private $gst_no;
    private $registration_address;
    private $create_on = '0000-00-00 00:00:00';
    private $create_at;
    private $create_by;
    private $modify_on;
    private $modify_at;
    private $modify_by;
    private $primary_key = array("entity_id");

    //primary key
    private $increment_field = "entity_id";

    //auo increment

    public function __construct()
    {
        parent::Base_vo();
    }

    //instance method

    public function get_entity_id()
    {
        return $this->entity_id;
    }

    public function set_entity_id($value)
    {
        $this->entity_id = $value;
        return $this;
    }

    public function get_name()
    {
        return $this->name;
    }

    public function set_name($value)
    {
        $this->name = $value;
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

    public function get_business_registration_no()
    {
        return $this->business_registration_no;
    }

    public function set_business_registration_no($value)
    {
        $this->business_registration_no = $value;
        return $this;
    }

    public function get_gst_no()
    {
        return $this->gst_no;
    }

    public function set_gst_no($value)
    {
        $this->gst_no = $value;
        return $this;
    }

    public function get_registration_address()
    {
        return $this->registration_address;
    }

    public function set_registration_address($value)
    {
        $this->registration_address = $value;
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

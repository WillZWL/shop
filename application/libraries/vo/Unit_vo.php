<?php
include_once 'Base_vo.php';

class Unit_vo extends Base_vo
{

    //class variable
    private $id;
    private $unit_type_id;
    private $unit_name;
    private $func_unit_name;
    private $standardize_value = '1.0000';
    private $description;
    private $status = '1';
    private $create_on = '0000-00-00 00:00:00';
    private $create_at;
    private $create_by;
    private $modify_on;
    private $modify_at;
    private $modify_by;

    //primary key
    private $primary_key = array("id");

    //auo increment
    private $increment_field = "";

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

    public function get_unit_type_id()
    {
        return $this->unit_type_id;
    }

    public function set_unit_type_id($value)
    {
        $this->unit_type_id = $value;
        return $this;
    }

    public function get_unit_name()
    {
        return $this->unit_name;
    }

    public function set_unit_name($value)
    {
        $this->unit_name = $value;
        return $this;
    }

    public function get_func_unit_name()
    {
        return $this->func_unit_name;
    }

    public function set_func_unit_name($value)
    {
        $this->func_unit_name = $value;
        return $this;
    }

    public function get_standardize_value()
    {
        return $this->standardize_value;
    }

    public function set_standardize_value($value)
    {
        $this->standardize_value = $value;
        return $this;
    }

    public function get_description()
    {
        return $this->description;
    }

    public function set_description($value)
    {
        $this->description = $value;
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
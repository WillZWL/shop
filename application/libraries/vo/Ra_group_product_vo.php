<?php
include_once "Base_vo.php";

class Ra_group_product_vo extends Base_vo
{
    private $ra_group_id;
    private $sku;
    private $priority;
    private $build_bundle;
    private $create_on = '0000-00-00 00:00:00';
    private $create_at;
    private $create_by;
    private $modify_on;
    private $modify_at;
    private $modify_by;

    //primary key
    private $primary_key = array("ra_group_id", "sku");

    //auo increment
    private $increment_field = "";

    //instance method
    public function get_ra_group_id()
    {
        return $this->ra_group_id;
    }

    public function set_ra_group_id($value)
    {
        $this->ra_group_id = $value;
    }

    public function get_sku()
    {
        return $this->sku;
    }

    public function set_sku($value)
    {
        $this->sku = $value;
    }

    public function get_priority()
    {
        return $this->priority;
    }

    public function set_priority($value)
    {
        $this->priority = $value;
    }

    public function get_build_bundle()
    {
        return $this->build_bundle;
    }

    public function set_build_bundle($value)
    {
        $this->build_bundle = $value;
    }

    public function get_create_on()
    {
        return $this->create_on;
    }

    public function set_create_on($value)
    {
        $this->create_on = $value;
    }

    public function get_create_at()
    {
        return $this->create_at;
    }

    public function set_create_at($value)
    {
        $this->create_at = $value;
    }

    public function get_create_by()
    {
        return $this->create_by;
    }

    public function set_create_by($value)
    {
        $this->create_by = $value;
    }

    public function get_modify_on()
    {
        return $this->modify_on;
    }

    public function set_modify_on($value)
    {
        $this->modify_on = $value;
    }

    public function get_modify_at()
    {
        return $this->modify_at;
    }

    public function set_modify_at($value)
    {
        $this->modify_at = $value;
    }

    public function get_modify_by()
    {
        return $this->modify_by;
    }

    public function set_modify_by($value)
    {
        $this->modify_by = $value;
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
<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once "Base_dto.php";

class Bundle_list_dto extends Base_dto
{

    //class variable
    private $prod_sku;
    private $component_sku;
    private $component_order;
    private $create_on;
    private $create_at;
    private $create_by;
    private $modify_on;
    private $modify_at;
    private $modify_by;
    private $components;
    private $bundle_name;

    //instance method
    public function get_prod_sku()
    {
        return $this->prod_sku;
    }

    public function set_prod_sku($value)
    {
        $this->prod_sku = $value;
    }

    public function get_component_sku()
    {
        return $this->component_sku;
    }

    public function set_component_sku($value)
    {
        $this->component_sku = $value;
    }

    public function get_component_order()
    {
        return $this->component_order;
    }

    public function set_component_order($value)
    {
        $this->component_order = $value;
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

    public function get_components()
    {
        return $this->components;
    }

    public function set_components($value)
    {
        $this->components = $value;
    }

    public function get_bundle_name()
    {
        return $this->bundle_name;
    }

    public function set_bundle_name($value)
    {
        $this->bundle_name = $value;
    }

}


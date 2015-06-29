<?php defined('BASEPATH') OR exit('No direct script access allowed');
include_once "Base_dto.php";

class Product_shipping_override_dto extends Base_dto
{
    private $platform_id;
    private $sku;
    private $ship_option;
    private $do_not_ship;
    private $type;
    private $shipping_charge;

    public function set_platform_id($value)
    {
        $this->platform_id = $value;
    }

    public function get_platform_id()
    {
        return $this->platform_id;
    }

    public function set_sku($value)
    {
        $this->sku = $value;
    }

    public function get_sku()
    {
        return $this->sku;
    }

    public function set_ship_option($value)
    {
        $this->ship_option = $value;
    }

    public function get_ship_option()
    {
        return $this->ship_option;
    }

    public function set_do_not_ship($value)
    {
        $this->do_not_ship = $value;
    }

    public function get_do_not_ship()
    {
        return $this->do_not_ship;
    }

    public function set_type($value)
    {
        $this->type = $value;
    }

    public function get_type()
    {
        return $this->type;
    }

    public function set_shipping_charge($value)
    {
        $this->shipping_charge = $value;
    }

    public function get_shipping_charge()
    {
        return $this->shipping_charge;
    }
}

/* End of file product_shipping_override_dto.php */
/* Location: ./system/application/libraries/dto/product_shipping_override_dto.php */
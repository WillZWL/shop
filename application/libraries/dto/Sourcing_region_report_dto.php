<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once "Base_dto.php";

class Sourcing_region_report_dto extends Base_dto
{
    private $platform_id;
    private $conv_site_id;
    private $so_no;
    private $order_create_date;
    private $prod_name;
    private $prod_sku;
    private $qty;
    private $order_amount;
    private $unit_price;
    private $profit;

    public function __construct()
    {
        parent::__construct();
    }

    public function get_platform_id()
    {
        return $this->platform_id;
    }

    public function set_platform_id($value)
    {
        $this->platform_id = $value;
    }

    public function get_conv_site_id()
    {
        return $this->conv_site_id;
    }

    public function set_conv_site_id($value)
    {
        $this->conv_site_id = $value;
    }

    public function get_so_no()
    {
        return $this->so_no;
    }

    public function set_so_no($value)
    {
        $this->so_no = $value;
    }

    public function get_order_create_date()
    {
        return $this->order_create_date;
    }

    public function set_order_create_date($value)
    {
        $this->order_create_date = $value;
    }

    public function get_prod_name()
    {
        return $this->prod_name;
    }

    public function set_prod_name($value)
    {
        $this->prod_name = $value;
    }

    public function get_prod_sku()
    {
        return $this->prod_sku;
    }

    public function set_prod_sku($value)
    {
        $this->prod_sku = $value;
    }

    public function get_qty()
    {
        return $this->qty;
    }

    public function set_qty($value)
    {
        $this->qty = $value;
    }

    public function get_order_amount()
    {
        return $this->order_amount;
    }

    public function set_order_amount($value)
    {
        $this->order_amount = $value;
    }

    public function get_unit_price()
    {
        return $this->unit_price;
    }

    public function set_unit_price($value)
    {
        $this->unit_price = $value;
    }

    public function get_profit()
    {
        return $this->profit;
    }

    public function set_profit($value)
    {
        $this->profit = $value;
    }
}
?>
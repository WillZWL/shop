<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once "Base_dto.php";

class Skype_report_dto extends Base_dto
{

    private $bill_country_id;
    private $period;
    private $number_of_orders;
    private $items_ordered;
    private $subtotal;
    private $tax;
    private $discounts;
    private $shipping;
    private $total;
    private $invoiced;
    private $refunded;

    public function __construct()
    {
        parent::__construct();
    }

    public function get_bill_country_id()
    {
        return $this->bill_country_id;
    }

    public function set_bill_country_id($value)
    {
        $this->bill_country_id = $value;
    }

    public function get_period()
    {
        return $this->period;
    }

    public function set_period($value)
    {
        $this->period = $value;
    }

    public function get_number_of_orders()
    {
        return $this->number_of_orders;
    }

    public function set_number_of_orders($value)
    {
        $this->number_of_orders = $value;
    }

    public function get_items_ordered()
    {
        return $this->items_ordered;
    }

    public function set_items_ordered($value)
    {
        $this->items_ordered = $value;
    }

    public function get_subtotal()
    {
        return $this->subtotal;
    }

    public function set_subtotal($value)
    {
        $this->subtotal = $value;
    }

    public function get_tax()
    {
        return $this->tax;
    }

    public function set_tax($value)
    {
        $this->tax = $value;
    }

    public function get_discounts()
    {
        return $this->discounts;
    }

    public function set_discounts($value)
    {
        $this->discounts = $value;
    }

    public function get_shipping()
    {
        return $this->shipping;
    }

    public function set_shipping($value)
    {
        $this->shipping = $value;
    }

    public function get_total()
    {
        return $this->total;
    }

    public function set_total($value)
    {
        $this->total = $value;
    }

    public function get_invoiced()
    {
        return $this->invoiced;
    }

    public function set_invoiced($value)
    {
        $this->invoiced = $value;
    }

    public function get_refunded()
    {
        return $this->refunded;
    }

    public function set_refunded($value)
    {
        $this->refunded = $value;
    }
}


/* End of file skype_report_dto.php */
/* Location: ./system/application/libraries/dto/skype_report_dto.php */
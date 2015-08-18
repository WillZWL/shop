<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once "Base_dto.php";

class Order_not_in_ria_report_dto extends Base_dto
{
    private $payment_gateway_id;
    private $order_create_date;
    private $currency_id;
    private $so_no;
    private $amount;

    public function __construct()
    {
        parent::__construct();
    }

    public function get_payment_gateway_id()
    {
        return $this->payment_gateway_id;
    }

    public function set_payment_gateway_id($value)
    {
        $this->payment_gateway_id = $value;
    }

    public function get_order_create_date()
    {
        return date('d/m/Y h:i:s', strtotime($this->order_create_date));
    }

    public function set_order_create_date($value)
    {
        $this->order_create_date = $value;
    }

    public function get_currency_id()
    {
        return $this->currency_id;
    }

    public function set_currency_id($value)
    {
        $this->currency_id = $value;
    }

    public function get_so_no()
    {
        return $this->so_no;
    }

    public function set_so_no($value)
    {
        $this->so_no = $value;
    }

    public function get_amount()
    {
        return $this->amount;
    }

    public function set_amount($value)
    {
        $this->amount = $value;
    }

}

?>
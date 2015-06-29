<?php defined('BASEPATH') OR exit('No direct script access allowed');

include_once "Base_dto.php";

class So_fee_invoice_dto extends Base_dto
{
    private $so_no;
    private $type;
    private $txn_time;
    private $currency;
    private $gateway_id;
    private $batch_id;
    private $qty;
    private $order_amount;
    private $fee;
    private $percentage;
    private $txn_ref;

    public function __construct()
    {
        parent::__construct();
    }

    public function get_so_no()
    {
        return $this->so_no;
    }

    public function set_so_no($value)
    {
        $this->so_no = $value;
    }

    public function get_type()
    {
        return $this->type;
    }

    public function set_type($value)
    {
        $this->type = $value;
    }

    public function get_txn_time()
    {
        return $this->txn_time;
    }

    public function set_txn_time($value)
    {
        $this->txn_time = $value;
    }

    public function get_currency()
    {
        return $this->currency;
    }

    public function set_currency($value)
    {
        $this->currency = $value;
    }

    public function get_gateway_id()
    {
        return $this->gateway_id;
    }

    public function set_gateway_id($value)
    {
        $this->gateway_id = $value;
    }

    public function get_batch_id()
    {
        return $this->batch_id;
    }

    public function set_batch_id($value)
    {
        $this->batch_id = $value;
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

    public function get_fee()
    {
        return $this->fee;
    }

    public function set_fee($value)
    {
        $this->fee = $value;
    }

    public function get_percentage()
    {
        return $this->percentage;
    }

    public function set_percentage($value)
    {
        $this->percentage = $value;
    }

    public function get_txn_ref()
    {
        return $this->txn_ref;
    }

    public function set_txn_ref($value)
    {
        $this->txn_ref = $value;
    }
}



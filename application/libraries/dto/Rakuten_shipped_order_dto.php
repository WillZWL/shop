<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once "Base_dto.php";

class Rakuten_shipped_order_dto extends Base_dto
{

    private $so_no;
    private $platform_order_id;
    private $platform_id;
    private $txn_id;
    private $currency_id;
    private $amount;
    private $order_create_date;
    private $dispatch_date;
    private $status;

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

    public function get_platform_order_id()
    {
        return $this->platform_order_id;
    }

    public function set_platform_order_id($value)
    {
        $this->platform_order_id = $value;
    }

    public function get_platform_id()
    {
        return $this->platform_id;
    }

    public function set_platform_id($value)
    {
        $this->platform_id = $value;
    }

    public function get_txn_id()
    {
        return $this->txn_id;
    }

    public function set_txn_id($value)
    {
        $this->txn_id = $value;
    }

    public function get_currency_id()
    {
        return $this->currency_id;
    }

    public function set_currency_id($value)
    {
        $this->currency_id = $value;
    }

    public function get_amount()
    {
        return $this->amount;
    }

    public function set_amount($value)
    {
        $this->amount = $value;
    }

    public function get_order_create_date()
    {
        return $this->order_create_date;
    }

    public function set_order_create_date($value)
    {
        $this->order_create_date = $value;
    }

    public function get_dispatch_date()
    {
        return $this->dispatch_date;
    }

    public function set_dispatch_date($value)
    {
        $this->dispatch_date = $value;
    }

    public function get_status()
    {
        return $this->status;
    }

    public function set_status($value)
    {
        $this->status = $value;
    }
}
?>
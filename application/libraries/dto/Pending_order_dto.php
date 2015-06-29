<?php defined('BASEPATH') OR exit('No direct script access allowed');

include_once "Base_dto.php";

class Pending_order_dto extends Base_dto
{
    private $ext_sku;
    private $flex_batch_id;
    private $txn_time;
    private $currency_id;
    private $gateway_id;
    private $qty;
    private $amount;
    private $so_no;
    private $platform_order_id;
    private $total;

    public function __construct()
    {
        parent::__construct();
    }

    public function get_ext_sku()
    {
        return $this->ext_sku;
    }

    public function set_ext_sku($value)
    {
        $this->ext_sku = $value;
    }

    public function get_flex_batch_id()
    {
        return $this->flex_batch_id;
    }

    public function set_flex_batch_id($value)
    {
        $this->flex_batch_id = $value;
    }

    public function get_txn_time()
    {
        return $this->txn_time;
    }

    public function set_txn_time($value)
    {
        $this->txn_time = $value;
    }

    public function get_currency_id()
    {
        return $this->currency_id;
    }

    public function set_currency_id($value)
    {
        $this->currency_id = $value;
    }

    public function get_gateway_id()
    {
        return $this->gateway_id;
    }

    public function set_gateway_id($value)
    {
        $this->gateway_id = $value;
    }

    public function get_qty()
    {
        return $this->qty;
    }

    public function set_qty($value)
    {
        $this->qty = $value;
    }

    public function get_amount()
    {
        return $this->amount;
    }

    public function set_amount($value)
    {
        $this->amount = $value;
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

    public function get_total()
    {
        return $this->total;
    }

    public function set_total($value)
    {
        $this->total = $value;
    }
}

/* End of file pending_order_dto.php */
/* Location: ./system/application/libraries/dto/pending_order_dto.php */
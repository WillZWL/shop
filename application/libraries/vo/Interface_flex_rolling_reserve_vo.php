<?php
include_once 'Base_vo.php';

class Interface_flex_rolling_reserve_vo extends Base_vo
{

    //class variable
    private $trans_id;
    private $so_no;
    private $flex_batch_id;
    private $gateway_id;
    private $txn_id;
    private $internal_txn_id;
    private $txn_time;
    private $currency_id;
    private $amount;
    private $status;
    private $batch_status = '';
    private $failed_reason;
    private $create_on = '0000-00-00 00:00:00';
    private $create_at = '127.0.0.1';
    private $create_by;
    private $modify_on;
    private $modify_at = '127.0.0.1';
    private $modify_by;

    //primary key
    private $primary_key = array("trans_id");

    //auo increment
    private $increment_field = "trans_id";

    //instance method
    public function get_trans_id()
    {
        return $this->trans_id;
    }

    public function set_trans_id($value)
    {
        $this->trans_id = $value;
        return $this;
    }

    public function get_so_no()
    {
        return $this->so_no;
    }

    public function set_so_no($value)
    {
        $this->so_no = $value;
        return $this;
    }

    public function get_flex_batch_id()
    {
        return $this->flex_batch_id;
    }

    public function set_flex_batch_id($value)
    {
        $this->flex_batch_id = $value;
        return $this;
    }

    public function get_gateway_id()
    {
        return $this->gateway_id;
    }

    public function set_gateway_id($value)
    {
        $this->gateway_id = $value;
        return $this;
    }

    public function get_txn_id()
    {
        return $this->txn_id;
    }

    public function set_txn_id($value)
    {
        $this->txn_id = $value;
    }

    public function get_internal_txn_id()
    {
        return $this->internal_txn_id;
    }

    public function set_internal_txn_id($value)
    {
        $this->internal_txn_id = $value;
    }

    public function get_txn_time()
    {
        return $this->txn_time;
    }

    public function set_txn_time($value)
    {
        $this->txn_time = $value;
        return $this;
    }

    public function get_currency_id()
    {
        return $this->currency_id;
    }

    public function set_currency_id($value)
    {
        $this->currency_id = $value;
        return $this;
    }

    public function get_amount()
    {
        return $this->amount;
    }

    public function set_amount($value)
    {
        $this->amount = $value;
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

    public function get_batch_status()
    {
        return $this->batch_status;
    }

    public function set_batch_status($value)
    {
        $this->batch_status = $value;
        return $this;
    }

    public function get_failed_reason()
    {
        return $this->failed_reason;
    }

    public function set_failed_reason($value)
    {
        $this->failed_reason = $value;
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
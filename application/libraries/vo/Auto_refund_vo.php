<?php
include_once "base_vo.php";

class Auto_refund_vo extends Base_vo
{

    private $refund_id;

    //class variable
    private $so_no;
    private $payment_gateway_id;
    private $action;
    private $amount;
    private $log_out;
    private $log_in;
    private $create_on = '0000-00-00 00:00:00';
    private $create_at;
    private $create_by;
    private $modify_on;
    private $modify_at;
    private $modify_by;
    private $primary_key = array("refund_id");

    //primary key
    private $increment_field = "";

    //auo increment

    public function __construct()
    {
        parent::Base_vo();
    }

    //instance method

    public function get_refund_id()
    {
        return $this->refund_id;
    }

    public function set_refund_id($value)
    {
        $this->refund_id = $value;
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

    public function get_payment_gateway_id()
    {
        return $this->payment_gateway_id;
    }

    public function set_payment_gateway_id($value)
    {
        $this->payment_gateway_id = $value;
        return $this;
    }

    public function get_action()
    {
        return $this->action;
    }

    public function set_action($value)
    {
        $this->action = $value;
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

    public function get_log_out()
    {
        return $this->log_out;
    }

    public function set_log_out($value)
    {
        $this->log_out = $value;
        return $this;
    }

    public function get_log_in()
    {
        return $this->log_in;
    }

    public function set_log_in($value)
    {
        $this->log_in = $value;
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

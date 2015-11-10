<?php
include_once 'Base_vo.php';

class Platform_pmgw_vo extends Base_vo
{

    //class variable
    private $platform_id;
    private $payment_gateway_id = 'mb';
    private $pmgw_ref_currency_id;
    private $ref_from_amt;
    private $ref_to_amt_exclusive;
    private $time_from;
    private $time_to_exclusive;
    private $status = '1';
    private $create_on = '0000-00-00 00:00:00';
    private $create_at;
    private $create_by;
    private $modify_on;
    private $modify_at;
    private $modify_by;

    //primary key
    private $primary_key = array("platform_id", "payment_gateway_id");

    //auo increment
    private $increment_field = "";

    //instance method
    public function get_platform_id()
    {
        return $this->platform_id;
    }

    public function set_platform_id($value)
    {
        $this->platform_id = $value;
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

    public function get_pmgw_ref_currency_id()
    {
        return $this->pmgw_ref_currency_id;
    }

    public function set_pmgw_ref_currency_id($value)
    {
        $this->pmgw_ref_currency_id = $value;
        return $this;
    }

    public function get_ref_from_amt()
    {
        return $this->ref_from_amt;
    }

    public function set_ref_from_amt($value)
    {
        $this->ref_from_amt = $value;
        return $this;
    }

    public function get_ref_to_amt_exclusive()
    {
        return $this->ref_to_amt_exclusive;
    }

    public function set_ref_to_amt_exclusive($value)
    {
        $this->ref_to_amt_exclusive = $value;
        return $this;
    }

    public function get_time_from()
    {
        return $this->time_from;
    }

    public function set_time_from($value)
    {
        $this->time_from = $value;
        return $this;
    }

    public function get_time_to_exclusive()
    {
        return $this->time_to_exclusive;
    }

    public function set_time_to_exclusive($value)
    {
        $this->time_to_exclusive = $value;
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
<?php
include_once 'Base_vo.php';

class Risk_ref_vo extends Base_vo
{

    //class variable
    private $payment_gateway_id;
    private $risk_ref;
    private $risk_ref_desc;
    private $action;
    private $create_on = '0000-00-00 00:00:00';
    private $create_at = '127.0.0.1';
    private $create_by;
    private $modify_on;
    private $modify_at = '127.0.0.1';
    private $modify_by;

    //primary key
    private $primary_key = array("payment_gateway_id", "risk_ref");

    //auo increment
    private $increment_field = "";

    //instance method
    public function get_payment_gateway_id()
    {
        return $this->payment_gateway_id;
    }

    public function set_payment_gateway_id($value)
    {
        $this->payment_gateway_id = $value;
        return $this;
    }

    public function get_risk_ref()
    {
        return $this->risk_ref;
    }

    public function set_risk_ref($value)
    {
        $this->risk_ref = $value;
        return $this;
    }

    public function get_risk_ref_desc()
    {
        return $this->risk_ref_desc;
    }

    public function set_risk_ref_desc($value)
    {
        $this->risk_ref_desc = $value;
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
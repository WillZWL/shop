<?php
include_once 'Base_vo.php';

class Pmgw_card_vo extends Base_vo
{

    //class variable
    private $code;
    private $payment_gateway_id = 'moneybookers';
    private $card_id;
    private $card_name;
    private $card_image;
    private $status = '1';
    private $create_on = '0000-00-00 00:00:00';
    private $create_at;
    private $create_by;
    private $modify_on;
    private $modify_at;
    private $modify_by;

    //primary key
    private $primary_key = array("code");

    //auo increment
    private $increment_field = "";

    //instance method
    public function get_code()
    {
        return $this->code;
    }

    public function set_code($value)
    {
        $this->code = $value;
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

    public function get_card_id()
    {
        return $this->card_id;
    }

    public function set_card_id($value)
    {
        $this->card_id = $value;
        return $this;
    }

    public function get_card_name()
    {
        return $this->card_name;
    }

    public function set_card_name($value)
    {
        $this->card_name = $value;
        return $this;
    }

    public function get_card_image()
    {
        return $this->card_image;
    }

    public function set_card_image($value)
    {
        $this->card_image = $value;
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
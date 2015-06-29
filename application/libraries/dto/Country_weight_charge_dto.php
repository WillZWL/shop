<?php
include_once 'Base_dto.php';

class Country_weight_charge_dto extends Base_dto
{

    //class variable
    private $wcat_id;
    private $delivery_type;
    private $dest_country;
    private $currency_id;
    private $amount;
    private $create_on;
    private $create_at;
    private $create_by;
    private $modify_on;
    private $modify_at;
    private $modify_by;
    private $country_id;
    private $display_name;

    //instance method
    public function get_wcat_id()
    {
        return $this->wcat_id;
    }

    public function set_wcat_id($value)
    {
        $this->wcat_id = $value;
        return $this;
    }

    public function get_delivery_type()
    {
        return $this->delivery_type;
    }

    public function set_delivery_type($value)
    {
        $this->delivery_type = $value;
    }

    public function get_dest_country()
    {
        return $this->dest_country;
    }

    public function dest_country($value)
    {
        $this->dest_country = $value;
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

    public function get_country_id()
    {
        return $this->country_id;
    }

    public function set_country_id($value)
    {
        $this->country_id = $value;
        return $this;
    }

    public function get_display_name()
    {
        return $this->display_name;
    }

    public function set_display_name($value)
    {
        $this->display_name = $value;
        return $this;
    }

}


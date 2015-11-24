<?php
include_once 'Base_vo.php';

class Country_vo extends Base_vo
{

    //class variable
    private $id;
    private $country_id;
    private $id_3_digit;
    private $name;
    private $description;
    private $status;
    private $currency_id;
    private $language_id;
    private $fc_id;
    private $allow_sell = '0';
    private $url_enable;
    private $create_on = '0000-00-00 00:00:00';
    private $create_at;
    private $create_by;
    private $modify_on;
    private $modify_at;
    private $modify_by;

    //primary key
    private $primary_key = array("id");

    //auo increment
    private $increment_field = "";

    //instance method
    public function get_id()
    {
        return $this->id;
    }

    public function set_id($value)
    {
        $this->id = $value;
        return $this;
    }

    public function set_country_id($country_id)
    {
        $this->country_id = $country_id;
    }

    public function get_country_id()
    {
        return $this->country_id;
    }

    public function get_id_3_digit()
    {
        return $this->id_3_digit;
    }

    public function set_id_3_digit($value)
    {
        $this->id_3_digit = $value;
        return $this;
    }

    public function get_name()
    {
        return $this->name;
    }

    public function set_name($value)
    {
        $this->name = $value;
        return $this;
    }

    public function get_description()
    {
        return $this->description;
    }

    public function set_description($value)
    {
        $this->description = $value;
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

    public function get_currency_id()
    {
        return $this->currency_id;
    }

    public function set_currency_id($value)
    {
        $this->currency_id = $value;
        return $this;
    }

    public function get_language_id()
    {
        return $this->language_id;
    }

    public function set_language_id($value)
    {
        $this->language_id = $value;
        return $this;
    }

    public function get_fc_id()
    {
        return $this->fc_id;
    }

    public function set_fc_id($value)
    {
        $this->fc_id = $value;
        return $this;
    }

    public function get_allow_sell()
    {
        return $this->allow_sell;
    }

    public function set_allow_sell($value)
    {
        $this->allow_sell = $value;
        return $this;
    }

    public function get_url_enable()
    {
        return $this->url_enable;
    }

    public function set_url_enable($value)
    {
        $this->url_enable = $value;
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
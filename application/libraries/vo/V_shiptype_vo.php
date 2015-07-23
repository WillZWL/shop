<?php
include_once 'Base_vo.php';

class V_shiptype_vo extends Base_vo
{

    //class variable
    private $shiptype = '0';
    private $shiptype_name;
    private $platform_id;
    private $admin_fee;
    private $platform_region_id;
    private $platform_country_id;
    private $platform_currency_id;
    private $vat_percent = '0.00';
    private $payment_charge_percent = '0.00';
    private $free_delivery_limit = '0';
    private $platform_default_shiptype;
    private $language_id = 'en';

    //primary key
    private $primary_key = array();

    //auo increment
    private $increment_field = "";

    //instance method
    public function get_shiptype()
    {
        return $this->shiptype;
    }

    public function set_shiptype($value)
    {
        $this->shiptype = $value;
        return $this;
    }

    public function get_shiptype_name()
    {
        return $this->shiptype_name;
    }

    public function set_shiptype_name($value)
    {
        $this->shiptype_name = $value;
        return $this;
    }

    public function get_platform_id()
    {
        return $this->platform_id;
    }

    public function set_platform_id($value)
    {
        $this->platform_id = $value;
        return $this;
    }

    public function get_admin_fee()
    {
        return $this->admin_fee;
    }

    public function set_admin_fee($value)
    {
        $this->admin_fee = $value;
        return $this;
    }

    public function get_platform_region_id()
    {
        return $this->platform_region_id;
    }

    public function set_platform_region_id($value)
    {
        $this->platform_region_id = $value;
        return $this;
    }

    public function get_platform_country_id()
    {
        return $this->platform_country_id;
    }

    public function set_platform_country_id($value)
    {
        $this->platform_country_id = $value;
        return $this;
    }

    public function get_platform_currency_id()
    {
        return $this->platform_currency_id;
    }

    public function set_platform_currency_id($value)
    {
        $this->platform_currency_id = $value;
        return $this;
    }

    public function get_vat_percent()
    {
        return $this->vat_percent;
    }

    public function set_vat_percent($value)
    {
        $this->vat_percent = $value;
        return $this;
    }

    public function get_payment_charge_percent()
    {
        return $this->payment_charge_percent;
    }

    public function set_payment_charge_percent($value)
    {
        $this->payment_charge_percent = $value;
        return $this;
    }

    public function get_free_delivery_limit()
    {
        return $this->free_delivery_limit;
    }

    public function set_free_delivery_limit($value)
    {
        $this->free_delivery_limit = $value;
        return $this;
    }

    public function get_platform_default_shiptype()
    {
        return $this->platform_default_shiptype;
    }

    public function set_platform_default_shiptype($value)
    {
        $this->platform_default_shiptype = $value;
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
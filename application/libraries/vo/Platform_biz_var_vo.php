<?php
include_once 'Base_vo.php';

class Platform_biz_var_vo extends Base_vo
{

    //class variable
    private $selling_platform_id;
    private $latency_in_stock;
    private $latency_out_of_stock;
    private $vat_percent = '0.00';
    private $admin_fee;
    private $platform_region_id;
    private $platform_country_id;
    private $dest_country;
    private $platform_currency_id;
    private $sign_pos;
    private $dec_place;
    private $dec_point;
    private $thousands_sep;
    private $language_id = 'en';
    private $payment_charge_percent = '0.00';
    private $forex_fee_percent = '0.00';
    private $delivery_type;
    private $free_delivery_limit = '0';
    private $default_shiptype;
    private $create_on = '0000-00-00 00:00:00';
    private $create_at;
    private $create_by;
    private $modify_on;
    private $modify_at;
    private $modify_by;

    //primary key
    private $primary_key = array("selling_platform_id");

    //auo increment
    private $increment_field = "";

    //instance method
    public function get_selling_platform_id()
    {
        return $this->selling_platform_id;
    }

    public function set_selling_platform_id($value)
    {
        $this->selling_platform_id = $value;
        return $this;
    }

    public function get_latency_in_stock()
    {
        return $this->latency_in_stock;
    }

    public function set_latency_in_stock($value)
    {
        $this->latency_in_stock = $value;
        return $this;
    }

    public function get_latency_out_of_stock()
    {
        return $this->latency_out_of_stock;
    }

    public function set_latency_out_of_stock($value)
    {
        $this->latency_out_of_stock = $value;
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

    public function get_dest_country()
    {
        return $this->dest_country;
    }

    public function set_dest_country($value)
    {
        $this->dest_country = $value;
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

    public function get_sign_pos()
    {
        return $this->sign_pos;
    }

    public function set_sign_pos($value)
    {
        $this->sign_pos = $value;
        return $this;
    }

    public function get_dec_place()
    {
        return $this->dec_place;
    }

    public function set_dec_place($value)
    {
        $this->dec_place = $value;
        return $this;
    }

    public function get_dec_point()
    {
        return $this->dec_point;
    }

    public function set_dec_point($value)
    {
        $this->dec_point = $value;
        return $this;
    }

    public function get_thousands_sep()
    {
        return $this->thousands_sep;
    }

    public function set_thousands_sep($value)
    {
        $this->thousands_sep = $value;
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

    public function get_payment_charge_percent()
    {
        return $this->payment_charge_percent;
    }

    public function set_payment_charge_percent($value)
    {
        $this->payment_charge_percent = $value;
        return $this;
    }

    public function get_forex_fee_percent()
    {
        return $this->forex_fee_percent;
    }

    public function set_forex_fee_percent($value)
    {
        $this->forex_fee_percent = $value;
        return $this;
    }

    public function get_delivery_type()
    {
        return $this->delivery_type;
    }

    public function set_delivery_type($value)
    {
        $this->delivery_type = $value;
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

    public function get_default_shiptype()
    {
        return $this->default_shiptype;
    }

    public function set_default_shiptype($value)
    {
        $this->default_shiptype = $value;
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
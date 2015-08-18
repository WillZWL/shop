<?php defined('BASEPATH') OR exit('No direct script access allowed');

include_once "Base_dto.php";

class Yandex_product_feed_dto extends Base_dto
{
    private $sku;
    private $ext_sku;
    private $detail_desc;
    private $prod_name;
    private $prod_url;
    private $image_url;
    private $rrp;
    private $price;
    private $category;
    private $sub_cat;
    private $cat_id;
    private $sub_cat_id;
    private $ean;
    private $mpn;
    private $brand_name;
    private $availability;
    private $delivery_cost;
    private $delivery_time;
    private $warranty;
    private $condition;
    private $empty_field = '';

    // The below variables needed for profit margin calculation (price_service->calculate_profit())
    private $platform_id;
    private $prod_weight;
    private $free_delivery_limit;
    private $delivery_charge;
    private $platform_country_id;
    private $declared_pcent;
    private $platform_commission;
    private $duty_pcent;
    private $payment_charge_percent;
    private $forex_fee_percent;
    private $vat_percent;
    private $supplier_cost;
    private $listing_fee;
    private $sub_cat_margin;
    private $admin_fee;
    private $cost;
    private $auto_total_charge;
    private $logistic_cost;
    private $vat;
    private $forex_fee;
    private $payment_charge;
    private $duty;
    private $declared_value;
    private $sales_commission;
    private $current_platform_price;
    private $default_platform_converted_price;
    private $default_delivery_charge;
    private $profit;
    private $margin;

    // The below variables needed for rrp calculation (price_service->calc_website_product_rrp())
    private $fixed_rrp;
    private $rrp_factor;


    public function __construct()
    {
        parent::__construct();
    }

    public function get_sku()
    {
        return $this->sku;
    }

    public function set_sku($value)
    {
        $this->sku = $value;
    }

    public function get_ext_sku()
    {
        return $this->ext_sku;
    }

    public function set_ext_sku($value)
    {
        $this->ext_sku = $value;
    }

    public function get_detail_desc()
    {
        return $this->detail_desc;
    }

    public function set_detail_desc($value)
    {
        $this->detail_desc = $value;
    }

    public function get_prod_name()
    {
        return $this->prod_name;
    }

    public function set_prod_name($value)
    {
        $this->prod_name = $value;
    }

    public function get_prod_url()
    {
        return $this->prod_url;
    }

    public function set_prod_url($value)
    {
        $this->prod_url = $value;
    }

    public function get_image_url()
    {
        return $this->image_url;
    }

    public function set_image_url($value)
    {
        $this->image_url = $value;
    }

    public function get_price()
    {
        return $this->price;
    }

    public function set_price($value)
    {
        $this->price = $value;
    }

    public function get_rrp()
    {
        return $this->rrp;
    }

    public function set_rrp($value)
    {
        $this->rrp = $value;
    }

    public function get_category()
    {
        return $this->category;
    }

    public function set_category($value)
    {
        $this->category = $value;
    }

    public function get_sub_cat()
    {
        return $this->sub_cat;
    }

    public function set_sub_cat($value)
    {
        $this->sub_cat = $value;
    }

    public function get_cat_id()
    {
        return $this->cat_id;
    }

    public function set_cat_id($value)
    {
        $this->cat_id = $value;
    }

    public function get_sub_cat_id()
    {
        return $this->sub_cat_id;
    }

    public function set_sub_cat_id($value)
    {
        $this->sub_cat_id = $value;
    }

    public function get_ean()
    {
        return $this->ean;
    }

    public function set_ean($value)
    {
        $this->ean = $value;
    }

    public function get_mpn()
    {
        return $this->mpn;
    }

    public function set_mpn($value)
    {
        $this->mpn = $value;
    }

    public function get_brand_name()
    {
        return $this->brand_name;
    }

    public function set_brand_name($value)
    {
        $this->brand_name = $value;
    }

    public function get_availability()
    {
        return $this->availability;
    }

    public function set_availability($value)
    {
        $this->availability = $value;
    }

    public function get_delivery_cost()
    {
        return $this->delivery_cost;
    }

    public function set_delivery_cost($value)
    {
        $this->delivery_cost = $value;
    }

    public function get_delivery_time()
    {
        return $this->delivery_time;
    }

    public function set_delivery_time($value)
    {
        $this->delivery_time = $value;
    }

    public function get_warranty()
    {
        return $this->warranty;
    }

    public function set_warranty($value)
    {
        $this->warranty = $value;
    }

    public function get_condition()
    {
        return $this->condition;
    }

    public function set_condition($value)
    {
        $this->condition = $value;
    }

    public function get_empty_field()
    {
        return $this->empty_field;
    }

    public function set_empty_field($value)
    {
        $this->empty_field = $value;
    }

    public function get_platform_id()
    {
        return $this->platform_id;
    }

    public function set_platform_id($value)
    {
        $this->platform_id = $value;
    }

    public function get_current_platform_price()
    {
        return $this->current_platform_price;
    }

    public function set_current_platform_price($value)
    {
        $this->current_platform_price = $value;
    }

    public function get_default_platform_converted_price()
    {
        return $this->default_platform_converted_price;
    }

    public function set_default_platform_converted_price($value)
    {
        $this->default_platform_converted_price = $value;
    }

    public function get_prod_weight()
    {
        return $this->prod_weight;
    }

    public function set_prod_weight($value)
    {
        $this->prod_weight = $value;
    }

    public function get_default_delivery_charge()
    {
        return $this->default_delivery_charge;
    }

    public function set_default_delivery_charge($value)
    {
        $this->default_delivery_charge = $value;
    }

    public function get_free_delivery_limit()
    {
        return $this->free_delivery_limit;
    }

    public function set_free_delivery_limit($value)
    {
        $this->free_delivery_limit = $value;
    }

    public function get_delivery_charge()
    {
        return $this->delivery_charge;
    }

    public function set_delivery_charge($value)
    {
        $this->delivery_charge = $value;
    }

    public function get_platform_country_id()
    {
        return $this->platform_country_id;
    }

    public function set_platform_country_id($value)
    {
        $this->platform_country_id = $value;
    }

    public function get_declared_pcent()
    {
        return $this->declared_pcent;
    }

    public function set_declared_pcent($value)
    {
        $this->declared_pcent = $value;
    }

    public function get_platform_commission()
    {
        return $this->platform_commission;
    }

    public function set_platform_commission($value)
    {
        $this->platform_commission = $value;
    }

    public function get_sales_commission()
    {
        return $this->sales_commission;
    }

    public function set_sales_commission($value)
    {
        $this->sales_commission = $value;
    }

    public function get_declared_value()
    {
        return $this->declared_value;
    }

    public function set_declared_value($value)
    {
        $this->declared_value = $value;
    }

    public function get_duty_pcent()
    {
        return $this->duty_pcent;
    }

    public function set_duty_pcent($value)
    {
        $this->duty_pcent = $value;
    }

    public function get_duty()
    {
        return $this->duty;
    }

    public function set_duty($value)
    {
        $this->duty = $value;
    }

    public function get_payment_charge_percent()
    {
        return $this->payment_charge_percent;
    }

    public function set_payment_charge_percent($value)
    {
        $this->payment_charge_percent = $value;
    }

    public function get_payment_charge()
    {
        return $this->payment_charge;
    }

    public function set_payment_charge($value)
    {
        $this->payment_charge = $value;
    }

    public function get_forex_fee_percent()
    {
        return $this->forex_fee_percent;
    }

    public function set_forex_fee_percent($value)
    {
        $this->forex_fee_percent = $value;
    }

    public function get_forex_fee()
    {
        return $this->forex_fee;
    }

    public function set_forex_fee($value)
    {
        $this->forex_fee = $value;
    }

    public function get_vat_percent()
    {
        return $this->vat_percent;
    }

    public function set_vat_percent($value)
    {
        $this->vat_percent = $value;
    }

    public function get_vat()
    {
        return $this->vat;
    }

    public function set_vat($value)
    {
        $this->vat = $value;
    }

    public function get_supplier_cost()
    {
        return $this->supplier_cost;
    }

    public function set_supplier_cost($value)
    {
        $this->supplier_cost = $value;
    }

    public function get_logistic_cost()
    {
        return $this->logistic_cost;
    }

    public function set_logistic_cost($value)
    {
        $this->logistic_cost = $value;
    }

    public function get_listing_fee()
    {
        return $this->listing_fee;
    }

    public function set_listing_fee($value)
    {
        $this->listing_fee = $value;
    }

    public function get_sub_cat_margin()
    {
        return $this->sub_cat_margin;
    }

    public function set_sub_cat_margin($value)
    {
        $this->sub_cat_margin = $value;
    }

    public function get_auto_total_charge()
    {
        return $this->auto_total_charge;
    }

    public function set_auto_total_charge($value)
    {
        $this->auto_total_charge = $value;
    }

    public function get_cost()
    {
        return $this->cost;
    }

    public function set_cost($value)
    {
        $this->cost = $value;
    }

    public function get_admin_fee()
    {
        return $this->admin_fee;
    }

    public function set_admin_fee($value)
    {
        $this->admin_fee = $value;
    }

    public function get_profit()
    {
        return $this->profit;
    }

    public function set_profit($value)
    {
        $this->profit = $value;
    }

    public function get_margin()
    {
        return $this->margin;
    }

    public function set_margin($value)
    {
        $this->margin = $value;
    }

    // The below variables are needed for rrp calculation (price_service->calc_website_product_rrp())

    public function get_fixed_rrp()
    {
        return $this->fixed_rrp;
    }

    public function set_fixed_rrp($value)
    {
        $this->fixed_rrp = $value;
    }

    public function get_rrp_factor()
    {
        return $this->rrp_factor;
    }

    public function set_rrp_factor($value)
    {
        $this->rrp_factor = $value;
    }
}

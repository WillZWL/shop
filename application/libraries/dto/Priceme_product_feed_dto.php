<?php defined('BASEPATH') OR exit('No direct script access allowed');

include_once "Base_dto.php";

class Priceme_product_feed_dto extends Base_dto
{

    private $sku;
    private $prod_id;
    private $upc;
    private $prod_name;
    private $detail_desc;
    private $cat_name;
    private $brand_name;
    private $model;
    private $product_url;
    private $image_url;
    private $shipment_cost;
    private $delivery_cost;
    private $price;
    private $mpn;

    private $stock_status;
    private $condition;
    private $payment_type;
    private $promo_msg;
    private $currency;
    private $availability;

    // The below variables are needed for profit margin calculation (price_service->calculate_profit())
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

    public function get_prod_id()
    {
        return $this->prod_id;
    }

    public function set_prod_id($value)
    {
        $this->prod_id = $value;
    }

    public function get_upc()
    {
        return $this->upc;
    }

    public function set_upc($value)
    {
        $this->upc = $value;
    }

    public function get_prod_name()
    {
        return $this->prod_name;
    }

    public function set_prod_name($value)
    {
        $this->prod_name = $value;
    }

    public function get_detail_desc()
    {
        return $this->detail_desc;
    }

    public function set_detail_desc($value)
    {
        $this->detail_desc = $value;
    }

    public function get_cat_name()
    {
        return $this->cat_name;
    }

    public function set_cat_name($value)
    {
        $this->cat_name = $value;
    }

    public function get_brand_name()
    {
        return $this->brand_name;
    }

    public function set_brand_name($value)
    {
        $this->brand_name = $value;
    }

    public function get_model()
    {
        return $this->model;
    }

    public function set_model($value)
    {
        $this->model = $value;
    }

    public function get_product_url()
    {
        return $this->product_url;
    }

    public function set_product_url($value)
    {
        $this->product_url = $value;
    }

    public function get_image_url()
    {
        return $this->image_url;
    }

    public function set_image_url($value)
    {
        $this->image_url = $value;
    }

    public function get_shipment_cost()
    {
        return $this->shipment_cost;
    }

    public function set_shipment_cost($value)
    {
        $this->shipment_cost = $value;
    }

    public function get_delivery_cost()
    {
        return $this->delivery_cost;
    }

    public function set_delivery_cost($value)
    {
        $this->delivery_cost = $value;
    }

    public function get_price()
    {
        return $this->price;
    }

    public function set_price($value)
    {
        $this->price = $value;
    }

    public function get_mpn()
    {
        return $this->mpn;
    }

    public function set_mpn($value)
    {
        $this->mpn = $value;
    }

        public function get_stock_status()
    {
        return $this->stock_status;
    }

    public function set_stock_status($value)
    {
        $this->stock_status = $value;
    }

    public function get_condition()
    {
        return $this->condition;
    }

    public function set_condition($value)
    {
        $this->condition = $value;
    }

    public function get_payment_type()
    {
        return $this->payment_type;
    }

    public function set_payment_type($value)
    {
        $this->payment_type = $value;
    }

    public function get_promo_msg()
    {
        return $this->promo_msg;
    }

    public function set_promo_msg($value)
    {
        $this->promo_msg = $value;
    }

    public function get_currency()
    {
        return $this->currency;
    }

    public function set_currency($value)
    {
        $this->currency = $value;
    }

    public function get_availability()
    {
        return $this->availability;
    }

    public function set_availability($value)
    {
        $this->availability= $value;
    }


    // The below variables are needed for profit margin calculation (price_service->calculate_profit())

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


}


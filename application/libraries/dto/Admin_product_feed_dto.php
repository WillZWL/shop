<?php defined('BASEPATH') OR exit('No direct script access allowed');

include_once "Base_dto.php";

class Admin_product_feed_dto extends Base_dto
{
    private $cat;
    private $sub_cat;
    private $sub_sub_cat;
    private $product_name;
    private $status;
    private $website_status;
    private $price_listing_status;
    private $create_by;
    private $create_on;
    private $modify_by;
    private $modify_on;
    private $master_sku;
    private $sku;
    private $website_display_name;
    private $youtube_id_1;
    private $youtube_caption_1;
    private $youtube_id_2;
    private $youtube_caption_2;
    private $price;
    private $ship_day;
    private $delivery_day;
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


    // all country platforms that are needed
    private $webau_price;
    private $webau_margin;
    private $webbe_price;
    private $webbe_margin;
    private $webes_price;
    private $webes_margin;
    private $webfi_price;
    private $webfi_margin;
    private $webfr_price;
    private $webfr_margin;
    private $webgb_price;
    private $webgb_margin;
    private $webhk_price;
    private $webhk_margin;
    private $webie_price;
    private $webie_margin;
    private $webmy_price;
    private $webmy_margin;
    private $webnz_price;
    private $webnz_margin;
    private $websg_price;
    private $websg_margin;
    private $webus_price;
    private $webus_margin;
    private $webit_price;
    private $webit_margin;
    private $webch_price;
    private $webch_margin;
    private $webmt_price;
    private $webmt_margin;
    private $webpt_price;
    private $webpt_margin;


public function __construct()
{
    parent::__construct();
}
    public function get_cat()
{
    return $this->cat;
}

    public function set_cat($value)
    {
        $this->cat=$value;
    }
    public function get_sub_cat()
    {
        return $this->sub_cat;
    }

    public function set_sub_cat($value)
    {
        $this->sub_cat=$value;
    }
    public function get_sub_sub_cat()
    {
        return $this->sub_sub_cat;
    }

    public function set_sub_sub_cat()
    {
        return $this->sub_sub_cat;
    }
    public function get_product_name()
    {
        return $this->product_name;
    }

    public function set_product_name($value)
    {
        $this->product_name=$value;
    }
    public function get_status()
    {
        return $this->status;
    }

    public function set_status($value)
    {
        $this->status=$value;
    }
    public function get_website_status()
    {
        return $this->website_status;
    }

    public function set_website_status($value)
    {
        $this->website_status=$value;
    }

    public function get_create_by()
    {
        return $this->create_by;
    }

    public function set_create_by($value)
    {
        $this->create_by=$value;
    }
    public function get_create_on()
    {
        return $this->create_on;
    }

    public function set_create_on($value)
    {
        $this->create_on=$value;
    }
    public function get_modify_by()
    {
        return $this->modify_by;
    }

    public function set_modify_by($value)
    {
        $this->modify_by=$value;
    }
    public function get_modify_on()
    {
        return $this->modify_on;
    }

    public function set_modify_on($value)
    {
        $this->modify_on=$value;
    }
    public function get_master_sku()
    {
        return $this->master_sku;
    }

    public function set_master_sku($value)
    {
        $this->master_sku=$value;
    }
    public function get_sku()
    {
        return $this->sku;
    }

    public function set_sku($value)
    {
        $this->sku=$value;
    }
    public function get_website_display_name()
    {
        return $this->website_display_name;
    }

    public function set_website_display_name($value)
    {
        $this->website_display_name=$value;
    }

    public function get_youtube_id_1()
    {
        return $this->youtube_id_1;
    }

    public function set_youtube_id_1($value)
    {
        $this->youtube_id_1=$value;
    }

    public function get_youtube_caption_1()
    {
        return $this->youtube_caption_1;
    }

    public function set_youtube_caption_1($value)
    {
        $this->youtube_caption_1=$value;
    }

    public function get_youtube_id_2()
    {
        return $this->youtube_id_2;
    }

    public function set_youtube_id_2($value)
    {
        $this->youtube_id_2=$value;
    }

    public function get_youtube_caption_2()
    {
        return $this->youtube_caption_2;
    }

    public function set_youtube_caption_2($value)
    {
        $this->youtube_caption_2=$value;
    }

    public function get_price()
    {
        return $this->price;
    }

    public function set_price($value)
    {
        $this->price = $value;
    }

    public function get_ship_day()
    {
        return $this->ship_day;
    }

    public function set_ship_day($value)
    {
        $this->ship_day = $value;
    }

    public function get_delivery_day()
    {
        return $this->delivery_day;
    }

    public function set_delivery_day($value)
    {
        $this->delivery_day = $value;
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

    public function get_empty_field()
    {
        return $this->empty_field;
    }

    public function set_empty_field($value)
    {
        $this->empty_field = $value;
    }

// The below variables needed for profit margin calculation (price_service->calculate_profit())

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


// all country platforms needed

    public function get_webau_price()
    {
        return $this->webau_price;
    }

    public function set_webau_price($value)
    {
        $this->webau_price = $value;
    }

    public function get_webau_margin()
    {
        return $this->webau_margin;
    }

    public function set_webau_margin($value)
    {
        $this->webau_margin = $value;
    }

    public function get_webbe_price()
    {
        return $this->webbe_price;
    }

    public function set_webbe_price($value)
    {
        $this->webbe_price = $value;
    }

    public function get_webbe_margin()
    {
        return $this->webbe_margin;
    }

    public function set_webbe_margin($value)
    {
        $this->webbe_margin = $value;
    }

    public function get_webes_price()
    {
        return $this->webes_price;
    }

    public function set_webes_price($value)
    {
        $this->webes_price = $value;
    }

    public function get_webes_margin()
    {
        return $this->webes_margin;
    }

    public function set_webes_margin($value)
    {
        $this->webes_margin = $value;
    }

    public function get_webfi_price()
    {
        return $this->webfi_price;
    }

    public function set_webfi_price($value)
    {
        $this->webfi_price = $value;
    }

    public function get_webfi_margin()
    {
        return $this->webfi_margin;
    }

    public function set_webfi_margin($value)
    {
        $this->webfi_margin = $value;
    }

    public function get_webfr_price()
    {
        return $this->webfr_price;
    }

    public function set_webfr_price($value)
    {
        $this->webfr_price = $value;
    }

    public function get_webfr_margin()
    {
        return $this->webfr_margin;
    }

    public function set_webfr_margin($value)
    {
        $this->webfr_margin = $value;
    }

    public function get_webgb_price()
    {
        return $this->webgb_price;
    }

    public function set_webgb_price($value)
    {
        $this->webgb_price = $value;
    }

    public function get_webgb_margin()
    {
        return $this->webgb_margin;
    }

    public function set_webgb_margin($value)
    {
        $this->webgb_margin = $value;
    }

    public function get_webhk_price()
    {
        return $this->webhk_price;
    }

    public function set_webhk_price($value)
    {
        $this->webhk_price = $value;
    }

    public function get_webhk_margin()
    {
        return $this->webhk_margin;
    }

    public function set_webhk_margin($value)
    {
        $this->webhk_margin = $value;
    }

    public function get_webie_price()
    {
        return $this->webie_price;
    }

    public function set_webie_price($value)
    {
        $this->webie_price = $value;
    }

    public function get_webie_margin()
    {
        return $this->webie_margin;
    }

    public function set_webie_margin($value)
    {
        $this->webie_margin = $value;
    }

    public function get_webmy_price()
    {
        return $this->webmy_price;
    }

    public function set_webmy_price($value)
    {
        $this->webmy_price = $value;
    }

    public function get_webmy_margin()
    {
        return $this->webmy_margin;
    }

    public function set_webmy_margin($value)
    {
        $this->webmy_margin = $value;
    }

    public function get_webnz_price()
    {
        return $this->webnz_price;
    }

    public function set_webnz_price($value)
    {
        $this->webnz_price = $value;
    }

    public function get_webnz_margin()
    {
        return $this->webnz_margin;
    }

    public function set_webnz_margin($value)
    {
        $this->webnz_margin = $value;
    }

    public function get_websg_price()
    {
        return $this->websg_price;
    }

    public function set_websg_price($value)
    {
        $this->websg_price = $value;
    }

    public function get_websg_margin()
    {
        return $this->websg_margin;
    }

    public function set_websg_margin($value)
    {
        $this->websg_margin = $value;
    }

    public function get_webus_price()
    {
        return $this->webus_price;
    }

    public function set_webus_price($value)
    {
        $this->webus_price = $value;
    }

    public function get_webus_margin()
    {
        return $this->webus_margin;
    }

    public function set_webus_margin($value)
    {
        $this->webus_margin = $value;
    }

    public function get_webit_price()
    {
        return $this->webit_price;
    }

    public function set_webit_price($value)
    {
        $this->webit_price = $value;
    }

    public function get_webit_margin()
    {
        return $this->webit_margin;
    }

    public function set_webit_margin($value)
    {
        $this->webit_margin = $value;
    }

    public function get_webch_price()
    {
        return $this->webch_price;
    }

    public function set_webch_price($value)
    {
        $this->webch_price = $value;
    }

    public function get_webch_margin()
    {
        return $this->webch_margin;
    }

    public function set_webch_margin($value)
    {
        $this->webch_margin = $value;
    }

    public function get_webmt_price()
    {
        return $this->webmt_price;
    }

    public function set_webmt_price($value)
    {
        $this->webmt_price = $value;
    }

    public function get_webmt_margin()
    {
        return $this->webmt_margin;
    }

    public function set_webmt_margin($value)
    {
        $this->webmt_margin = $value;
    }

    public function get_webpt_price()
    {
        return $this->webpt_price;
    }

    public function set_webpt_price($value)
    {
        $this->webpt_price = $value;
    }

    public function get_webpt_margin()
    {
        return $this->webpt_margin;
    }

    public function set_webpt_margin($value)
    {
        $this->webpt_margin = $value;
    }


}
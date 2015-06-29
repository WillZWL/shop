<?php
defined('BASEPATH') OR exit('No direct script access allowed');
include_once 'Base_dto.php';

class Shipment_info_to_courier_dhl_dto extends Base_dto
{

    private $tel;
    private $delivery_name;
    private $delivery_company;
    private $delivery_address;
    private $delivery_address1;
    private $delivery_address2;
    private $delivery_address3;
    private $delivery_postcode;
    private $delivery_city;
    private $delivery_state;
    private $delivery_country_id;
    private $qty;
    private $prod_weight;
    private $amount;
    private $rate;
    private $cc_desc;
    private $cc_code;
    private $so_no;

    private $price;
    private $free_delivery_limit;
    private $delivery_charge;
    private $platform_id;
    private $declared_pcent;
    private $declared_value;
    private $prod_sku;
    private $mawb;

    private $order_cost;
    private $added_service;

    private $currency_id;
    //#2507 for DPD_NL courier feed
    private $client_email;
    private $delivery_country_id_2;
    private $shipping_date;
    private $category_name;
    //END #2507

    //#2702 for Quantium courier feed
    private $item_no;
    private $country_name;
    //end #2702

    //for tnt courier feed
    private $cat_name;
    private $subcat;
    private $subsubcat;

    public function __construct()
    {
        parent::__construct();
    }

    public function set_currency_id($value)
    {
        $this->currency_id = $value;
        return $this;
    }

    public function get_currency_id()
    {
        return $this->currency_id;
    }

    public function set_order_cost($value)
    {
        $this->order_cost = $value;
        return $this;
    }

    public function get_order_cost()
    {
        return $this->order_cost;
    }

    public function set_added_service($value)
    {
        $this->added_service = $value;
        return $this;
    }

    public function get_added_service()
    {
        return $this->added_service;
    }

    public function set_tel($value)
    {
        $this->tel = $value;
        return $this;
    }

    public function get_tel()
    {
        return $this->tel;
    }

    public function set_delivery_name($value)
    {
        $this->delivery_name = $value;
        return $this;
    }

    public function get_delivery_name()
    {
        return $this->delivery_name;
    }

    public function set_delivery_company($value)
    {
        $this->delivery_company = $value;
        return $this;
    }

    public function get_delivery_company()
    {
        return $this->delivery_company;
    }


    public function set_delivery_address($value)
    {
        $this->delivery_address = $value;
        return $this;
    }

    public function get_delivery_address()
    {
        return $this->delivery_address;
    }

    public function set_delivery_address1($value)
    {
        $this->delivery_address1 = $value;
        return $this;
    }

    public function get_delivery_address1()
    {
        return $this->delivery_address1;
    }


    public function set_delivery_address2($value)
    {
        $this->delivery_address2 = $value;
        return $this;
    }

    public function get_delivery_address2()
    {
        return $this->delivery_address2;
    }

    public function set_delivery_address3($value)
    {
        $this->delivery_address3 = $value;
        return $this;
    }

    public function get_delivery_address3()
    {
        return $this->delivery_address3;
    }

    public function set_delivery_postcode($value)
    {
        $this->delivery_postcode = $value;
        return $this;
    }

    public function get_delivery_postcode()
    {
        return $this->delivery_postcode;
    }

    public function set_delivery_city($value)
    {
        $this->delivery_city = $value;
        return $this;
    }

    public function get_delivery_city()
    {
        return $this->delivery_city;
    }

    public function set_delivery_state($value)
    {
        $this->delivery_state = $value;
        return $this;
    }

    public function get_delivery_state()
    {
        return $this->delivery_state;
    }

    public function set_delivery_country_id($value)
    {
        $this->delivery_country_id = $value;
        return $this;
    }

    public function get_delivery_country_id()
    {
        return $this->delivery_country_id;
    }

    public function set_qty($value)
    {
        $this->qty = $value;
        return $this;
    }

    public function get_qty()
    {
        return $this->qty;
    }

    public function set_prod_weight($value)
    {
        $this->prod_weight = $value;
        return $this;
    }

    public function get_prod_weight()
    {
        return $this->prod_weight;
    }

    public function set_amount($value)
    {
        $this->amount = $value;
        return $this;
    }

    public function get_amount()
    {
        return $this->amount;
    }

    public function set_rate($value)
    {
        $this->rate = $value;
        return $this;
    }

    public function get_rate()
    {
        return $this->rate;
    }

    public function set_cc_desc($value)
    {
        $this->cc_desc = $value;
        return $this;
    }

    public function get_cc_desc()
    {
        return $this->cc_desc;
    }

    public function set_cc_code($value)
    {
        $this->cc_code = $value;
        return $this;
    }

    public function get_cc_code()
    {
        return $this->cc_code;
    }

    public function set_so_no($value)
    {
        $this->so_no = $value;
        return $this;
    }

    public function get_so_no()
    {
        return $this->so_no;
    }

    public function set_price($value)
    {
        $this->price = $value;
        return $this;
    }

    public function get_price()
    {
        return $this->price;
    }

    public function set_free_delivery_limit($value)
    {
        $this->free_delivery_limit = $value;
        return $this;
    }

    public function get_free_delivery_limit()
    {
        return $this->free_delivery_limit;
    }

    public function set_delivery_charge($value)
    {
        $this->delivery_charge = $value;
        return $this;
    }

    public function get_delivery_charge()
    {
        return $this->delivery_charge;
    }

    public function set_platform_id($value)
    {
        $this->platform_id = $value;
        return $this;
    }

    public function get_platform_id()
    {
        return $this->platform_id;
    }

    public function set_declared_pcent($value)
    {
        $this->declared_pcent = $value;
        return $this;
    }

    public function get_declared_pcent()
    {
        return $this->declared_pcent;
    }

    public function set_declared_value($value)
    {
        $this->declared_value = $value;
        return $this;
    }

    public function get_declared_value()
    {
        return $this->declared_value;
    }

    public function set_prod_sku($value)
    {
        $this->prod_sku = $value;
        return $this;
    }

    public function get_prod_sku()
    {
        return $this->prod_sku;
    }

    public function set_mawb($value)
    {
        $this->mawb = $value;
        return $this;
    }

    public function get_mawb()
    {
        return $this->mawb;
    }

    public function set_client_email($value)
    {
        $this->client_email = $value;
        return $this;
    }

    public function get_client_email()
    {
        return $this->client_email;
    }


    public function set_delivery_country_id_2($value)
    {
        $this->delivery_country_id_2 = $value;
        return $this;
    }

    public function get_delivery_country_id_2()
    {
        return $this->delivery_country_id_2;
    }

    public function set_shipping_date($value)
    {
        $this->shipping_date = $value;
        return $this;
    }

    public function get_shipping_date()
    {
        return $this->shipping_date;
    }

    public function set_category_name($value)
    {
        $this->category_name = $value;
        return $this;
    }

    public function get_category_name()
    {
        return $this->category_name;
    }


    public function set_item_no($value)
    {
        $this->item_no = $value;
        return $this;
    }

    public function get_item_no()
    {
        return $this->item_no;
    }

    public function set_country_name($value)
    {
        $this->country_name = $value;
        return $this;
    }

    public function get_country_name()
    {
        return $this->country_name;
    }

    public function set_cat_name($value)
    {
        $this->cat_name = $value;
        return $this;
    }

    public function get_cat_name()
    {
        return $this->cat_name;
    }

    public function set_subcat($value)
    {
        $this->subcat = $value;
        return $this;
    }

    public function get_subcat()
    {
        return $this->subcat;
    }

    public function set_subsubcat($value)
    {
        $this->subsubcat = $value;
        return $this;
    }

    public function get_subsubcat()
    {
        return $this->subsubcat;
    }
}

/* End of file shipment_info_to_courier_dhl_dto.php */
/* Location: ./system/application/libraries/dto/shipment_info_to_courier_dhl_dto.php */
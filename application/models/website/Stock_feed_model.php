<?php

class Stock_feed_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        include_once(APPPATH . "libraries/service/bundle_service.php");
        $this->set_bundle_srv(new Bundle_service());
        include_once(APPPATH . "libraries/service/price_service.php");
        $this->set_price_srv(new Price_service());
        include_once(APPPATH . "libraries/service/promotion_code_service.php");
        $this->set_promo_code_srv(new Promotion_code_service());
        include_once(APPPATH . "libraries/service/selling_platform_service.php");
        $this->set_sp_srv(new Selling_platform_service());
    }

    public function set_bundle_srv(Base_service $srv)
    {
        $this->bundle_srv = $srv;
    }

    public function set_price_srv(Base_service $srv)
    {
        $this->price_srv = $srv;
    }

    public function set_promo_code_srv(Base_service $srv)
    {
        $this->promo_code_srv = $srv;
    }

    public function set_sp_srv(Base_service $srv)
    {
        $this->sp_srv = $srv;
    }

    public function set_sf_srv(Base_service $srv)
    {
        $this->sf_srv = $srv;
    }

    public function get_skype_cache_product_feed($platform_id, $sku, $promotion_code)
    {
        return $this->get_sf_srv()->get_skype_cache_product_feed($platform_id, $sku, $promotion_code);
    }

    public function get_sf_srv()
    {
        return $this->sf_srv;
    }

    public function get_cache_product_feed_vo()
    {
        return $this->get_sf_srv()->get_cache_product_feed_vo();
    }

    public function insert_cpf($obj)
    {
        return $this->get_sf_srv()->insert_cpf($obj);
    }

    public function update_cpf($obj)
    {
        return $this->get_sf_srv()->update_cpf($obj);
    }

    public function get_listing_info($sku = "", $platform_id = "", $lang_id = 'en', $option = array())
    {
        if (is_array($sku)) {
            return false;
        }
        if ($this->check_bundle($sku)) {
            return $this->get_bundle_srv()->get_bundle_listing_info($sku, $platform_id, $option);
        } else {
            return $this->get_price_srv()->get_listing_info($sku, $platform_i, $lang_id, $option);
        }
    }

    public function check_bundle($sku)
    {
        return $this->get_bundle_srv()->check_bundle($sku);
    }

    public function get_bundle_srv()
    {
        return $this->bundle_srv;
    }

    public function get_price_srv()
    {
        return $this->price_srv;
    }

    public function get_promo_disc_info($cart_price = "", $platform_id = "", $promo_code = "", $sku = "")
    {
        return $this->get_promo_code_srv()->get_promo_disc_info($cart_price, $platform_id, $promo_code, $sku);
    }

    public function get_promo_code_srv()
    {
        return $this->promo_code_srv;
    }

    public function calc_website_product_rrp($price)
    {
        return $this->price_service->calc_website_product_rrp($price);
    }

    public function get_platform_list_w_allow_sell_country($where = array(), $option = array())
    {
        return $this->get_sp_srv()->get_platform_list_w_allow_sell_country($where, $option);
    }

    public function get_sp_srv()
    {
        return $this->sp_srv;
    }
}

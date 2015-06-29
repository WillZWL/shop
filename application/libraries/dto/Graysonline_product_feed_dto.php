<?php defined('BASEPATH') OR exit('No direct script access allowed');

include_once "Base_dto.php";

class Graysonline_product_feed_dto extends Base_dto
{
    private $sku;
    private $ext_sku;
    private $prod_name;
    private $detail_desc;
    private $specification;
    private $cat_name;
    private $sub_cat_id;
    private $sub_cat_name;
    private $cost;
    private $suggested_selling_price;
    private $image_url;
    private $shipping;
    private $sourcing_status;
    private $price;
    private $supplier_status;
    private $lead_day;
    private $last_week_updated;

    private $supplier_cost;

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

    public function get_prod_name()
    {
        return $this->prod_name;
    }

    public function set_prod_name($value)
    {
        $this->prod_name = $value;
    }

    public function get_specification()
    {
        return $this->specification;
    }

    public function set_specification($value)
    {
        $this->specification = $value;
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

    public function get_sub_cat_id()
    {
        return $this->sub_cat_id;
    }

    public function set_sub_cat_id($value)
    {
        $this->sub_cat_id = $value;
    }

    public function get_sub_cat_name()
    {
        return $this->sub_cat_name;
    }

    public function set_sub_cat_name($value)
    {
        $this->sub_cat_name = $value;
    }

    public function get_cost()
    {
        return $this->cost;
    }

    public function set_cost($value)
    {
        $this->cost = $value;
    }

    public function get_suggested_selling_price()
    {
        return $this->suggested_selling_price;
    }

    public function set_suggested_selling_price($value)
    {
        $this->suggested_selling_price = $value;
    }

    public function get_image_url()
    {
        return $this->image_url;
    }

    public function set_image_url($value)
    {
        $this->image_url = $value;
    }

    public function get_shipping()
    {
        return $this->shipping;
    }

    public function set_shipping($value)
    {
        $this->shipping = $value;
    }

    public function get_sourcing_status()
    {
        return $this->sourcing_status;
    }

    public function set_sourcing_status($value)
    {
        $this->sourcing_status = $value;
    }

    public function get_price()
    {
        return $this->price;
    }

    public function set_price($value)
    {
        $this->price = $value;
    }

    public function get_supplier_status()
    {
        return $this->supplier_status;
    }

    public function set_supplier_status($value)
    {
        $this->supplier_status = $value;
    }

    public function get_lead_day()
    {
        return $this->lead_day;
    }

    public function set_lead_day($value)
    {
        $this->lead_day = $value;
    }

    public function get_last_week_updated()
    {
        return $this->last_week_updated;
    }

    public function set_last_week_updated($value)
    {
        $this->last_week_updated = $value;
    }

    public function get_supplier_cost()
    {
        return $this->supplier_cost;
    }

    public function set_supplier_cost($value)
    {
        $this->supplier_cost = $value;
    }
}

/* End of file graysonline_product_feed_dto.php */
/* Location: ./system/application/libraries/dto/shopbot_product_feed_dto.php */
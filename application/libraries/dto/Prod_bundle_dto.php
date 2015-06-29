<?php defined('BASEPATH') OR exit('No direct script access allowed');

include_once "Base_dto.php";

class Prod_bundle_dto extends Base_dto
{
    private $prod_sku;
    private $main_prod_sku;
    private $name;
    private $total_price;
    private $website_status;
    private $website_quantity;
    private $bundle_name;
    // Array of components ordered by the product order
    // including sku, name, price, image, website_qty, website_status
    private $component_sku_list;

    public function get_prod_sku()
    {
        return $this->prod_sku;
    }

    public function set_prod_sku($prod_sku)
    {
        $this->prod_sku = $prod_sku;
    }

    public function get_main_prod_sku()
    {
        return $this->main_prod_sku;
    }

    public function set_main_prod_sku($main_prod_sku)
    {
        $this->main_prod_sku = $main_prod_sku;
    }

    public function get_name()
    {
        return $this->name;
    }

    public function set_name($name)
    {
        $this->name = $name;
    }

    public function get_total_price()
    {
        return $this->total_price;
    }

    public function set_total_price($total_price)
    {
        $this->total_price = $total_price;
    }

    public function get_website_status()
    {
        return $this->website_status;
    }

    public function set_website_status($data)
    {
        $this->website_status = $data;
    }

    public function get_website_quantity()
    {
        return $this->website_quantity;
    }

    public function set_website_quantity($data)
    {
        $this->website_quantity = $data;
    }

    public function get_component_sku_list()
    {
        return $this->component_sku_list;
    }

    public function set_component_sku_list($component_sku_list)
    {
        $this->component_sku_list = $component_sku_list;
    }

    public function get_bundle_name()
    {
        return $this->bundle_name;
    }

    public function set_bundle_name($data)
    {
        $this->bundle_name = $data;
    }
}

/* End of file prod_bundle_list_dto.php */
/* Location: ./app/libraries/dao/Product_dao.php */
<?php defined('BASEPATH') OR exit('No direct script access allowed');

include_once "Base_dto.php";

class Prod_cat_ws_dto extends Base_dto
{
    private $sku;
    private $cat_id;
    private $cat_name;
    private $scat_id;
    private $scat_name;
    private $sscat_id;
    private $sscat_name;

    public function get_sku()
    {
        return $this->sku;
    }

    public function set_sku($value)
    {
        $this->sku = $value;
    }

    public function get_cat_id()
    {
        return $this->cat_id;
    }

    public function set_cat_id($value)
    {
        $this->cat_id = $value;
    }

    public function get_cat_name()
    {
        return $this->cat_name;
    }

    public function set_cat_name($value)
    {
        $this->cat_name = $value;
    }

    public function get_scat_id()
    {
        return $this->scat_id;
    }

    public function set_scat_id($value)
    {
        $this->scat_id = $value;
    }

    public function get_scat_name()
    {
        return $this->scat_name;
    }

    public function set_scat_name($value)
    {
        $this->scat_name = $value;
    }

    public function get_sscat_id()
    {
        return $this->sscat_id;
    }

    public function set_sscat_id($value)
    {
        $this->sscat_id = $value;
    }

    public function get_sscat_name()
    {
        return $this->sscat_name;
    }

    public function set_sscat_name($value)
    {
        $this->sscat_name = $value;
    }
}

/* End of file product_cost_dto.php */
/* Location: ./system/application/libraries/dto/product_cost_dto.php */
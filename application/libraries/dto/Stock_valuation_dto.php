<?php
defined('BASEPATH') OR exit('No direct script access allowed');
include_once 'Base_dto.php';

class Stock_valuation_dto extends Base_dto
{
    //class variable
    private $cat_name;
    private $sub_cat_name;
    private $sub_sub_cat_name;
    private $prod_name;
    private $prod_sku;
    private $log_sku;
    private $warehouse_id;
    private $inventory;
    private $git;
    private $value_per_piece;
    private $total_inv_value;
    private $total_git_value;
    private $total_value;

    //instance method
    public function get_cat_name()
    {
        return $this->cat_name;
    }

    public function set_cat_name($value)
    {
        $this->cat_name = $value;
        return $this;
    }

    public function get_sub_cat_name()
    {
        return $this->sub_cat_name;
    }

    public function set_sub_cat_name($value)
    {
        $this->sub_cat_name = $value;
        return $this;
    }

    public function get_sub_sub_cat_name()
    {
        return $this->sub_sub_cat_name;
    }

    public function set_sub_sub_cat_name($value)
    {
        $this->sub_sub_cat_name = $value;
        return $this;
    }

    public function get_prod_name()
    {
        return $this->prod_name;
    }

    public function set_prod_name($value)
    {
        $this->prod_name = $value;
        return $this;
    }

    public function get_prod_sku()
    {
        return $this->prod_sku;
    }

    public function set_prod_sku($value)
    {
        $this->prod_sku = $value;
        return $this;
    }

    public function get_log_sku()
    {
        return $this->log_sku;
    }

    public function set_log_sku($value)
    {
        $this->log_sku = $value;
        return $this;
    }

    public function get_warehouse_id()
    {
        return $this->warehouse_id;
    }

    public function set_warehouse_id($value)
    {
        $this->warehouse_id = $value;
        return $this;
    }

    public function get_inventory()
    {
        return $this->inventory;
    }

    public function set_inventory($value)
    {
        $this->inventory = $value;
        return $this;
    }

    public function get_git()
    {
        return $this->git;
    }

    public function set_git($value)
    {
        $this->git = $value;
        return $this;
    }

    public function get_value_per_piece()
    {
        return $this->value_per_piece;
    }

    public function set_value_per_piece($value)
    {
        $this->value_per_piece = $value;
        return $this;
    }

    public function get_total_inv_value()
    {
        return $this->total_inv_value;
    }

    public function set_total_inv_value($value)
    {
        $this->total_inv_value = $value;
        return $this;
    }

    public function get_total_git_value()
    {
        return $this->total_git_value;
    }

    public function set_total_git_value($value)
    {
        $this->total_git_value = $value;
        return $this;
    }

    public function get_total_value()
    {
        return $this->total_value;
    }

    public function set_total_value($value)
    {
        $this->total_value = $value;
        return $this;
    }
}

?>
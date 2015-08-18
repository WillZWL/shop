<?php defined('BASEPATH') OR exit('No direct script access allowed');

include_once "product_cost_dto.php";

class Product_feed_dto extends Product_cost_dto
{

    private $url;
    private $cat_name;
    private $sub_cat_name;
    private $brand_name;
    private $model;
    private $condition = "New";
    private $payment_accepted;
    private $payment_notes;
    private $value_1;
    private $value_2;
    private $value_3;
    private $delivery_time = "2-5 Working days";
    private $empty_field = "";
    private $warranty = "12 Months";
    private $field_y = "Y";
    private $field_n = "N";
    private $extra = "";

    public function __construct()
    {
        parent::Product_cost_dto();
    }

    public function get_cat_name()
    {
        return $this->cat_name;
    }

    public function set_cat_name($value)
    {
        $this->cat_name = $value;
    }

    public function get_sub_cat_name()
    {
        return $this->sub_cat_name;
    }

    public function set_sub_cat_name($value)
    {
        $this->sub_cat_name = $value;
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

    public function get_url()
    {
        return $this->url;
    }

    public function set_url($value)
    {
        $this->url = $value;
    }

    public function get_condition()
    {
        return $this->condition;
    }

    public function set_condition($value)
    {
        $this->condition = $value;
    }

    public function get_payment_accepted()
    {
        return $this->payment_accepted;
    }

    public function set_payment_accepted($value)
    {
        $this->payment_accepted = $value;
    }

    public function get_payment_notes()
    {
        return $this->payment_notes;
    }

    public function set_payment_notes($value)
    {
        $this->payment_notes = $value;
    }

    public function get_value_1()
    {
        return $this->value_1;
    }

    public function set_value_1($value)
    {
        $this->value_1 = $value;
        return $this;
    }

    public function get_value_2()
    {
        return $this->value_2;
    }

    public function set_value_2($value)
    {
        $this->value_2 = $value;
        return $this;
    }

    public function get_value_3()
    {
        return $this->value_3;
    }

    public function set_value_3($value)
    {
        $this->value_3 = $value;
        return $this;
    }

    public function get_delivery_time()
    {
        return $this->delivery_time;
    }

    public function set_delivery_time($value)
    {
        $this->delivery_time = $value;
        return $this;
    }

    public function get_empty_field()
    {
        return $this->empty_field;
    }

    public function set_empty_field($value)
    {
        $this->empty_field = $value;
        return $this;
    }

    public function get_warranty()
    {
        return $this->warranty;
    }

    public function set_warranty($value)
    {
        $this->warranty = $value;
        return $this;
    }

    public function get_field_y()
    {
        return $this->field_y;
    }

    public function set_field_y($value)
    {
        $this->field_y = $value;
        return $this;
    }

    public function get_field_n()
    {
        return $this->field_n;
    }

    public function set_field_n($value)
    {
        $this->field_n = $value;
        return $this;
    }

    public function get_extra()
    {
        return $this->extra;
    }

    public function set_extra($value)
    {
        $this->extra = $value;
        return $this;
    }

}



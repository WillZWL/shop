<?php
include_once 'Base_vo.php';

class Competitor_map_vo extends Base_vo
{
    //class variable
    private $id;
    private $ext_sku;
    private $competitor_id;
    private $status = '0';
    private $match;
    private $last_price;
    private $now_price;
    private $product_url;
    private $note_1;
    private $note_2;
    private $comp_stock_status;
    private $comp_ship_charge;
    private $reprice_min_margin = '9.00';
    private $reprice_value = '0.00';
    private $sourcefile_timestamp = '0000-00-00 00:00:00';
    private $create_on = '0000-00-00 00:00:00';
    private $create_at = '127.0.0.1';
    private $create_by;
    private $modify_on;
    private $modify_at = '127.0.0.1';
    private $modify_by;

    //primary key
    private $primary_key = array("id");

    //auto increment
    private $increment_field = "id";

    //instance method
    public function get_id()
    {
        return $this->id;
    }

    public function set_id($value)
    {
        $this->id = $value;
    return $this;
    }

    public function get_ext_sku()
    {
        return $this->ext_sku;
    }

    public function set_ext_sku($value)
    {
        $this->ext_sku = $value;
        return $this;
    }

    public function get_competitor_id()
    {
        return $this->competitor_id;
    }

    public function set_competitor_id($value)
    {
        $this->competitor_id = $value;
        return $this;
    }

    public function get_status()
    {
        return $this->status;
    }

    public function set_status($value)
    {
        $this->status = $value;
        return $this;
    }

    public function get_match()
    {
        return $this->match;
    }

    public function set_match($value)
    {
        $this->match = $value;
        return $this;
    }

    public function get_last_price()
    {
        return $this->last_price;
    }

    public function set_last_price($value)
    {
        $this->last_price = $value;
        return $this;
    }
    public function get_now_price()
    {
        return $this->now_price;
    }

    public function set_now_price($value)
    {
        $this->now_price = $value;
        return $this;
    }

    public function get_product_url()
    {
        return $this->product_url;
    }

    public function set_product_url($value)
    {
        $this->product_url = $value;
        return $this;
    }

    public function get_note_1()
    {
        return $this->note_1;
    }

    public function set_note_1($value)
    {
        $this->note_1 = $value;
        return $this;
    }

    public function get_note_2()
    {
        return $this->note_2;
    }

    public function set_note_2($value)
    {
        $this->note_2 = $value;
        return $this;
    }

    public function get_comp_stock_status()
    {
        return $this->comp_stock_status;
    }

    public function set_comp_stock_status($value)
    {
        $this->comp_stock_status = $value;
        return $this;
    }

    public function get_comp_ship_charge()
    {
        return $this->comp_ship_charge;
    }

    public function set_comp_ship_charge($value)
    {
        $this->comp_ship_charge = $value;
        return $this;
    }

    public function get_reprice_min_margin()
    {
        return $this->reprice_min_margin;
    }

    public function set_reprice_min_margin($value)
    {
        $this->reprice_min_margin = $value;
    }

    public function get_reprice_value()
    {
        return $this->reprice_value;
    }

    public function set_reprice_value($value)
    {
        $this->reprice_value = $value;
    }

    public function get_sourcefile_timestamp()
    {
        return $this->sourcefile_timestamp;
    }

    public function set_sourcefile_timestamp($value)
    {
        $this->sourcefile_timestamp = $value;
        return $this;
    }

    public function get_create_on()
    {
        return $this->create_on;
    }

    public function set_create_on($value)
    {
        $this->create_on = $value;
        return $this;
    }

    public function get_create_at()
    {
        return $this->create_at;
    }

    public function set_create_at($value)
    {
        $this->create_at = $value;
        return $this;
    }

    public function get_create_by()
    {
        return $this->create_by;
    }

    public function set_create_by($value)
    {
        $this->create_by = $value;
        return $this;
    }

    public function get_modify_on()
    {
        return $this->modify_on;
    }

    public function set_modify_on($value)
    {
        $this->modify_on = $value;
        return $this;
    }

    public function get_modify_at()
    {
        return $this->modify_at;
    }

    public function set_modify_at($value)
    {
        $this->modify_at = $value;
        return $this;
    }

    public function get_modify_by()
    {
        return $this->modify_by;
    }

    public function set_modify_by($value)
    {
        $this->modify_by = $value;
        return $this;
    }

    public function _get_primary_key()
    {
        return $this->primary_key;
    }

    public function _get_increment_field()
    {
        return $this->increment_field;
    }
}
?>
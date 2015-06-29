<?php
include_once 'Base_vo.php';

class Cps_sourcing_list_vo extends Base_vo
{

    //class variable
    private $list_date;
    private $item_sku;
    private $order_info;
    private $required_info;
    private $required_qty;
    private $avg_cost;
    private $status = 0;
    private $create_on = '0000-00-00 00:00:00';
    private $create_at = '127.0.0.1';
    private $create_by;
    private $modify_on;
    private $modify_at = '127.0.0.1';
    private $modify_by;

    //primary key
    private $primary_key = array("list_date", "item_sku");

    //auo increment
    private $increment_field = "";

    //instance method
    public function get_list_date()
    {
        return $this->list_date;
    }

    public function set_list_date($value)
    {
        $this->list_date = $value;
        return $this;
    }

    public function get_item_sku()
    {
        return $this->item_sku;
    }

    public function set_item_sku($value)
    {
        $this->item_sku = $value;
        return $this;
    }

    public function get_order_info()
    {
        return $this->order_info;
    }

    public function set_order_info($value)
    {
        $this->order_info = $value;
        return $this;
    }

    public function get_required_info()
    {
        return $this->required_info;
    }

    public function set_required_info($value)
    {
        $this->required_info = $value;
        return $this;
    }

    public function get_required_qty()
    {
        return $this->required_qty;
    }

    public function set_required_qty($value)
    {
        $this->required_qty = $value;
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

    public function get_avg_cost()
    {
        return $this->avg_cost;
    }

    public function set_avg_cost($value)
    {
        $this->avg_cost = $value;
        return $this;
    }

}
?>
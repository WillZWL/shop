<?php
include_once 'Base_dto.php';

class Cps_sourcing_list_dto extends Base_dto
{

    //class variable
    private $list_date;
    private $master_sku;
    private $item_sku;
    private $order_info;
    private $required_qty;
    private $avg_cost;
    private $inventory;

    //instance method
    public function get_master_sku()
    {
        return $this->master_sku;
    }

    public function set_master_sku($value)
    {
        $this->master_sku = $value;
    }

    public function get_item_sku()
    {
        return $this->item_sku;
    }

    public function set_item_sku($value)
    {
        $this->item_sku = $value;
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

    public function get_required_qty()
    {
        return $this->required_qty;
    }

    public function set_required_qty($value)
    {
        $this->required_qty = $value;
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
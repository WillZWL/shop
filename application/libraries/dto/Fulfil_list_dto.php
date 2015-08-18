<?php
include_once 'Base_dto.php';

class Fulfil_list_dto extends Base_dto
{

    //class variable
    private $so_no;
    private $line_no;
    private $item_sku;
    private $qty;
    private $outstanding_qty;
    private $unit_price;
    private $vat_total;
    private $discount = '0.00';
    private $amount;
    private $cost;
    private $profit = '0.00';
    private $margin = '0.00';
    private $status = '0';
    private $create_on = '0000-00-00 00:00:00';
    private $create_at;
    private $create_by;
    private $modify_on;
    private $modify_at;
    private $modify_by;
    private $items;

    //instance method
    public function get_so_no()
    {
        return $this->so_no;
    }

    public function set_so_no($value)
    {
        $this->so_no = $value;
        return $this;
    }

    public function get_line_no()
    {
        return $this->line_no;
    }

    public function set_line_no($value)
    {
        $this->line_no = $value;
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

    public function get_qty()
    {
        return $this->qty;
    }

    public function set_qty($value)
    {
        $this->qty = $value;
        return $this;
    }

    public function get_outstanding_qty()
    {
        return $this->outstanding_qty;
    }

    public function set_outstanding_qty($value)
    {
        $this->outstanding_qty = $value;
        return $this;
    }

    public function get_unit_price()
    {
        return $this->unit_price;
    }

    public function set_unit_price($value)
    {
        $this->unit_price = $value;
        return $this;
    }

    public function get_vat_total()
    {
        return $this->vat_total;
    }

    public function set_vat_total($value)
    {
        $this->vat_total = $value;
        return $this;
    }

    public function get_discount()
    {
        return $this->discount;
    }

    public function set_discount($value)
    {
        $this->discount = $value;
        return $this;
    }

    public function get_amount()
    {
        return $this->amount;
    }

    public function set_amount($value)
    {
        $this->amount = $value;
        return $this;
    }

    public function get_cost()
    {
        return $this->cost;
    }

    public function set_cost($value)
    {
        $this->cost = $value;
        return $this;
    }

    public function get_profit()
    {
        return $this->profit;
    }

    public function set_profit($value)
    {
        $this->profit = $value;
        return $this;
    }

    public function get_margin()
    {
        return $this->margin;
    }

    public function set_margin($value)
    {
        $this->margin = $value;
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

    public function get_items()
    {
        return $this->items;
    }

    public function set_items($value)
    {
        $this->items = $value;
    }

}

?>
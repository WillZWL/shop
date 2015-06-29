<?php
include_once 'Base_dto.php';

class Sourcing_list_dto extends Base_dto
{

    //class variable
    private $list_date;
    private $master_sku;
    private $item_sku;
    private $platform_qty;
    private $required_qty;
    private $prioritized_qty;
    private $supplier_id;
    private $supplier_curr_id;
    private $supplier_cost;
    private $budget_pcent;
    private $budget;
    private $sourced_qty = '0';
    private $sourced_pcent;
    private $comments;
    private $create_on = '0000-00-00 00:00:00';
    private $create_at = '127.0.0.1';
    private $create_by;
    private $modify_on;
    private $modify_at = '127.0.0.1';
    private $modify_by;

    private $prod_name;
    private $sourcing_status;
    private $inventory;
    private $clearance;

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

    public function get_platform_qty()
    {
        return $this->platform_qty;
    }

    public function set_platform_qty($value)
    {
        $this->platform_qty = $value;
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

    public function get_prioritized_qty()
    {
        return $this->prioritized_qty;
    }

    public function set_prioritized_qty($value)
    {
        $this->prioritized_qty = $value;
        return $this;
    }

    public function get_supplier_id()
    {
        return $this->supplier_id;
    }

    public function set_supplier_id($value)
    {
        $this->supplier_id = $value;
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

    public function get_sourcing_status()
    {
        return $this->sourcing_status;
    }

    public function set_sourcing_status($value)
    {
        $this->sourcing_status = $value;
        return $this;
    }

    public function get_list_date()
    {
        return $this->list_date;
    }

    public function set_list_date($value)
    {
        $this->list_date = $value;
        return $this;
    }

    public function get_supplier_curr_id()
    {
        return $this->supplier_curr_id;
    }

    public function set_supplier_curr_id($value)
    {
        $this->supplier_curr_id = $value;
    }

    public function get_supplier_cost()
    {
        return $this->supplier_cost;
    }

    public function set_supplier_cost($value)
    {
        $this->supplier_cost = $value;
    }

    public function get_budget_pcent()
    {
        return $this->budget_pcent;
    }

    public function set_budget_pcent($value)
    {
        $this->budget_pcent = $value;
    }

    public function get_budget()
    {
        return $this->budget;
    }

    public function set_budget($value)
    {
        $this->budget = $value;
    }

    public function get_sourced_qty()
    {
        return $this->sourced_qty;
    }

    public function set_sourced_qty($value)
    {
        $this->sourced_qty = $value;
        return $this;
    }

    public function get_sourced_pcent()
    {
        return $this->sourced_pcent;
    }

    public function set_sourced_pcent($value)
    {
        $this->sourced_pcent = $value;
        return $this;
    }

    public function get_comments()
    {
        return $this->comments;
    }

    public function set_comments($value)
    {
        $this->comments = $value;
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

    public function get_clearance()
    {
        return $this->clearance;
    }

    public function set_clearance($value)
    {
        $this->clearance = $value;
    }
}

?>
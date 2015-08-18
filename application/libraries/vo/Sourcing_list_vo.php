<?php
include_once "base_vo.php";

class Sourcing_list_vo extends Base_vo
{

    private $list_date;

    //class variable
    private $batch_no = '1';
    private $item_sku;
    private $platform_qty;
    private $required_qty;
    private $prioritized_qty = '0';
    private $supplier_id;
    private $sourcing_reg_id;
    private $sourced_qty = '0';
    private $comments;
    private $create_on = '0000-00-00 00:00:00';
    private $create_at = '127.0.0.1';
    private $create_by;
    private $modify_on;
    private $modify_at = '127.0.0.1';
    private $modify_by;
    private $primary_key = array("list_date", "batch_no", "item_sku");

    //primary key
    private $increment_field = "";

    //auo increment

    public function __construct()
    {
        parent::Base_vo();
    }

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

    public function get_batch_no()
    {
        return $this->batch_no;
    }

    public function set_batch_no($value)
    {
        $this->batch_no = $value;
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
        return $this;
    }

    public function get_sourcing_reg_id()
    {
        return $this->sourcing_reg_id;
    }

    public function set_sourcing_reg_id($value)
    {
        $this->sourcing_reg_id = $value;
        return $this;
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

    public function get_comments()
    {
        return $this->comments;
    }

    public function set_comments($value)
    {
        $this->comments = $value;
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

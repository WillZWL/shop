<?php
include_once 'Base_dto.php';

class Refund_item_prod_dto extends Base_dto
{

    //class variable
    private $refund_id;
    private $line_no;
    private $item_sku;
    private $qty;
    private $refund_amount;
    private $item_status;
    private $status;
    private $refund_type;
    private $create_on;
    private $create_at;
    private $create_by;
    private $modify_on;
    private $modify_at;
    private $modify_by;
    private $username;
    private $name;

    public function __construct()
    {
        parent::__construct();
    }

    //instance method
    public function get_refund_id()
    {
        return $this->refund_id;
    }

    public function set_refund_id($value)
    {
        $this->refund_id = $value;
    }

    public function get_line_no()
    {
        return $this->line_no;
    }

    public function set_line_no($value)
    {
        $this->line_no = $value;
    }

    public function get_item_sku()
    {
        return $this->item_sku;
    }

    public function set_item_sku($value)
    {
        $this->item_sku = $value;
    }

    public function get_qty()
    {
        return $this->qty;
    }

    public function set_qty($value)
    {
        $this->qty = $value;
    }

    public function get_refund_amount()
    {
        return $this->refund_amount;
    }

    public function set_refund_amount($value)
    {
        $this->refund_amount = $value;
    }

    public function get_status()
    {
        return $this->status;
    }

    public function set_status($value)
    {
        $this->status = $value;
    }

    public function get_refund_type()
    {
        return $this->refund_type;
    }

    public function set_refund_type($value)
    {
        $this->refund_type = $value;
    }

    public function get_create_on()
    {
        return $this->create_on;
    }

    public function set_create_on($value)
    {
        $this->create_on = $value;
    }

    public function get_create_at()
    {
        return $this->create_at;
    }

    public function set_create_at($value)
    {
        $this->create_at = $value;
    }

    public function get_create_by()
    {
        return $this->create_by;
    }

    public function set_create_by($value)
    {
        $this->create_by = $value;
    }

    public function get_modify_on()
    {
        return $this->modify_on;
    }

    public function set_modify_on($value)
    {
        $this->modify_on = $value;
    }

    public function get_modify_at()
    {
        return $this->modify_at;
    }

    public function set_modify_at($value)
    {
        $this->modify_at = $value;
    }

    public function get_modify_by()
    {
        return $this->modify_by;
    }

    public function set_modify_by($value)
    {
        $this->modify_by = $value;
    }

    public function get_item_status()
    {
        return $this->item_status;
    }

    public function set_item_status($value)
    {
        $this->item_status = $value;
    }

    public function _get_primary_key()
    {
        return $this->primary_key;
    }

    public function _get_increment_field()
    {
        return $this->increment_field;
    }

    public function get_username()
    {
        return $this->username;
    }

    public function set_username($value)
    {
        $this->username = $value;
    }

    public function get_name()
    {
        return $this->name;
    }

    public function set_name($value)
    {
        $this->name = $value;
    }
}

?>
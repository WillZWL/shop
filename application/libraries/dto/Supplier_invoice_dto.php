<?php defined('BASEPATH') OR exit('No direct script access allowed');

include_once "Base_dto.php";

class Supplier_invoice_dto extends Base_dto
{
    private $index_no;
    private $product_line;
    private $row_no;
    private $master_sku;
    private $tran_type;
    private $dispatch_date;
    private $currency_id;
    private $supplier_code;
    private $siv;
    private $product_code;
    private $qty;
    private $unit_price;
    private $ship_loc_code;

    public function __construct()
    {
        parent::__construct();
    }

    public function get_index_no()
    {
        return $this->index_no;
    }

    public function set_index_no($value)
    {
        $this->index_no = $value;
    }

    public function get_product_line()
    {
        return $this->product_line;
    }

    public function set_product_line($value)
    {
        $this->product_line = $value;
    }

    public function get_row_no()
    {
        return $this->row_no;
    }

    public function set_row_no($value)
    {
        $this->row_no = $value;
    }

    public function get_master_sku()
    {
        return $this->master_sku;
    }

    public function set_master_sku($value)
    {
        $this->master_sku = $value;
    }

    public function get_tran_type()
    {
        return $this->tran_type;
    }

    public function set_tran_type($value)
    {
        $this->tran_type = $value;
    }

    public function get_dispatch_date()
    {
        return $this->dispatch_date;
    }

    public function set_dispatch_date($value)
    {
        $this->dispatch_date = $value;
    }

    public function get_currency_id()
    {
        return $this->currency_id;
    }

    public function set_currency_id($value)
    {
        $this->currency_id = $value;
    }

    public function get_supplier_code()
    {
        return $this->supplier_code;
    }

    public function set_supplier_code($value)
    {
        $this->supplier_code = $value;
    }

    public function get_siv()
    {
        return $this->siv;
    }

    public function set_siv($value)
    {
        $this->siv = $value;
    }

    public function get_product_code()
    {
        return $this->product_code;
    }

    public function set_product_code($value)
    {
        $this->product_code = $value;
    }

    public function get_qty()
    {
        return $this->qty;
    }

    public function set_qty($value)
    {
        $this->qty = $value;
    }

    public function get_unit_price()
    {
        return $this->unit_price;
    }

    public function set_unit_price($value)
    {
        $this->unit_price = $value;
    }

    public function get_ship_loc_code()
    {
        return $this->ship_loc_code;
    }

    public function set_ship_loc_code($value)
    {
        $this->ship_loc_code = $value;
    }
}

/* End of file supplier_invoice_dto.php */
/* Location: ./system/application/libraries/dto/supplier_invoice_dto.php */
<?php defined('BASEPATH') OR exit('No direct script access allowed');

include_once "Base_dto.php";

class Sales_invoice_dto extends Base_dto
{
    private $index_no;
    private $product_line;
    private $row_no;
    private $master_sku;
    private $tran_type;
    private $dispatch_date;
    private $currency_id;
    private $report_pmgw;
    private $flex_batch_id;
    private $product_code;
    private $qty;
    private $unit_price;
    private $txn_time;
    private $so_no;
    private $biz_type;
    private $ship_loc_code;
    private $txn_id;
    private $customer_email;
    private $delivery_charge;
    private $gateway_id;
    private $ria;
    private $remark;
    private $order_reason;
    private $amount;
    private $contain_size;
    private $sm_code;
    private $order_create_date;
    private $platform_id;
    private $split_so_group;
    private $parent_so_no;
    private $line_index;
    private $reason;

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

    public function get_report_pmgw()
    {
        return $this->report_pmgw;
    }

    public function set_report_pmgw($value)
    {
        $this->report_pmgw = $value;
    }

    public function get_flex_batch_id()
    {
        return $this->flex_batch_id;
    }

    public function set_flex_batch_id($value)
    {
        $this->flex_batch_id = $value;
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

    public function get_txn_time()
    {
        return $this->txn_time;
    }

    public function set_txn_time($value)
    {
        $this->txn_time = $value;
    }

    public function get_so_no()
    {
        return $this->so_no;
    }

    public function set_so_no($value)
    {
        $this->so_no = $value;
    }

    public function get_biz_type()
    {
        return $this->biz_type;
    }

    public function set_biz_type($value)
    {
        $this->biz_type = $value;
    }

    public function get_ship_loc_code()
    {
        return $this->ship_loc_code;
    }

    public function set_ship_loc_code($value)
    {
        $this->ship_loc_code = $value;
    }

    public function get_txn_id()
    {
        return $this->txn_id;
    }

    public function set_txn_id($value)
    {
        $this->txn_id = $value;
    }

    public function get_customer_email()
    {
        return $this->customer_email;
    }

    public function set_customer_email($value)
    {
        $this->customer_email = $value;
    }

    public function get_delivery_charge()
    {
        return $this->delivery_charge;
    }

    public function set_delivery_charge($value)
    {
        $this->delivery_charge = $value;
    }

    public function get_gateway_id()
    {
        return $this->gateway_id;
    }

    public function set_gateway_id($value)
    {
        $this->gateway_id = $value;
    }

    public function get_ria()
    {
        return $this->ria;
    }

    public function set_ria($value)
    {
        return $this->ria = $value;
    }

    public function get_remark()
    {
        return $this->remark;
    }

    public function set_remark($value)
    {
        return $this->remark = $value;
    }

    public function get_order_reason()
    {
        return $this->order_reason;
    }

    public function set_order_reason($order_reason)
    {
        $this->order_reason = $order_reason;
    }

    public function get_amount()
    {
        return $this->amount;
    }

    public function set_amount($value)
    {
        $this->amount = $value;
    }

    public function get_contain_size()
    {
        return $this->contain_size;
    }

    public function set_contain_size($value)
    {
        $this->contain_size = $value;
    }

    public function get_sm_code()
    {
        return $this->sm_code;
    }

    public function set_sm_code($value)
    {
        $this->sm_code = $value;
    }

    public function get_order_create_date()
    {
        return $this->order_create_date;
    }

    public function set_order_create_date($value)
    {
        $this->order_create_date = $value;
    }

    public function get_platform_id()
    {
        return $this->platform_id;
    }

    public function set_platform_id($value)
    {
        $this->platform_id = $value;
    }

    public function get_split_so_group()
    {
        return $this->split_so_group;
    }

    public function set_split_so_group($value)
    {
        $this->split_so_group = $value;
    }

    public function get_parent_so_no()
    {
        return $this->parent_so_no;
    }

    public function set_parent_so_no($value)
    {
        $this->parent_so_no = $value;
    }

    public function get_line_index()
    {
        return $this->line_index;
    }

    public function set_line_index($value)
    {
        $this->line_index = $value;
    }

    public function get_reason()
    {
        return $this->reason;
    }

    public function set_reason($value)
    {
        $this->reason = $value;
    }
}

/* End of file sales_invoice_dto.php */
/* Location: ./system/application/libraries/dto/sales_invoice_dto.php */
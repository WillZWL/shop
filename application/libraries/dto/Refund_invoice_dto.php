<?php defined('BASEPATH') OR exit('No direct script access allowed');

include_once "Base_dto.php";

class Refund_invoice_dto extends Base_dto
{
	private $index_no;
	private $product_line;
	private $row_no;
	private $master_sku;
	private $tran_type;
	private $flex_batch_id;
	private $txn_time;
	private $currency_id;
	private $report_pmgw;
	private $product_code;
	private $qty;
	private $unit_price;
	private $so_no;
	private $ship_loc_code;
	private $txn_id;
	private $ria_txn_time;
	private $failed_reason;
	private $sr_num;
	private $gateway_id;
	private $amount;
	private $contain_size;
	private $sm_code;

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

	public function get_flex_batch_id()
	{
		return $this->flex_batch_id;
	}

	public function set_flex_batch_id($value)
	{
		$this->flex_batch_id = $value;
	}

	public function get_txn_time()
	{
		return $this->txn_time;
	}

	public function set_txn_time($value)
	{
		$this->txn_time = $value;
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

	public function get_so_no()
	{
		return $this->so_no;
	}

	public function set_so_no($value)
	{
		$this->so_no = $value;
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

	public function get_ria_txn_time()
	{
		return $this->ria_txn_time;
	}

	public function set_ria_txn_time($value)
	{
		$this->ria_txn_time = $value;
	}

	public function get_failed_reason()
	{
		return $this->failed_reason;
	}

	public function set_failed_reason($value)
	{
		$this->failed_reason = $value;
	}

	public function get_sr_num()
	{
		return $this->sr_num;
	}

	public function set_sr_num($value)
	{
		$this->sr_num = $value;
	}

	public function get_gateway_id()
	{
		return $this->gateway_id;
	}

	public function set_gateway_id($value)
	{
		$this->gateway_id = $value;
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
}

/* End of file refund_invoice_dto.php */
/* Location: ./system/application/libraries/dto/refund_invoice_dto.php */
<?php
defined('BASEPATH') OR exit('No direct script access allowed');
include_once 'Base_dto.php';

class Shipment_info_to_courier_dto extends Base_dto
{
	protected $platform_id;
	protected $sh_no;
	protected $so_no;
	protected $platform_order_id;
	protected $order_create_date;
	protected $bill_name;
	protected $bill_company;
	protected $bill_address;
	protected $bill_postcode;
	protected $bill_city;
	protected $bill_state;
	protected $bill_country_id;
	protected $email;
	protected $tel;
	protected $delivery_name;
	protected $delivery_company;
	protected $delivery_address;
	protected $delivery_postcode;
	protected $delivery_city;
	protected $delivery_state;
	protected $delivery_country_id;
	protected $line_no;
	protected $sku;
	protected $prod_name;
	protected $currency_id;
	protected $unit_price;
	protected $qty;
	protected $delivery_charge;
	protected $amount;
	protected $delivery_type_id;
	protected $promotion_code;
	protected $bill_detail;
	protected $total_item_count;
	protected $item_no;
	protected $subtotal;
	protected $ship_option;
	protected $actual_cost;
	protected $offline_fee = '0.00';
	protected $delivery_address_1;
	protected $delivery_address_2;
	protected $delivery_address_3;
	protected $cc_desc;
	protected $cc_code;
	protected $courier_id;

	public function __construct()
	{
		parent::__construct();
	}

	public function set_platform_id($value)
	{
		$this->platform_id = $value;
	}

	public function get_platform_id()
	{
		return $this->platform_id;
	}

	public function set_sh_no($value)
	{
		$this->sh_no = $value;
	}

	public function get_sh_no()
	{
		return $this->sh_no;
	}

	public function set_so_no($value)
	{
		$this->so_no = $value;
	}

	public function get_so_no()
	{
		return $this->so_no;
	}

	public function set_platform_order_id($value)
	{
		$this->platform_order_id = $value;
	}

	public function get_platform_order_id()
	{
		return $this->platform_order_id;
	}

	public function set_order_create_date($value)
	{
		$this->order_create_date = $value;
	}

	public function get_order_create_date()
	{
		return $this->order_create_date;
	}

	public function set_bill_name($value)
	{
		$this->bill_name = $value;
	}

	public function get_bill_name()
	{
		return $this->bill_name;
	}

	public function set_bill_company($value)
	{
		$this->bill_company = $value;
	}

	public function get_bill_company()
	{
		return $this->bill_company;
	}

	public function set_bill_address($value)
	{
		$this->bill_address = $value;
	}

	public function get_bill_address()
	{
		return $this->bill_address;
	}

	public function set_bill_postcode($value)
	{
		$this->bill_postcode = $value;
	}

	public function get_bill_postcode()
	{
		return $this->bill_postcode;
	}

	public function set_bill_city($value)
	{
		$this->bill_city = $value;
	}

	public function get_bill_city()
	{
		return $this->bill_city;
	}

	public function set_bill_state($value)
	{
		$this->bill_state = $value;
	}

	public function get_bill_state()
	{
		return $this->bill_state;
	}

	public function set_bill_country_id($value)
	{
		$this->bill_country_id = $value;
	}

	public function get_bill_country_id()
	{
		return $this->bill_country_id;
	}

	public function set_email($value)
	{
		$this->email = $value;
	}

	public function get_email()
	{
		return $this->email;
	}

	public function set_tel($value)
	{
		$this->tel = $value;
	}

	public function get_tel()
	{
		return $this->tel;
	}

	public function set_delivery_name($value)
	{
		$this->delivery_name = $value;
	}

	public function get_delivery_name()
	{
		return $this->delivery_name;
	}

	public function set_delivery_company($value)
	{
		$this->delivery_company = $value;
	}

	public function get_delivery_company()
	{
		return $this->delivery_company;
	}

	public function set_delivery_address($value)
	{
		$this->delivery_address = $value;
	}

	public function get_delivery_address()
	{
		return $this->delivery_address;
	}

	public function set_delivery_postcode($value)
	{
		$this->delivery_postcode = $value;
	}

	public function get_delivery_postcode()
	{
		return $this->delivery_postcode;
	}

	public function set_delivery_city($value)
	{
		$this->delivery_city = $value;
	}

	public function get_delivery_city()
	{
		return $this->delivery_city;
	}

	public function set_delivery_state($value)
	{
		$this->delivery_state = $value;
	}

	public function get_delivery_state()
	{
		return $this->delivery_state;
	}

	public function set_delivery_country_id($value)
	{
		$this->delivery_country_id = $value;
	}

	public function get_delivery_country_id()
	{
		return $this->delivery_country_id;
	}

	public function set_line_no($value)
	{
		$this->line_no = $value;
	}

	public function get_line_no()
	{
		return $this->line_no;
	}

	public function set_sku($value)
	{
		$this->sku = $value;
	}

	public function get_sku()
	{
		return $this->sku;
	}

	public function set_prod_name($value)
	{
		$this->prod_name = $value;
	}

	public function get_prod_name()
	{
		return $this->prod_name;
	}

	public function set_currency_id($value)
	{
		$this->currency_id = $value;
	}

	public function get_currency_id()
	{
		return $this->currency_id;
	}

	public function set_unit_price($value)
	{
		$this->unit_price = $value;
	}

	public function get_unit_price()
	{
		return $this->unit_price;
	}

	public function set_qty($value)
	{
		$this->qty = $value;
	}

	public function get_qty()
	{
		return $this->qty;
	}

	public function set_delivery_charge($value)
	{
		$this->delivery_charge = $value;
	}

	public function get_delivery_charge()
	{
		return $this->delivery_charge;
	}

	public function set_amount($value)
	{
		$this->amount = $value;
	}

	public function get_amount()
	{
		return $this->amount;
	}

	public function set_delivery_type_id($value)
	{
		$this->delivery_type_id = $value;
	}

	public function get_delivery_type_id()
	{
		return $this->delivery_type_id;
	}

	public function set_promotion_code($value)
	{
		$this->promotion_code = $value;
	}

	public function get_promotion_code()
	{
		return $this->promotion_code;
	}

	public function set_bill_detail($value)
	{
		$this->bill_detail = $value;
	}

	public function get_bill_detail()
	{
		return $this->bill_detail;
	}

	public function set_total_item_count($value)
	{
		$this->total_item_count = $value;
	}

	public function get_total_item_count()
	{
		return $this->total_item_count;
	}

	public function set_item_no($value)
	{
		$this->item_no = $value;
	}

	public function get_item_no()
	{
		return $this->item_no;
	}

	public function set_subtotal($value)
	{
		$this->subtotal = $value;
	}

	public function get_subtotal()
	{
		return $this->subtotal;
	}

	public function set_ship_option($value)
	{
		$this->ship_option = $value;
	}

	public function get_ship_option()
	{
		return $this->ship_option;
	}

	public function set_actual_cost($value)
	{
		$this->actual_cost = $value;
	}

	public function get_actual_cost()
	{
		return $this->actual_cost;
	}

	public function get_offline_fee()
	{
		return $this->offline_fee;
	}

	public function set_offline_fee($value)
	{
		$this->offline_fee = $value;
		return $this;
	}

	public function get_delivery_address_1()
	{
		return $this->delivery_address_1;
	}

	public function set_delivery_address_1($value)
	{
		$this->delivery_address_1 = $value;
	}

	public function get_delivery_address_2()
	{
		return $this->delivery_address_2;
	}

	public function set_delivery_address_2($value)
	{
		$this->delivery_address_2 = $value;
	}

	public function get_delivery_address_3()
	{
		return $this->delivery_address_3;
	}

	public function set_delivery_address_3($value)
	{
		$this->delivery_address_3 = $value;
	}

	public function get_cc_desc()
	{
		return $this->cc_desc;
	}

	public function set_cc_desc($value)
	{
		$this->cc_desc = $value;
	}

	public function get_cc_code()
	{
		return $this->cc_code;
	}

	public function set_cc_code($value)
	{
		$this->cc_code = $value;
	}

	public function get_courier_id()
	{
		return $this->courier_id;
	}

	public function set_courier_id($value)
	{
		$this->courier_id = $value;
	}
}
?>
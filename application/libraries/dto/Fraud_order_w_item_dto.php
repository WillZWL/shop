<?php

include_once 'Base_dto.php';

class Fraud_order_w_item_dto extends Base_dto
{
	protected $id;
	protected $so_no;
	protected $hold_date;
	protected $hold_staff;
	protected $order_create_date;
	protected $payment_gateway_id;
	protected $prod_name;
	protected $category;
	protected $currency_id;
	protected $item_price;
	protected $item_quantity;
	protected $order_total_item;
	protected $order_value;
	protected $forename;
	protected $surname;
	protected $client_id;
	protected $email;
	protected $bill_name;
	protected $bill_company;
	protected $bill_address1;
	protected $bill_address2;
	protected $bill_address3;
	protected $bill_city;
	protected $bill_state;
	protected $bill_postcode;
	protected $bill_country_id;
	protected $delivery_name;
	protected $delivery_company;
	protected $delivery_address1;
	protected $delivery_address2;
	protected $delivery_address3;
	protected $delivery_state;
	protected $delivery_postcode;
	protected $delivery_country_id;
	protected $password;
	protected $tel_1;
	protected $tel_2;
	protected $tel_3;
	protected $mobile;
	protected $platform_id;
	protected $card_id;
	protected $card_type;
	protected $risk_var1;
	protected $risk_var2;
	protected $risk_var3;
	protected $risk_var4;
	protected $risk_var5;
	protected $risk_var6;
	protected $risk_var7;
	protected $risk_var8;
	protected $risk_var9;
	protected $risk_var10;
	protected $card_bin;
	protected $verification_level;
	protected $fraud_result;
	protected $AVS_result;
	protected $protection_eligibility;
	protected $protection_eligibility_type;
	protected $address_status;
	protected $payer_status;
	protected $create_at;
	protected $dispatch_date;
	protected $refund_status;
	protected $refund_date;
	protected $description;


	public function get_id()
	{
		return $this->id;
	}

	public function set_id($value)
	{
		$this->id = $value;
		return $this;
	}
	public function get_so_no()
	{
		return $this->so_no;
	}

	public function set_so_no($value)
	{
		$this->so_no = $value;
		return $this;
	}
	public function get_hold_date()
	{
		return $this->hold_date;
	}

	public function set_hold_date($value)
	{
		$this->hold_date = $value;
		return $this;
	}
	public function get_hold_staff()
	{
		return $this->hold_staff;
	}

	public function set_hold_staff($value)
	{
		$this->hold_staff = $value;
		return $this;
	}
	public function get_order_create_date()
	{
		return $this->order_create_date;
	}

	public function set_order_create_date($value)
	{
		$this->order_create_date = $value;
		return $this;
	}
	public function get_payment_gateway_id()
	{
		return $this->payment_gateway_id;
	}

	public function set_payment_gateway_id($value)
	{
		$this->payment_gateway_id = $value;
		return $this;
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
	public function get_category()
	{
		return $this->category;
	}

	public function set_category($value)
	{
		$this->category = $value;
		return $this;
	}
	public function get_currency_id()
	{
		return $this->currency_id;
	}

	public function set_currency_id($value)
	{
		$this->currency_id = $value;
		return $this;
	}
	public function get_item_price()
	{
		return $this->item_price;
	}

	public function set_item_price($value)
	{
		$this->item_price = $value;
		return $this;
	}
	public function get_item_quantity()
	{
		return $this->item_quantity;
	}

	public function set_item_quantity($value)
	{
		$this->item_quantity = $value;
		return $this;
	}
	public function get_order_total_item()
	{
		return $this->order_total_item;
	}

	public function set_order_total_item($value)
	{
		$this->order_total_item = $value;
		return $this;
	}
	public function get_order_value()
	{
		return $this->order_value;
	}

	public function set_order_value($value)
	{
		$this->order_value = $value;
		return $this;
	}
	public function get_forename()
	{
		return $this->forename;
	}

	public function set_forename($value)
	{
		$this->forename = $value;
		return $this;
	}
	public function get_surname()
	{
		return $this->surname;
	}

	public function set_surname($value)
	{
		$this->surname = $value;
		return $this;
	}
	public function get_client_id()
	{
		return $this->client_id;
	}

	public function set_client_id($value)
	{
		$this->client_id = $value;
		return $this;
	}
	public function get_email()
	{
		return $this->email;
	}

	public function set_email($value)
	{
		$this->email = $value;
		return $this;
	}
	public function get_bill_name()
	{
		return $this->bill_name;
	}

	public function set_bill_name($value)
	{
		$this->bill_name = $value;
		return $this;
	}
	public function get_bill_company()
	{
		return $this->bill_company;
	}

	public function set_bill_company($value)
	{
		$this->bill_company = $value;
		return $this;
	}
	public function get_bill_address1()
	{
		$bill_address_list = explode('|', $this->bill_address1);
		return $bill_address_list[0];
	}

	public function set_bill_address1($value)
	{
		$this->bill_address1 = $value;
		return $this;
	}
	public function get_bill_address2()
	{
		$bill_address_list = explode('|', $this->bill_address2);
		return $bill_address_list[1];
	}

	public function set_bill_address2($value)
	{
		$this->bill_address2 = $value;
		return $this;
	}
	public function get_bill_address3()
	{
		$bill_address_list = explode('|', $this->bill_address3);
		return $bill_address_list[2];
	}

	public function set_bill_address3($value)
	{
		$this->bill_address3 = $value;
		return $this;
	}
	public function get_bill_city()
	{
		return $this->bill_city;
	}

	public function set_bill_city($value)
	{
		$this->bill_city = $value;
		return $this;
	}
	public function get_bill_state()
	{
		return $this->bill_state;
	}

	public function set_bill_state($value)
	{
		$this->bill_state = $value;
		return $this;
	}
	public function get_bill_postcode()
	{
		return $this->bill_postcode;
	}

	public function set_bill_postcode($value)
	{
		$this->bill_postcode = $value;
		return $this;
	}
	public function get_bill_country_id()
	{
		return $this->bill_country_id;
	}

	public function set_bill_country_id($value)
	{
		$this->bill_country_id = $value;
		return $this;
	}
	public function get_delivery_name()
	{
		return $this->delivery_name;
	}

	public function set_delivery_name($value)
	{
		$this->delivery_name = $value;
		return $this;
	}
	public function get_delivery_company()
	{
		return $this->delivery_company;
	}

	public function set_delivery_company($value)
	{
		$this->delivery_company = $value;
		return $this;
	}
	public function get_delivery_address1()
	{
		$delivery_address_list = explode('|', $this->delivery_address1);
		return $delivery_address_list[0];
	}

	public function set_delivery_address1($value)
	{
		$this->delivery_address1 = $value;
		return $this;
	}
	public function get_delivery_address2()
	{
		$delivery_address_list = explode('|', $this->delivery_address2);
		return $delivery_address_list[1];
	}

	public function set_delivery_address2($value)
	{
		$this->delivery_address2 = $value;
		return $this;
	}
	public function get_delivery_address3()
	{
		$delivery_address_list = explode('|', $this->delivery_address3);
		return $delivery_address_list[2];
	}

	public function set_delivery_address3($value)
	{
		$this->delivery_address3 = $value;
		return $this;
	}

	public function get_delivery_state()
	{
		return $this->delivery_state;
	}

	public function set_delivery_state($value)
	{
		$this->delivery_state = $value;
		return $this;
	}
	public function get_delivery_postcode()
	{
		return $this->delivery_postcode;
	}

	public function set_delivery_postcode($value)
	{
		$this->delivery_postcode = $value;
		return $this;
	}
	public function get_delivery_country_id()
	{
		return $this->delivery_country_id;
	}

	public function set_delivery_country_id($value)
	{
		$this->delivery_country_id = $value;
		return $this;
	}
	public function get_password()
	{
		include_once(BASEPATH . 'libraries/Encrypt.php');
		$encrypt_obj = new CI_Encrypt();
		$password = $encrypt_obj->decode($this->password);
		return $password;
	}

	public function set_password($value)
	{
		$this->password = $value;
		return $this;
	}
	public function get_tel_1()
	{
		return $this->tel_1;
	}

	public function set_tel_1($value)
	{
		$this->tel_1 = $value;
		return $this;
	}
	public function get_tel_2()
	{
		return $this->tel_2;
	}

	public function set_tel_2($value)
	{
		$this->tel_2 = $value;
		return $this;
	}
	public function get_tel_3()
	{
		return $this->tel_3;
	}

	public function set_tel_3($value)
	{
		$this->tel_3 = $value;
		return $this;
	}
	public function get_mobile()
	{
		return $this->mobile;
	}

	public function set_mobile($value)
	{
		$this->mobile = $value;
		return $this;
	}
	public function get_platform_id()
	{
		return $this->platform_id;
	}

	public function set_platform_id($value)
	{
		$this->platform_id = $value;
		return $this;
	}
	public function get_card_id()
	{
		return $this->card_id;
	}

	public function set_card_id($value)
	{
		$this->card_id = $value;
		return $this;
	}
	public function get_card_type()
	{
		return $this->card_type;
	}

	public function set_card_type($value)
	{
		$this->card_type = $value;
		return $this;
	}
	public function get_risk_var1()
	{
		return $this->risk_var1;
	}

	public function set_risk_var1($value)
	{
		$this->risk_var1 = $value;
		return $this;
	}
	public function get_risk_var2()
	{
		return $this->risk_var2;
	}

	public function set_risk_var2($value)
	{
		$this->risk_var2 = $value;
		return $this;
	}
	public function get_risk_var3()
	{
		return $this->risk_var3;
	}

	public function set_risk_var3($value)
	{
		$this->risk_var3 = $value;
		return $this;
	}
	public function get_risk_var4()
	{
		return $this->risk_var4;
	}

	public function set_risk_var4($value)
	{
		$this->risk_var4 = $value;
		return $this;
	}
	public function get_risk_var5()
	{
		return $this->risk_var5;
	}

	public function set_risk_var5($value)
	{
		$this->risk_var5 = $value;
		return $this;
	}
	public function get_risk_var6()
	{
		return $this->risk_var6;
	}

	public function set_risk_var6($value)
	{
		$this->risk_var6 = $value;
		return $this;
	}
	public function get_risk_var7()
	{
		return $this->risk_var7;
	}

	public function set_risk_var7($value)
	{
		$this->risk_var7 = $value;
		return $this;
	}
	public function get_risk_var8()
	{
		return $this->risk_var8;
	}

	public function set_risk_var8($value)
	{
		$this->risk_var8 = $value;
		return $this;
	}
	public function get_risk_var9()
	{
		return $this->risk_var9;
	}

	public function set_risk_var9($value)
	{
		$this->risk_var9 = $value;
		return $this;
	}
	public function get_risk_var10()
	{
		return $this->risk_var10;
	}

	public function set_risk_var10($value)
	{
		$this->risk_var10 = $value;
		return $this;
	}
	public function get_card_bin()
	{
		return $this->card_bin;
	}

	public function set_card_bin($value)
	{
		$this->card_bin = $value;
		return $this;
	}
	public function get_verification_level()
	{
		return $this->verification_level;
	}

	public function set_verification_level($value)
	{
		$this->verification_level = $value;
		return $this;
	}
	public function get_fraud_result()
	{
		return $this->fraud_result;
	}

	public function set_fraud_result($value)
	{
		$this->fraud_result = $value;
		return $this;
	}
	public function get_AVS_result()
	{
		return $this->AVS_result;
	}

	public function set_AVS_result($value)
	{
		$this->AVS_result = $value;
		return $this;
	}
	public function get_protection_eligibility()
	{
		return $this->protection_eligibility;
	}

	public function set_protection_eligibility($value)
	{
		$this->protection_eligibility = $value;
		return $this;
	}
	public function get_protection_eligibility_type()
	{
		return $this->protection_eligibility_type;
	}

	public function set_protection_eligibility_type($value)
	{
		$this->protection_eligibility_type = $value;
		return $this;
	}
	public function get_address_status()
	{
		return $this->address_status;
	}

	public function set_address_status($value)
	{
		$this->address_status = $value;
		return $this;
	}
	public function get_payer_status()
	{
		return $this->payer_status;
	}

	public function set_payer_status($value)
	{
		$this->payer_status = $value;
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
	public function get_dispatch_date()
	{
		return $this->dispatch_date;
	}

	public function set_dispatch_date($value)
	{
		$this->dispatch_date = $value;
		return $this;
	}
	public function get_refund_status()
	{
		return $this->refund_status;
	}

	public function set_refund_status($value)
	{
		$this->refund_status = $value;
		return $this;
	}
	public function get_refund_date()
	{
		return $this->refund_date;
	}

	public function set_refund_date($value)
	{
		$this->refund_date = $value;
		return $this;
	}
	public function get_description()
	{
		return $this->description;
	}

	public function set_description($value)
	{
		$this->description = $value;
		return $this;
	}
}

/* End of file fraud_order_w_item_dto.php */
/* Location: ./app/libraries/dto/fraud_order_w_item_dto.php */

<?php

class All_orders_model extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
		$this->load->library('service/all_orders_service');
		$this->load->library('service/payment_gateway_service');
		$this->load->library('service/platform_biz_var_service');
		$this->load->library('dao/so_hold_reason_dao');
	}

	public function get_report_heading()
	{
		$fieldTitle = "Order No., Hold Reason, Hold Date, Hold Time, Hold Staff, Release Date, Release Time, Release Staff";
		$fieldTitle .= ", Order Create Date, Order Create Time, PSP Transaction ID, PSP Hold Alert, PSP Gateway, Item Ordered, Category";
		$fieldTitle .= ", Currency, Item Value, Item Quantity, Order Quantity, Order Value, Paid, MB Status, Client Forename, Client Surname, Client ID, Email Address, Billing Forename, Billing Surname, Billing Company Name";
		$fieldTitle .= ", BillingAddressLine1, BillingAddressLine2, BillingAddressLine3, BillingCity, BillingState, BillingPostalCode";
		$fieldTitle .= ", BillingCountry, DeliveryClientName, DeliveryCompanyName, DeliveryAddressLine1, DeliveryAddressLine2, DeliveryAddressLine3";
		$fieldTitle .= ", DeliveryCity, DeliveryState, DeliveryPostalCode, DeliveryCountry, Password, Telephone, Mobile, OrderType";
		$fieldTitle .= ", ShipServiceLevel, DeliveryCost, PromotionCode, PaymentType, CardType, risk1, risk2, risk3, risk4, risk5, risk6, risk7, risk8, risk9, risk10, BIN, VerificationLevel, FraudResult";
		$fieldTitle .= ", AVSResult, ProtectionEligibility, ProtectionEligibilityType, AddressStatus, PayerStatus, IPAddress, OrderStatus";
		$fieldTitle .= ", ShippedDate, RefundStatus, RefundDate, RefundReason";
		return $fieldTitle;
//Release Date, Release Time, Release Staff
//PSP Hold Alert
//what is bin
//billing has name only, no forename, surname separation

//Order Quantity
//status, paid = 2
//payment_status in so_payment_status will hold also mb_status
//order type
//ship service level $data["del_opt_list"] = end($this->delivery_option_service->get_list_w_key(array("lang_id"=>"en")));
//if not (paypalorder)
//{
	//fraud_result = risk_spec2
	//avs result = so_payment_status.risk_ref1
 //+ risk_ref.risk_ref_desc
//}
//else
//{
// paypal order
//protection eligibility = so_payment_status.risk_ref1
//protection eligibility type = so_payment_status.risk_ref2
//}
//if (moneybooker)
//VerificationLevel = so_payment_status.risk_ref1
	}

	public function get_all_orders_report($start_date, $end_date, $so_number, $order_type, $psp_gateway, $hold_reason, $currency)
	{
		return $this->all_orders_service->get_all_orders_report($start_date, $end_date, $so_number, $order_type, $psp_gateway, $hold_reason, $currency);
	}

	public function get_all_orders_export_report($start_date, $end_date, $so_number, $order_type, $psp_gateway, $hold_reason, $currency)
	{
		return $this->all_orders_service->get_all_orders_export_report($start_date, $end_date, $so_number, $order_type, $psp_gateway, $hold_reason, $currency);
	}

	public function get_export_filename($start_date, $end_date)
	{
		return "Order_screening_" . $start_date . "-" . $end_date . ".csv";
	}

	public function get_pmgw_list($where=array(), $option=array())
	{
		return $this->payment_gateway_service->get_list($where, $option);
	}

	public function get_selling_platform_list()
	{
		return $this->platform_biz_var_service->get_selling_platform_list();
	}

	public function get_currency_list()
	{
		return $this->platform_biz_var_service->get_currency_list();
	}

	public function get_so_hold_reason_list()
	{
		return $this->so_hold_reason_dao->get_reason_list();
	}

}

?>
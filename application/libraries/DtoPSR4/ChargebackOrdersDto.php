<?php
class ChargebackOrdersDto
{
    private $so_no;
    private $hold_reason;
    private $hold_date_time;
    private $hold_date;
    private $hold_time;
    private $hold_staff;
    private $order_create_date_time;
    private $order_create_date;
    private $order_create_time;
    private $payment_transaction_id;
    private $payment_gateway_id;
    private $order_value;
    private $item_quantity;
    private $order_quantity;
    private $category_name;
    private $currency;
    private $product_name;
    private $item_value;
    private $payment_status;
    private $mb_status;
    private $client_forename;
    private $client_surname;
    private $client_id;
    private $email;
    private $bill_address;
    private $bill_address_1;
    private $bill_address_2;
    private $bill_address_3;
    private $delivery_company;
    private $delivery_address;
    private $delivery_address_1;
    private $delivery_address_2;
    private $delivery_address_3;
    private $bill_name;
    private $bill_surname;
    private $bill_forename;
    private $bill_company;
    private $bill_city;
    private $bill_state;
    private $bill_postcode;
    private $bill_country_id;
    private $paid;
    private $delivery_name;
    private $delivery_forename;
    private $delivery_surname;
    private $delivery_city;
    private $delivery_state;
    private $delivery_postcode;
    private $delivery_country_id;
    private $password;
    private $tel = "  ";
    private $tel_1;
    private $tel_2;
    private $tel_3;
    private $mobile;
    private $ship_service_level;
    private $order_type;
    private $delivery_mode;
    private $delivery_cost;
    private $promotion_code;
    private $payment_type;
    private $card_type;
    private $pay_to_account;
    private $risk_var_1;
    private $risk_var_2;
    private $risk_var_3;
    private $risk_var_4;
    private $risk_var_5;
    private $risk_var_6;
    private $risk_var_7;
    private $risk_var_8;
    private $risk_var_9;
    private $risk_var_10;
    private $card_bin;
    private $risk_ref_1;
    private $risk_ref_2;
    private $risk_ref_3;
    private $risk_ref_4;
    private $ip_address;
    private $order_status;
    private $dispatch_date;
    private $refund_status;
    private $refund_date;
    private $refund_reason;
    private $empty_field;
    private $verification_level;
    private $fraud_result;
    private $avs_result;
    private $protection_eligibility;
    private $protection_eligibility_type;
    private $address_status;
    private $payer_status;
    private $chargeback_create_date;
    private $chargeback_reason;
    private $chargeback_remark;
    private $chargeback_status;

    public function setSoNo($so_no)
    {
        $this->so_no = $so_no;
    }

    public function getSoNo()
    {
        return $this->so_no;
    }

    public function setHoldReason($hold_reason)
    {
        $this->hold_reason = $hold_reason;
    }

    public function getHoldReason()
    {
        return $this->hold_reason;
    }

    public function setHoldDateTime($hold_date_time)
    {
        $this->hold_date_time = $hold_date_time;
        if ($hold_date_time != "") {
            $date = explode(" ", $hold_date_time);
            $this->hold_date = $date[0];
            $this->hold_time = $date[1];
        }

    }

    public function getHoldDateTime()
    {
        return $this->hold_date_time;
    }

    public function getHoldDate()
    {
        return $this->hold_date;
    }

    public function getHoldTime()
    {
        return $this->hold_time;
    }

    public function setHoldStaff($hold_staff)
    {
        $this->hold_staff = $hold_staff;
    }

    public function getHoldStaff()
    {
        return $this->hold_staff;
    }

    public function setOrderCreateDateTime($order_create_date_time)
    {
        $this->order_create_date_time = $order_create_date_time;
        if ($order_create_date_time != "") {
            $date = explode(" ", $order_create_date_time);
            $this->order_create_date = $date[0];
            $this->order_create_time = $date[1];
        }
    }

    public function getOrderCreateDateTime()
    {
        return $this->order_create_date_time;
    }

    public function getOrderCreateDate()
    {
        return $this->order_create_date;
    }

    public function getOrderCreateTime()
    {
        return $this->order_create_time;
    }

    public function setPaymentTransactionId($payment_transaction_id)
    {
        $this->payment_transaction_id = $payment_transaction_id;
    }

    public function getPaymentTransactionId()
    {
        return $this->payment_transaction_id;
    }

    public function setPaymentGatewayId($payment_gateway_id)
    {
        $this->payment_gateway_id = $payment_gateway_id;
    }

    public function getPaymentGatewayId()
    {
        return $this->payment_gateway_id;
    }

    public function setOrderValue($order_value)
    {
        $this->order_value = $order_value;
    }

    public function getOrderValue()
    {
        return $this->order_value;
    }

    public function setItemQuantity($item_quantity)
    {
        $this->item_quantity = $item_quantity;
    }

    public function getItemQuantity()
    {
        return $this->item_quantity;
    }

    public function setOrderQuantity($order_quantity)
    {
        $this->order_quantity = $order_quantity;
    }

    public function getOrderQuantity()
    {
        return $this->order_quantity;
    }

    public function setCategoryName($category_name)
    {
        $this->category_name = $category_name;
    }

    public function getCategoryName()
    {
        return $this->category_name;
    }

    public function setCurrency($currency)
    {
        $this->currency = $currency;
    }

    public function getCurrency()
    {
        return $this->currency;
    }

    public function setProductName($product_name)
    {
        $this->product_name = $product_name;
    }

    public function getProductName()
    {
        return $this->product_name;
    }

    public function setItemValue($item_value)
    {
        $this->item_value = $item_value;
    }

    public function getItemValue()
    {
        return $this->item_value;
    }

    public function setPaymentStatus($payment_status)
    {
        $this->payment_status = $payment_status;
        if ($this->payment_gateway_id == 'moneybookers')
            $this->mb_status = $this->payment_status;
        else
            $this->mb_status = "";

    }

    public function getPaymentStatus()
    {
        return $this->payment_status;
    }

    public function getMbStatus()
    {
        return $this->mb_status;
    }

    public function setClientForename($client_forename)
    {
        $this->client_forename = $client_forename;
    }

    public function getClientForename()
    {
        return $this->client_forename;
    }

    public function setClientSurname($client_surname)
    {
        $this->client_surname = $client_surname;
    }

    public function getClientSurname()
    {
        return $this->client_surname;
    }

    public function setClientId($client_id)
    {
        $this->client_id = $client_id;
    }

    public function getClientId()
    {
        return $this->client_id;
    }

    public function setEmail($email)
    {
        $this->email = $email;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function setBillAddress($bill_address)
    {
        $this->bill_address = $bill_address;

        $address = explode("|", $bill_address);
        $this->bill_address_1 = $address[0];
        if (sizeof($address) > 1)
            $this->bill_address_2 = $address[1];
        if (sizeof($address) > 2)
            $this->bill_address_3 = $address[2];
    }

    public function getBillAddress()
    {
        return $this->bill_address;
    }

    public function getBillAddress1()
    {
        return $this->bill_address_1;
    }

    public function getBillAddress2()
    {
        return $this->bill_address_2;
    }

    public function getBillAddress3()
    {
        return $this->bill_address_3;
    }

    public function setDeliveryCompany($delivery_company)
    {
        $this->delivery_company = $delivery_company;
    }

    public function getDeliveryCompany()
    {
        return $this->delivery_company;
    }

    public function setDeliveryAddress($delivery_address)
    {
        $this->delivery_address = $delivery_address;

        $address = explode("|", $delivery_address);
        $this->delivery_address_1 = $address[0];
        if (sizeof($address) > 1)
            $this->delivery_address_2 = $address[1];
        if (sizeof($address) > 2)
            $this->delivery_address_3 = $address[2];
    }

    public function getDeliveryAddress()
    {
        return $this->delivery_address;
    }

    public function getDeliveryAddress1()
    {
        return $this->delivery_address_1;
    }

    public function getDeliveryAddress2()
    {
        return $this->delivery_address_2;
    }

    public function getDeliveryAddress3()
    {
        return $this->delivery_address_3;
    }

    public function setBillName($bill_name)
    {
        $this->bill_name = $bill_name;
    }

    public function getBillName()
    {
        return $this->bill_name;
    }

    public function setBillSurname($bill_surname)
    {
        $this->bill_surname = $bill_surname;
    }

    public function getBillSurname()
    {
        $name_length = strlen($this->getBillForename());
        return substr($this->bill_name, $name_length, (strlen($this->bill_name) - $name_length));
    }

    public function getBillForename()
    {
        $name = explode(" ", $this->bill_name);
        if (sizeof($name) > 0)
            return $name[0];
        else
            return "";
    }

    public function setBillCompany($bill_company)
    {
        $this->bill_company = $bill_company;
    }

    public function getBillCompany()
    {
        return $this->bill_company;
    }

    public function setBillCity($bill_city)
    {
        $this->bill_city = $bill_city;
    }

    public function getBillCity()
    {
        return $this->bill_city;
    }

    public function setBillState($bill_state)
    {
        $this->bill_state = $bill_state;
    }

    public function getBillState()
    {
        return $this->bill_state;
    }

    public function setBillPostcode($bill_postcode)
    {
        $this->bill_postcode = $bill_postcode;
    }

    public function getBillPostcode()
    {
        return $this->bill_postcode;
    }

    public function setBillCountryId($bill_country_id)
    {
        $this->bill_country_id = $bill_country_id;
    }

    public function getBillCountryId()
    {
        return $this->bill_country_id;
    }

    public function getPaid()
    {
        if (($this->order_status == 5)
            || ($this->order_status == 2)
            || ($this->order_status == 3)
            || ($this->order_status == 6)
        )
            return "1";
        else
            return 0;
    }

    public function setDeliveryName($delivery_name)
    {
        $this->delivery_name = $delivery_name;
        $name = explode(" ", $this->delivery_name);
        $this->delivery_forename = $name[0];
        if (sizeof($name) > 0)
            $this->delivery_surname = $name[1];

    }

    public function getDeliveryName()
    {
        return $this->delivery_name;
    }

    public function getDeliveryForename()
    {
        return $this->delivery_forename;
    }

    public function getDeliverySurname()
    {
        return $this->delivery_surname;
    }

    public function setDeliveryCity($delivery_city)
    {
        $this->delivery_city = $delivery_city;
    }

    public function getDeliveryCity()
    {
        return $this->delivery_city;
    }

    public function setDeliveryState($delivery_state)
    {
        $this->delivery_state = $delivery_state;
    }

    public function getDeliveryState()
    {
        return $this->delivery_state;
    }

    public function setDeliveryPostcode($delivery_postcode)
    {
        $this->delivery_postcode = $delivery_postcode;
    }

    public function getDeliveryPostcode()
    {
        return $this->delivery_postcode;
    }

    public function setDeliveryCountryId($delivery_country_id)
    {
        $this->delivery_country_id = $delivery_country_id;
    }

    public function getDeliveryCountryId()
    {
        return $this->delivery_country_id;
    }

    public function setPassword($password)
    {
        $this->password = $password;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function getTel()
    {
        return $this->tel_1 . " " . $this->tel_2 . " " . $this->tel_3;
    }

    public function setTel1($tel_1)
    {
        $this->tel_1 = $tel_1;
    }

    public function getTel1()
    {
        return $this->tel_1;
    }

    public function setTel2($tel_2)
    {
        $this->tel_2 = $tel_2;
    }

    public function getTel2()
    {
        return $this->tel_2;
    }

    public function setTel3($tel_3)
    {
        $this->tel_3 = $tel_3;
    }

    public function getTel3()
    {
        return $this->tel_3;
    }

    public function setMobile($mobile)
    {
        $this->mobile = $mobile;
    }

    public function getMobile()
    {
        return $this->mobile;
    }

    public function setShipServiceLevel($ship_service_level)
    {
        $this->ship_service_level = $ship_service_level;
    }

    public function getShipServiceLevel()
    {
        return $this->ship_service_level;
    }

    public function setOrderType($order_type)
    {
        $this->order_type = $order_type;
    }

    public function getOrderType()
    {
        return $this->order_type;
    }

    public function setDeliveryMode($delivery_mode)
    {
        $this->delivery_mode = $delivery_mode;
    }

    public function getDeliveryMode()
    {
        return $this->delivery_mode;
    }

    public function setDeliveryCost($delivery_cost)
    {
        $this->delivery_cost = $delivery_cost;
    }

    public function getDeliveryCost()
    {
        return $this->delivery_cost;
    }

    public function setPromotionCode($promotion_code)
    {
        $this->promotion_code = $promotion_code;
    }

    public function getPromotionCode()
    {
        return $this->promotion_code;
    }

    public function setPaymentType($payment_type)
    {
        $this->payment_type = $payment_type;
    }

    public function getPaymentType()
    {
        return $this->payment_type;
    }

    public function setCardType($card_type)
    {
        $this->card_type = $card_type;
    }

    public function getCardType()
    {
        return $this->card_type;
    }

    public function setPayToAccount($pay_to_account)
    {
        $this->pay_to_account = $pay_to_account;
    }

    public function getPayToAccount()
    {
        return $this->pay_to_account;
    }

    public function setRiskVar1($risk_var_1)
    {
        $this->risk_var_1 = $risk_var_1;
    }

    public function getRiskVar1()
    {
        return $this->risk_var_1;
    }

    public function setRiskVar2($risk_var_2)
    {
        $this->risk_var_2 = $risk_var_2;
    }

    public function getRiskVar2()
    {
        return $this->risk_var_2;
    }

    public function setRiskVar3($risk_var_3)
    {
        $this->risk_var_3 = $risk_var_3;
    }

    public function getRiskVar3()
    {
        return $this->risk_var_3;
    }

    public function setRiskVar4($risk_var_4)
    {
        $this->risk_var_4 = $risk_var_4;
    }

    public function getRiskVar4()
    {
        return $this->risk_var_4;
    }

    public function setRiskVar5($risk_var_5)
    {
        $this->risk_var_5 = $risk_var_5;
    }

    public function getRiskVar5()
    {
        return $this->risk_var_5;
    }

    public function setRiskVar6($risk_var_6)
    {
        $this->risk_var_6 = $risk_var_6;
    }

    public function getRiskVar6()
    {
        return $this->risk_var_6;
    }

    public function setRiskVar7($risk_var_7)
    {
        $this->risk_var_7 = $risk_var_7;
    }

    public function getRiskVar7()
    {
        return $this->risk_var_7;
    }

    public function setRiskVar8($risk_var_8)
    {
        $this->risk_var_8 = $risk_var_8;
    }

    public function getRiskVar8()
    {
        return $this->risk_var_8;
    }

    public function setRiskVar9($risk_var_9)
    {
        $this->risk_var_9 = $risk_var_9;
    }

    public function getRiskVar9()
    {
        return $this->risk_var_9;
    }

    public function setRiskVar10($risk_var_10)
    {
        $this->risk_var_10 = $risk_var_10;
    }

    public function getRiskVar10()
    {
        return $this->risk_var_10;
    }

    public function setCardBin($card_bin)
    {
        $this->card_bin = $card_bin;
    }

    public function getCardBin()
    {
        return $this->card_bin;
    }

    public function setRiskRef1($risk_ref_1)
    {
        $this->risk_ref_1 = $risk_ref_1;
    }

    public function getRiskRef1()
    {
        return $this->risk_ref_1;
    }

    public function setRiskRef2($risk_ref_2)
    {
        $this->risk_ref_2 = $risk_ref_2;
    }

    public function getRiskRef2()
    {
        return $this->risk_ref_2;
    }

    public function setRiskRef3($risk_ref_3)
    {
        $this->risk_ref_3 = $risk_ref_3;
    }

    public function getRiskRef3()
    {
        return $this->risk_ref_3;
    }

    public function setRiskRef4($risk_ref_4)
    {
        $this->risk_ref_4 = $risk_ref_4;
    }

    public function getRiskRef4()
    {
        return $this->risk_ref_4;
    }

    public function setIpAddress($ip_address)
    {
        $this->ip_address = $ip_address;
    }

    public function getIpAddress()
    {
        return $this->ip_address;
    }

    public function setOrderStatus($order_status)
    {
        $this->order_status = $order_status;
    }

    public function getOrderStatus()
    {
        return $this->order_status;
    }

    public function setDispatchDate($dispatch_date)
    {
        $this->dispatch_date = $dispatch_date;
    }

    public function getDispatchDate()
    {
        return $this->dispatch_date;
    }

    public function setRefundStatus($refund_status)
    {
        $this->refund_status = $refund_status;
    }

    public function getRefundStatus()
    {
        return $this->refund_status;
    }

    public function setRefundDate($refund_date)
    {
        $this->refund_date = $refund_date;
    }

    public function getRefundDate()
    {
        return $this->refund_date;
    }

    public function setRefundReason($refund_reason)
    {
        $this->refund_reason = $refund_reason;
    }

    public function getRefundReason()
    {
        return $this->refund_reason;
    }

    public function getEmptyField()
    {
        $this->empty_field = "";
        return $this->empty_field;
    }

    public function getVerificationLevel()
    {
        if ($this->payment_gateway_id == 'moneybookers')
            return $this->risk_ref_1;
        else
            return "";
    }

    public function getFraudResult()
    {
        if ($this->payment_gateway_id != 'paypal')
            return $this->risk_ref_2;
        else
            return "";
    }

    public function getAvsResult()
    {
        if ($this->payment_gateway_id != 'paypal')
            return $this->risk_ref_1;
        else
            return "";
    }

    public function getProtectionEligibility()
    {
        if ($this->payment_gateway_id == 'paypal')
            return $this->risk_ref_1;
        else
            return "";
    }

    public function getProtectionEligibilityType()
    {
       if ($this->payment_gateway_id == 'paypal')
            return $this->risk_ref_2;
        else
            return "";
    }

    public function getAddressStatus()
    {
        return $this->risk_ref_3;
    }

    public function getPayerStatus()
    {
        return $this->risk_ref_4;
    }

    public function setChargebackCreateDate($chargeback_create_date)
    {
        $this->chargeback_create_date = $chargeback_create_date;
    }

    public function getChargebackCreateDate()
    {
        return $this->chargeback_create_date;
    }

    public function setChargebackReason($chargeback_reason)
    {
        $this->chargeback_reason = $chargeback_reason;
    }

    public function getChargebackReason()
    {
        return $this->chargeback_reason;
    }

    public function setChargebackRemark($chargeback_remark)
    {
        $this->chargeback_remark = $chargeback_remark;
    }

    public function getChargebackRemark()
    {
        return $this->chargeback_remark;
    }

    public function setChargebackStatus($chargeback_status)
    {
        $this->chargeback_status = $chargeback_status;
    }

    public function getChargebackStatus()
    {
        return $this->chargeback_status;
    }

}

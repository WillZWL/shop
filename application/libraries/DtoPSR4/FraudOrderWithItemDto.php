<?php
class FraudOrderWithItemDto
{
    private $id;
    private $so_no;
    private $hold_date;
    private $hold_staff;
    private $order_create_date;
    private $payment_gateway_id;
    private $prod_name;
    private $category;
    private $currency_id;
    private $item_price;
    private $item_quantity;
    private $order_total_item;
    private $order_value;
    private $forename;
    private $surname;
    private $client_id;
    private $email;
    private $bill_name;
    private $bill_company;
    private $bill_address_1;
    private $bill_address_2;
    private $bill_address_3;
    private $bill_city;
    private $bill_state;
    private $bill_postcode;
    private $bill_country_id;
    private $delivery_name;
    private $delivery_company;
    private $delivery_address_1;
    private $delivery_address_2;
    private $delivery_address_3;
    private $delivery_state;
    private $delivery_postcode;
    private $delivery_country_id;
    private $password;
    private $tel__1;
    private $tel__2;
    private $tel__3;
    private $mobile;
    private $platform_id;
    private $card_id;
    private $card_type;
    private $risk_var_1;
    private $risk_var_2;
    private $risk_var_3;
    private $risk_var_4;
    private $risk_var_5;
    private $risk_var_6;
    private $risk_var_7;
    private $risk_var_8;
    private $risk_var_9;
    private $risk_var_1_0;
    private $card_bin;
    private $verification_level;
    private $fraud_result;
    private $a_v_s_result;
    private $protection_eligibility;
    private $protection_eligibility_type;
    private $address_status;
    private $payer_status;
    private $create_at;
    private $dispatch_date;
    private $refund_status;
    private $refund_date;
    private $description;

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setSoNo($so_no)
    {
        $this->so_no = $so_no;
    }

    public function getSoNo()
    {
        return $this->so_no;
    }

    public function setHoldDate($hold_date)
    {
        $this->hold_date = $hold_date;
    }

    public function getHoldDate()
    {
        return $this->hold_date;
    }

    public function setHoldStaff($hold_staff)
    {
        $this->hold_staff = $hold_staff;
    }

    public function getHoldStaff()
    {
        return $this->hold_staff;
    }

    public function setOrderCreateDate($order_create_date)
    {
        $this->order_create_date = $order_create_date;
    }

    public function getOrderCreateDate()
    {
        return $this->order_create_date;
    }

    public function setPaymentGatewayId($payment_gateway_id)
    {
        $this->payment_gateway_id = $payment_gateway_id;
    }

    public function getPaymentGatewayId()
    {
        return $this->payment_gateway_id;
    }

    public function setProdName($prod_name)
    {
        $this->prod_name = $prod_name;
    }

    public function getProdName()
    {
        return $this->prod_name;
    }

    public function setCategory($category)
    {
        $this->category = $category;
    }

    public function getCategory()
    {
        return $this->category;
    }

    public function setCurrencyId($currency_id)
    {
        $this->currency_id = $currency_id;
    }

    public function getCurrencyId()
    {
        return $this->currency_id;
    }

    public function setItemPrice($item_price)
    {
        $this->item_price = $item_price;
    }

    public function getItemPrice()
    {
        return $this->item_price;
    }

    public function setItemQuantity($item_quantity)
    {
        $this->item_quantity = $item_quantity;
    }

    public function getItemQuantity()
    {
        return $this->item_quantity;
    }

    public function setOrderTotalItem($order_total_item)
    {
        $this->order_total_item = $order_total_item;
    }

    public function getOrderTotalItem()
    {
        return $this->order_total_item;
    }

    public function setOrderValue($order_value)
    {
        $this->order_value = $order_value;
    }

    public function getOrderValue()
    {
        return $this->order_value;
    }

    public function setForename($forename)
    {
        $this->forename = $forename;
    }

    public function getForename()
    {
        return $this->forename;
    }

    public function setSurname($surname)
    {
        $this->surname = $surname;
    }

    public function getSurname()
    {
        return $this->surname;
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

    public function setBillName($bill_name)
    {
        $this->bill_name = $bill_name;
    }

    public function getBillName()
    {
        return $this->bill_name;
    }

    public function setBillCompany($bill_company)
    {
        $this->bill_company = $bill_company;
    }

    public function getBillCompany()
    {
        return $this->bill_company;
    }

    public function setBillAddress1($bill_address_1)
    {
        $this->bill_address_1 = $bill_address_1;
    }

    public function getBillAddress1()
    {
        return $this->bill_address_1;
    }

    public function setBillAddress2($bill_address_2)
    {
        $this->bill_address_2 = $bill_address_2;
    }

    public function getBillAddress2()
    {
        return $this->bill_address_2;
    }

    public function setBillAddress3($bill_address_3)
    {
        $this->bill_address_3 = $bill_address_3;
    }

    public function getBillAddress3()
    {
        return $this->bill_address_3;
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

    public function setDeliveryName($delivery_name)
    {
        $this->delivery_name = $delivery_name;
    }

    public function getDeliveryName()
    {
        return $this->delivery_name;
    }

    public function setDeliveryCompany($delivery_company)
    {
        $this->delivery_company = $delivery_company;
    }

    public function getDeliveryCompany()
    {
        return $this->delivery_company;
    }

    public function setDeliveryAddress1($delivery_address_1)
    {
        $this->delivery_address_1 = $delivery_address_1;
    }

    public function getDeliveryAddress1()
    {
        return $this->delivery_address_1;
    }

    public function setDeliveryAddress2($delivery_address_2)
    {
        $this->delivery_address_2 = $delivery_address_2;
    }

    public function getDeliveryAddress2()
    {
        return $this->delivery_address_2;
    }

    public function setDeliveryAddress3($delivery_address_3)
    {
        $this->delivery_address_3 = $delivery_address_3;
    }

    public function getDeliveryAddress3()
    {
        return $this->delivery_address_3;
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

    public function setTel1($tel__1)
    {
        $this->tel__1 = $tel__1;
    }

    public function getTel1()
    {
        return $this->tel__1;
    }

    public function setTel2($tel__2)
    {
        $this->tel__2 = $tel__2;
    }

    public function getTel2()
    {
        return $this->tel__2;
    }

    public function setTel3($tel__3)
    {
        $this->tel__3 = $tel__3;
    }

    public function getTel3()
    {
        return $this->tel__3;
    }

    public function setMobile($mobile)
    {
        $this->mobile = $mobile;
    }

    public function getMobile()
    {
        return $this->mobile;
    }

    public function setPlatformId($platform_id)
    {
        $this->platform_id = $platform_id;
    }

    public function getPlatformId()
    {
        return $this->platform_id;
    }

    public function setCardId($card_id)
    {
        $this->card_id = $card_id;
    }

    public function getCardId()
    {
        return $this->card_id;
    }

    public function setCardType($card_type)
    {
        $this->card_type = $card_type;
    }

    public function getCardType()
    {
        return $this->card_type;
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

    public function setRiskVar10($risk_var_1_0)
    {
        $this->risk_var_1_0 = $risk_var_1_0;
    }

    public function getRiskVar10()
    {
        return $this->risk_var_1_0;
    }

    public function setCardBin($card_bin)
    {
        $this->card_bin = $card_bin;
    }

    public function getCardBin()
    {
        return $this->card_bin;
    }

    public function setVerificationLevel($verification_level)
    {
        $this->verification_level = $verification_level;
    }

    public function getVerificationLevel()
    {
        return $this->verification_level;
    }

    public function setFraudResult($fraud_result)
    {
        $this->fraud_result = $fraud_result;
    }

    public function getFraudResult()
    {
        return $this->fraud_result;
    }

    public function setAVSResult($a_v_s_result)
    {
        $this->a_v_s_result = $a_v_s_result;
    }

    public function getAVSResult()
    {
        return $this->a_v_s_result;
    }

    public function setProtectionEligibility($protection_eligibility)
    {
        $this->protection_eligibility = $protection_eligibility;
    }

    public function getProtectionEligibility()
    {
        return $this->protection_eligibility;
    }

    public function setProtectionEligibilityType($protection_eligibility_type)
    {
        $this->protection_eligibility_type = $protection_eligibility_type;
    }

    public function getProtectionEligibilityType()
    {
        return $this->protection_eligibility_type;
    }

    public function setAddressStatus($address_status)
    {
        $this->address_status = $address_status;
    }

    public function getAddressStatus()
    {
        return $this->address_status;
    }

    public function setPayerStatus($payer_status)
    {
        $this->payer_status = $payer_status;
    }

    public function getPayerStatus()
    {
        return $this->payer_status;
    }

    public function setCreateAt($create_at)
    {
        $this->create_at = $create_at;
    }

    public function getCreateAt()
    {
        return $this->create_at;
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

    public function setDescription($description)
    {
        $this->description = $description;
    }

    public function getDescription()
    {
        return $this->description;
    }

}

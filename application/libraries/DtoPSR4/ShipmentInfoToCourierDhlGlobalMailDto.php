<?php
class ShipmentInfoToCourierDhlGlobalMailDto
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
    protected $weight;
    protected $declared_value;
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
    protected $pt_so_no;

    public function setPlatformId($value)
    {
        $this->platform_id = $value;
    }

    public function getPlatformId()
    {
        return $this->platform_id;
    }

    public function setShNo($value)
    {
        $this->sh_no = $value;
    }

    public function getShNo()
    {
        return $this->sh_no;
    }

    public function setSoNo($value)
    {
        $this->so_no = $value;
    }

    public function getSoNo()
    {
        return $this->so_no;
    }

    public function setPlatformOrderId($value)
    {
        $this->platform_order_id = $value;
    }

    public function getPlatformOrderId()
    {
        return $this->platform_order_id;
    }

    public function setOrderCreateDate($value)
    {
        $this->order_create_date = $value;
    }

    public function getOrderCreateDate()
    {
        return $this->order_create_date;
    }

    public function setBillName($value)
    {
        $this->bill_name = $value;
    }

    public function getBillName()
    {
        return $this->bill_name;
    }

    public function setBillCompany($value)
    {
        $this->bill_company = $value;
    }

    public function getBillCompany()
    {
        return $this->bill_company;
    }

    public function setBillAddress($value)
    {
        $this->bill_address = $value;
    }

    public function getBillAddress()
    {
        return $this->bill_address;
    }

    public function setBillPostcode($value)
    {
        $this->bill_postcode = $value;
    }

    public function getBillPostcode()
    {
        return $this->bill_postcode;
    }

    public function setBillCity($value)
    {
        $this->bill_city = $value;
    }

    public function getBillCity()
    {
        return $this->bill_city;
    }

    public function setBillState($value)
    {
        $this->bill_state = $value;
    }

    public function getBillState()
    {
        return $this->bill_state;
    }

    public function setBillCountryId($value)
    {
        $this->bill_country_id = $value;
    }

    public function getBillCountryId()
    {
        return $this->bill_country_id;
    }

    public function setEmail($value)
    {
        $this->email = $value;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function setTel($value)
    {
        $this->tel = $value;
    }

    public function getTel()
    {
        return $this->tel;
    }

    public function setDeliveryName($value)
    {
        $this->delivery_name = $value;
    }

    public function getDeliveryName()
    {
        return $this->delivery_name;
    }

    public function setDeliveryCompany($value)
    {
        $this->delivery_company = $value;
    }

    public function getDeliveryCompany()
    {
        return $this->delivery_company;
    }

    public function setDeliveryAddress($value)
    {
        $this->delivery_address = $value;
    }

    public function getDeliveryAddress()
    {
        return $this->delivery_address;
    }

    public function setDeliveryPostcode($value)
    {
        $this->delivery_postcode = $value;
    }

    public function getDeliveryPostcode()
    {
        return $this->delivery_postcode;
    }

    public function setDeliveryCity($value)
    {
        $this->delivery_city = $value;
    }

    public function getDeliveryCity()
    {
        return $this->delivery_city;
    }

    public function setDeliveryState($value)
    {
        $this->delivery_state = $value;
    }

    public function getDeliveryState()
    {
        return $this->delivery_state;
    }

    public function setDeliveryCountryId($value)
    {
        $this->delivery_country_id = $value;
    }

    public function getDeliveryCountryId()
    {
        return $this->delivery_country_id;
    }

    public function setLineNo($value)
    {
        $this->line_no = $value;
    }

    public function getLineNo()
    {
        return $this->line_no;
    }

    public function setSku($value)
    {
        $this->sku = $value;
    }

    public function getSku()
    {
        return $this->sku;
    }

    public function setProdName($value)
    {
        $this->prod_name = $value;
    }

    public function getProdName()
    {
        return $this->prod_name;
    }

    public function setCurrencyId($value)
    {
        $this->currency_id = $value;
    }

    public function getCurrencyId()
    {
        return $this->currency_id;
    }

    public function setUnitPrice($value)
    {
        $this->unit_price = $value;
    }

    public function getUnitPrice()
    {
        return $this->unit_price;
    }

    public function setQty($value)
    {
        $this->qty = $value;
    }

    public function getQty()
    {
        return $this->qty;
    }

    public function setDeliveryCharge($value)
    {
        $this->delivery_charge = $value;
    }

    public function getDeliveryCharge()
    {
        return $this->delivery_charge;
    }

    public function setAmount($value)
    {
        $this->amount = $value;
    }

    public function getAmount()
    {
        return $this->amount;
    }

    public function setDeliveryTypeId($value)
    {
        $this->delivery_type_id = $value;
    }

    public function getDeliveryTypeId()
    {
        return $this->delivery_type_id;
    }

    public function setPromotionCode($value)
    {
        $this->promotion_code = $value;
    }

    public function getPromotionCode()
    {
        return $this->promotion_code;
    }

    public function setDeclaredValue($value){

        $this->declared_value = $value;
    }

    public function getDeclaredValue(){

        return $this->declared_value;
    }

    public function setWeight($value){

        $this->weight = $value;
    }

    public function getWeight(){

        return $this->weight;
    }

    public function setBillDetail($value)
    {
        $this->bill_detail = $value;
    }

    public function getBillDetail()
    {
        return $this->bill_detail;
    }

    public function setTotalItemCount($value)
    {
        $this->total_item_count = $value;
    }

    public function getTotalItemCount()
    {
        return $this->total_item_count;
    }

    public function setItemNo($value)
    {
        $this->item_no = $value;
    }

    public function getItemNo()
    {
        return $this->item_no;
    }

    public function setSubtotal($value)
    {
        $this->subtotal = $value;
    }

    public function getSubtotal()
    {
        return $this->subtotal;
    }

    public function setShipOption($value)
    {
        $this->ship_option = $value;
    }

    public function getShipOption()
    {
        return $this->ship_option;
    }

    public function setActualCost($value)
    {
        $this->actual_cost = $value;
    }

    public function getActualCost()
    {
        return $this->actual_cost;
    }

    public function getOfflineFee()
    {
        return $this->offline_fee;
    }

    public function setOfflineFee($value)
    {
        $this->offline_fee = $value;
        return $this;
    }

    public function getDeliveryAddress1()
    {
        return $this->delivery_address_1;
    }

    public function setDeliveryAddress1($value)
    {
        $this->delivery_address_1 = $value;
    }

    public function getDeliveryAddress2()
    {
        return $this->delivery_address_2;
    }

    public function setDeliveryAddress2($value)
    {
        $this->delivery_address_2 = $value;
    }

    public function getDeliveryAddress3()
    {
        return $this->delivery_address_3;
    }

    public function setDeliveryAddress3($value)
    {
        $this->delivery_address_3 = $value;
    }

    public function getCcDesc()
    {
        return $this->cc_desc;
    }

    public function setCcDesc($value)
    {
        $this->cc_desc = $value;
    }

    public function getCcCode()
    {
        return $this->cc_code;
    }

    public function setCcCode($value)
    {
        $this->cc_code = $value;
    }

    public function getCourierId()
    {
        return $this->courier_id;
    }

    public function setCourierId($value)
    {
        $this->courier_id = $value;
    }

    public function getPtSoNo()
    {
        return $this->pt_so_no;
    }

    public function setPtSoNo($value)
    {
        $this->pt_so_no = $value;
    }

}
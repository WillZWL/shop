<?php
class ShipmentInfoToCourierDto
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
    protected $offline_fee = "0.00";
    protected $delivery_address_1;
    protected $delivery_address_2;
    protected $delivery_address_3;
    protected $cc_desc;
    protected $cc_code;
    protected $courier_id;

    public function setPlatformId($platform_id)
    {
        $this->platform_id = $platform_id;
    }

    public function getPlatformId()
    {
        return $this->platform_id;
    }

    public function setShNo($sh_no)
    {
        $this->sh_no = $sh_no;
    }

    public function getShNo()
    {
        return $this->sh_no;
    }

    public function setSoNo($so_no)
    {
        $this->so_no = $so_no;
    }

    public function getSoNo()
    {
        return $this->so_no;
    }

    public function setPlatformOrderId($platform_order_id)
    {
        $this->platform_order_id = $platform_order_id;
    }

    public function getPlatformOrderId()
    {
        return $this->platform_order_id;
    }

    public function setOrderCreateDate($order_create_date)
    {
        $this->order_create_date = $order_create_date;
    }

    public function getOrderCreateDate()
    {
        return $this->order_create_date;
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

    public function setBillAddress($bill_address)
    {
        $this->bill_address = $bill_address;
    }

    public function getBillAddress()
    {
        return $this->bill_address;
    }

    public function setBillPostcode($bill_postcode)
    {
        $this->bill_postcode = $bill_postcode;
    }

    public function getBillPostcode()
    {
        return $this->bill_postcode;
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

    public function setBillCountryId($bill_country_id)
    {
        $this->bill_country_id = $bill_country_id;
    }

    public function getBillCountryId()
    {
        return $this->bill_country_id;
    }

    public function setEmail($email)
    {
        $this->email = $email;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function setTel($tel)
    {
        $this->tel = $tel;
    }

    public function getTel()
    {
        return $this->tel;
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

    public function setDeliveryAddress($delivery_address)
    {
        $this->delivery_address = $delivery_address;
    }

    public function getDeliveryAddress()
    {
        return $this->delivery_address;
    }

    public function setDeliveryPostcode($delivery_postcode)
    {
        $this->delivery_postcode = $delivery_postcode;
    }

    public function getDeliveryPostcode()
    {
        return $this->delivery_postcode;
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

    public function setDeliveryCountryId($delivery_country_id)
    {
        $this->delivery_country_id = $delivery_country_id;
    }

    public function getDeliveryCountryId()
    {
        return $this->delivery_country_id;
    }

    public function setLineNo($line_no)
    {
        $this->line_no = $line_no;
    }

    public function getLineNo()
    {
        return $this->line_no;
    }

    public function setSku($sku)
    {
        $this->sku = $sku;
    }

    public function getSku()
    {
        return $this->sku;
    }

    public function setProdName($prod_name)
    {
        $this->prod_name = $prod_name;
    }

    public function getProdName()
    {
        return $this->prod_name;
    }

    public function setCurrencyId($currency_id)
    {
        $this->currency_id = $currency_id;
    }

    public function getCurrencyId()
    {
        return $this->currency_id;
    }

    public function setUnitPrice($unit_price)
    {
        $this->unit_price = $unit_price;
    }

    public function getUnitPrice()
    {
        return $this->unit_price;
    }

    public function setQty($qty)
    {
        $this->qty = $qty;
    }

    public function getQty()
    {
        return $this->qty;
    }

    public function setDeliveryCharge($delivery_charge)
    {
        $this->delivery_charge = $delivery_charge;
    }

    public function getDeliveryCharge()
    {
        return $this->delivery_charge;
    }

    public function setAmount($amount)
    {
        $this->amount = $amount;
    }

    public function getAmount()
    {
        return $this->amount;
    }

    public function setDeliveryTypeId($delivery_type_id)
    {
        $this->delivery_type_id = $delivery_type_id;
    }

    public function getDeliveryTypeId()
    {
        return $this->delivery_type_id;
    }

    public function setPromotionCode($promotion_code)
    {
        $this->promotion_code = $promotion_code;
    }

    public function getPromotionCode()
    {
        return $this->promotion_code;
    }

    public function setBillDetail($bill_detail)
    {
        $this->bill_detail = $bill_detail;
    }

    public function getBillDetail()
    {
        return $this->bill_detail;
    }

    public function setTotalItemCount($total_item_count)
    {
        $this->total_item_count = $total_item_count;
    }

    public function getTotalItemCount()
    {
        return $this->total_item_count;
    }

    public function setItemNo($item_no)
    {
        $this->item_no = $item_no;
    }

    public function getItemNo()
    {
        return $this->item_no;
    }

    public function setSubtotal($subtotal)
    {
        $this->subtotal = $subtotal;
    }

    public function getSubtotal()
    {
        return $this->subtotal;
    }

    public function setShipOption($ship_option)
    {
        $this->ship_option = $ship_option;
    }

    public function getShipOption()
    {
        return $this->ship_option;
    }

    public function setActualCost($actual_cost)
    {
        $this->actual_cost = $actual_cost;
    }

    public function getActualCost()
    {
        return $this->actual_cost;
    }

    public function setOfflineFee($offline_fee)
    {
        $this->offline_fee = $offline_fee;
    }

    public function getOfflineFee()
    {
        return $this->offline_fee;
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

    public function setCcDesc($cc_desc)
    {
        $this->cc_desc = $cc_desc;
    }

    public function getCcDesc()
    {
        return $this->cc_desc;
    }

    public function setCcCode($cc_code)
    {
        $this->cc_code = $cc_code;
    }

    public function getCcCode()
    {
        return $this->cc_code;
    }

    public function setCourierId($courier_id)
    {
        $this->courier_id = $courier_id;
    }

    public function getCourierId()
    {
        return $this->courier_id;
    }

}

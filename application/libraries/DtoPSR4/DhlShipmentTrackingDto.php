<?php
class DhlShipmentTrackingDto
{
    private $sh_no;
    private $tracking_no;
    private $delivery_name;
    private $delivery_address;
    private $delivery_city;
    private $delivery_postcode;
    private $delivery_country_id;
    private $cc_desc;
    private $amount;
    private $so_no;
    private $currency_id;
    private $acount_number;
    private $customer_ref;
    private $product_code;
    private $delivery_state;

    public function setShNo($sh_no)
    {
        $this->sh_no = $sh_no;
    }

    public function getShNo()
    {
        return $this->sh_no;
    }

    public function setTrackingNo($tracking_no)
    {
        $this->tracking_no = $tracking_no;
    }

    public function getTrackingNo()
    {
        return $this->tracking_no;
    }

    public function setDeliveryName($delivery_name)
    {
        $this->delivery_name = $delivery_name;
    }

    public function getDeliveryName()
    {
        return $this->delivery_name;
    }

    public function setDeliveryAddress($delivery_address)
    {
        $this->delivery_address = $delivery_address;
    }

    public function getDeliveryAddress()
    {
        return $this->delivery_address;
    }

    public function setDeliveryCity($delivery_city)
    {
        $this->delivery_city = $delivery_city;
    }

    public function getDeliveryCity()
    {
        return $this->delivery_city;
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

    public function setCcDesc($cc_desc)
    {
        $this->cc_desc = $cc_desc;
    }

    public function getCcDesc()
    {
        return $this->cc_desc;
    }

    public function setAmount($amount)
    {
        $this->amount = $amount;
    }

    public function getAmount()
    {
        return $this->amount;
    }

    public function setSoNo($so_no)
    {
        $this->so_no = $so_no;
    }

    public function getSoNo()
    {
        return $this->so_no;
    }

    public function setCurrencyId($currency_id)
    {
        $this->currency_id = $currency_id;
    }

    public function getCurrencyId()
    {
        return $this->currency_id;
    }

    public function setAcountNumber($acount_number)
    {
        $this->acount_number = $acount_number;
    }

    public function getAcountNumber()
    {
        return $this->acount_number;
    }

    public function setCustomerRef($customer_ref)
    {
        $this->customer_ref = $customer_ref;
    }

    public function getCustomerRef()
    {
        return $this->customer_ref;
    }

    public function setProductCode($product_code)
    {
        $this->product_code = $product_code;
    }

    public function getProductCode()
    {
        return $this->product_code;
    }

    public function setDeliveryState($delivery_state)
    {
        $this->delivery_state = $delivery_state;
    }

    public function getDeliveryState()
    {
        return $this->delivery_state;
    }

}

<?php
class SoWithPaymentDto
{
    private $order_create_date;
    private $platform_id;
    private $bill_country_id;
    private $delivery_country_id;
    private $country_by_ip;
    private $so_no;
    private $amount;
    private $card_type;
    private $fail_reason;

    public function setOrderCreateDate($order_create_date)
    {
        $this->order_create_date = $order_create_date;
    }

    public function getOrderCreateDate()
    {
        return $this->order_create_date;
    }

    public function setPlatformId($platform_id)
    {
        $this->platform_id = $platform_id;
    }

    public function getPlatformId()
    {
        return $this->platform_id;
    }

    public function setBillCountryId($bill_country_id)
    {
        $this->bill_country_id = $bill_country_id;
    }

    public function getBillCountryId()
    {
        return $this->bill_country_id;
    }

    public function setDeliveryCountryId($delivery_country_id)
    {
        $this->delivery_country_id = $delivery_country_id;
    }

    public function getDeliveryCountryId()
    {
        return $this->delivery_country_id;
    }

    public function setCountryByIp($country_by_ip)
    {
        $this->country_by_ip = $country_by_ip;
    }

    public function getCountryByIp()
    {
        return $this->country_by_ip;
    }

    public function setSoNo($so_no)
    {
        $this->so_no = $so_no;
    }

    public function getSoNo()
    {
        return $this->so_no;
    }

    public function setAmount($amount)
    {
        $this->amount = $amount;
    }

    public function getAmount()
    {
        return $this->amount;
    }

    public function setCardType($card_type)
    {
        $this->card_type = $card_type;
    }

    public function getCardType()
    {
        return $this->card_type;
    }

    public function setFailReason($fail_reason)
    {
        $this->fail_reason = $fail_reason;
    }

    public function getFailReason()
    {
        return $this->fail_reason;
    }

}

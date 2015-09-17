<?php
class CountryPmgwCardDto
{
    private $country_id;
    private $code;
    private $payment_gateway_id = "moneybookers";
    private $card_id;
    private $card_name;
    private $card_image;
    private $status = "1";
    private $create_on = "0000-00-00 00:00:00";
    private $create_at;
    private $create_by;
    private $modify_on;
    private $modify_at;
    private $modify_by;

    public function setCountryId($country_id)
    {
        $this->country_id = $country_id;
    }

    public function getCountryId()
    {
        return $this->country_id;
    }

    public function setCode($code)
    {
        $this->code = $code;
    }

    public function getCode()
    {
        return $this->code;
    }

    public function setPaymentGatewayId($payment_gateway_id)
    {
        $this->payment_gateway_id = $payment_gateway_id;
    }

    public function getPaymentGatewayId()
    {
        return $this->payment_gateway_id;
    }

    public function setCardId($card_id)
    {
        $this->card_id = $card_id;
    }

    public function getCardId()
    {
        return $this->card_id;
    }

    public function setCardName($card_name)
    {
        $this->card_name = $card_name;
    }

    public function getCardName()
    {
        return $this->card_name;
    }

    public function setCardImage($card_image)
    {
        $this->card_image = $card_image;
    }

    public function getCardImage()
    {
        return $this->card_image;
    }

    public function setStatus($status)
    {
        $this->status = $status;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function setCreateOn($create_on)
    {
        $this->create_on = $create_on;
    }

    public function getCreateOn()
    {
        return $this->create_on;
    }

    public function setCreateAt($create_at)
    {
        $this->create_at = $create_at;
    }

    public function getCreateAt()
    {
        return $this->create_at;
    }

    public function setCreateBy($create_by)
    {
        $this->create_by = $create_by;
    }

    public function getCreateBy()
    {
        return $this->create_by;
    }

    public function setModifyOn($modify_on)
    {
        $this->modify_on = $modify_on;
    }

    public function getModifyOn()
    {
        return $this->modify_on;
    }

    public function setModifyAt($modify_at)
    {
        $this->modify_at = $modify_at;
    }

    public function getModifyAt()
    {
        return $this->modify_at;
    }

    public function setModifyBy($modify_by)
    {
        $this->modify_by = $modify_by;
    }

    public function getModifyBy()
    {
        return $this->modify_by;
    }

}

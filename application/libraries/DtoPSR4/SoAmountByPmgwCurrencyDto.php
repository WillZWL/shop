<?php
class SoAmountByPmgwCurrencyDto
{
    private $so_count;
    private $so_amount;
    private $currency_id;
    private $payment_gateway_id;
    private $pmgw_name;
    private $platform_country_id;

    public function setSoCount($so_count)
    {
        $this->so_count = $so_count;
    }

    public function getSoCount()
    {
        return $this->so_count;
    }

    public function setSoAmount($so_amount)
    {
        $this->so_amount = $so_amount;
    }

    public function getSoAmount()
    {
        return $this->so_amount;
    }

    public function setCurrencyId($currency_id)
    {
        $this->currency_id = $currency_id;
    }

    public function getCurrencyId()
    {
        return $this->currency_id;
    }

    public function setPaymentGatewayId($payment_gateway_id)
    {
        $this->payment_gateway_id = $payment_gateway_id;
    }

    public function getPaymentGatewayId()
    {
        return $this->payment_gateway_id;
    }

    public function setPmgwName($pmgw_name)
    {
        $this->pmgw_name = $pmgw_name;
    }

    public function getPmgwName()
    {
        return $this->pmgw_name;
    }

    public function setPlatformCountryId($platform_country_id)
    {
        $this->platform_country_id = $platform_country_id;
    }

    public function getPlatformCountryId()
    {
        return $this->platform_country_id;
    }

}

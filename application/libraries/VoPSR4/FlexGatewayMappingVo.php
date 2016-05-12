<?php

class FlexGatewayMappingVo extends \BaseVo
{
    private $gateway_id;
    private $currency_id;
    private $gateway_code;
    private $ria = '1';

    protected $primary_key = ['gateway_id', 'currency_id'];
    protected $increment_field = '';

    public function setGatewayId($gateway_id)
    {
        if ($gateway_id !== null) {
            $this->gateway_id = $gateway_id;
        }
    }

    public function getGatewayId()
    {
        return $this->gateway_id;
    }

    public function setCurrencyId($currency_id)
    {
        if ($currency_id !== null) {
            $this->currency_id = $currency_id;
        }
    }

    public function getCurrencyId()
    {
        return $this->currency_id;
    }

    public function setGatewayCode($gateway_code)
    {
        if ($gateway_code !== null) {
            $this->gateway_code = $gateway_code;
        }
    }

    public function getGatewayCode()
    {
        return $this->gateway_code;
    }

    public function setRia($ria)
    {
        if ($ria !== null) {
            $this->ria = $ria;
        }
    }

    public function getRia()
    {
        return $this->ria;
    }

}

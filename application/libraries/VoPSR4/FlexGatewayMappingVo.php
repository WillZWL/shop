<?php
class FlexGatewayMappingVo extends \BaseVo
{
    private $id;
    private $gateway_id;
    private $currency_id;
    private $gateway_code;
    private $ria;

    protected $increment_field = "";

    public function getId()
    {
        return $this->id;
    }

    public function setId($value)
    {
        $this->id = $value;
        return $this;
    }

    public function getGatewayId()
    {
        return $this->gateway_id;
    }

    public function setGatewayId($value)
    {
        $this->gateway_id = $value;
        return $this;
    }

    public function getCurrencyId()
    {
        return $this->currency_id;
    }

    public function setCurrencyId($value)
    {
        $this->currency_id = $value;
        return $this;
    }

    public function getGatewayCode()
    {
        return $this->gateway_code;
    }

    public function setGatewayCode($value)
    {
        $this->gateway_code = $value;
        return $this;
    }

    public function getRia()
    {
        return $this->ria;
    }

    public function setRia($value)
    {
        return $this->ria = $value;
    }




}

?>
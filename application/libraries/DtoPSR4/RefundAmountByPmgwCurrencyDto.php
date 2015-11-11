<?php
class RefundAmountByPmgwCurrencyDto
{
    //class variable
    private $refund_count;
    private $refund_amount;
    private $currency_id;
    private $payment_gateway_id;
    private $pmgw_name;
    private $refund_reason;
    private $platfrom_country_id;

    //instance method
    public function getRefundCount()
    {
        return $this->refund_count;
    }

    public function setRefundCount($value)
    {
        $this->refund_count = $value;
    }

    public function getRefundAmount()
    {
        return $this->refund_amount;
    }

    public function setRefundAmount($value)
    {
        $this->refund_amount = $value;
    }

    public function getCurrencyId()
    {
        return $this->currency_id;
    }

    public function setCurrencyId($value)
    {
        $this->currency_id = $value;
    }

    public function getPaymentGatewayId()
    {
        return $this->payment_gateway_id;
    }

    public function setPaymentGatewayId($value)
    {
        $this->payment_gateway_id = $value;
    }

    public function getPmgwName()
    {
        return $this->pmgw_name;
    }

    public function setPmgwName($value)
    {
        $this->pmgw_name = $value;
    }

    public function getRefundReason()
    {
        return $this->refund_reason;
    }

    public function setRefundReason($value)
    {
        $this->refund_reason = $value;
    }

    public function getPlatformCountryId()
    {
        return $this->platform_country_id;
    }

    public function setPlatformCountryId($value)
    {
        $this->platform_country_id = $value;
    }
}


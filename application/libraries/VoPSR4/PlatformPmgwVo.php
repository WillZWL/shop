<?php
class PlatformPmgwVo extends \BaseVo
{
    private $id;
    private $platform_id;
    private $sequence = '1';
    private $payment_gateway_id = 'paypal';
    private $pmgw_ref_currency_id = '';
    private $ref_from_amt = '0';
    private $ref_to_amt_exclusive = '0';
    private $status = '1';


    public function setId($id)
    {
        if ($id !== null) {
            $this->id = $id;
        }
    }

    public function getId()
    {
        return $this->id;
    }

    public function setPlatformId($platform_id)
    {
        if ($platform_id !== null) {
            $this->platform_id = $platform_id;
        }
    }

    public function getPlatformId()
    {
        return $this->platform_id;
    }

    public function setSequence($sequence)
    {
        if ($sequence !== null) {
            $this->sequence = $sequence;
        }
    }

    public function getSequence()
    {
        return $this->sequence;
    }

    public function setPaymentGatewayId($payment_gateway_id)
    {
        if ($payment_gateway_id !== null) {
            $this->payment_gateway_id = $payment_gateway_id;
        }
    }

    public function getPaymentGatewayId()
    {
        return $this->payment_gateway_id;
    }

    public function setPmgwRefCurrencyId($pmgw_ref_currency_id)
    {
        if ($pmgw_ref_currency_id !== null) {
            $this->pmgw_ref_currency_id = $pmgw_ref_currency_id;
        }
    }

    public function getPmgwRefCurrencyId()
    {
        return $this->pmgw_ref_currency_id;
    }

    public function setRefFromAmt($ref_from_amt)
    {
        if ($ref_from_amt !== null) {
            $this->ref_from_amt = $ref_from_amt;
        }
    }

    public function getRefFromAmt()
    {
        return $this->ref_from_amt;
    }

    public function setRefToAmtExclusive($ref_to_amt_exclusive)
    {
        if ($ref_to_amt_exclusive !== null) {
            $this->ref_to_amt_exclusive = $ref_to_amt_exclusive;
        }
    }

    public function getRefToAmtExclusive()
    {
        return $this->ref_to_amt_exclusive;
    }

    public function setStatus($status)
    {
        if ($status !== null) {
            $this->status = $status;
        }
    }

    public function getStatus()
    {
        return $this->status;
    }

}

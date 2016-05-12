<?php

class FlexPmgwTransactionsVo extends \BaseVo
{
    private $so_no;
    private $payment_gateway_id;
    private $txn_id;
    private $payment_type;
    private $txn_time;
    private $currency_id;
    private $amount;
    private $commission = '0.00';
    private $ext_ref;

    protected $primary_key = ['so_no', 'payment_type'];
    protected $increment_field = '';

    public function setSoNo($so_no)
    {
        if ($so_no !== null) {
            $this->so_no = $so_no;
        }
    }

    public function getSoNo()
    {
        return $this->so_no;
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

    public function setTxnId($txn_id)
    {
        if ($txn_id !== null) {
            $this->txn_id = $txn_id;
        }
    }

    public function getTxnId()
    {
        return $this->txn_id;
    }

    public function setPaymentType($payment_type)
    {
        if ($payment_type !== null) {
            $this->payment_type = $payment_type;
        }
    }

    public function getPaymentType()
    {
        return $this->payment_type;
    }

    public function setTxnTime($txn_time)
    {
        if ($txn_time !== null) {
            $this->txn_time = $txn_time;
        }
    }

    public function getTxnTime()
    {
        return $this->txn_time;
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

    public function setAmount($amount)
    {
        if ($amount !== null) {
            $this->amount = $amount;
        }
    }

    public function getAmount()
    {
        return $this->amount;
    }

    public function setCommission($commission)
    {
        if ($commission !== null) {
            $this->commission = $commission;
        }
    }

    public function getCommission()
    {
        return $this->commission;
    }

    public function setExtRef($ext_ref)
    {
        if ($ext_ref !== null) {
            $this->ext_ref = $ext_ref;
        }
    }

    public function getExtRef()
    {
        return $this->ext_ref;
    }

}

<?php

class AutoRefundVo extends \BaseVo
{
    private $refund_id;
    private $so_no;
    private $payment_gateway_id;
    private $action;
    private $amount;
    private $log_out;
    private $log_in;

    protected $primary_key = ['refund_id'];
    protected $increment_field = '';

    public function setRefundId($refund_id)
    {
        if ($refund_id !== null) {
            $this->refund_id = $refund_id;
        }
    }

    public function getRefundId()
    {
        return $this->refund_id;
    }

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

    public function setAction($action)
    {
        if ($action !== null) {
            $this->action = $action;
        }
    }

    public function getAction()
    {
        return $this->action;
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

    public function setLogOut($log_out)
    {
        if ($log_out !== null) {
            $this->log_out = $log_out;
        }
    }

    public function getLogOut()
    {
        return $this->log_out;
    }

    public function setLogIn($log_in)
    {
        if ($log_in !== null) {
            $this->log_in = $log_in;
        }
    }

    public function getLogIn()
    {
        return $this->log_in;
    }

}

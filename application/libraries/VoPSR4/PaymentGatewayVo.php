<?php
class PaymentGatewayVo extends \BaseVo
{
    private $id;
    private $payment_gateway_id;
    private $name;
    private $ref_id = '';
    private $status = '0';


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

    public function setName($name)
    {
        if ($name !== null) {
            $this->name = $name;
        }
    }

    public function getName()
    {
        return $this->name;
    }

    public function setRefId($ref_id)
    {
        if ($ref_id !== null) {
            $this->ref_id = $ref_id;
        }
    }

    public function getRefId()
    {
        return $this->ref_id;
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

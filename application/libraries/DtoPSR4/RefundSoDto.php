<?php
class RefundSoDto
{
    private $id;
    private $so_no;
    private $platform_order_id;
    private $platform_id;
    private $txn_id;
    private $total_refund_amount;
    private $currency_id;
    private $create_on;
    private $create_by;
    private $order_date;
    private $dispatch_date;
    private $modify_on;
    private $payment_gateway;
    private $refund_score;
    private $refund_score_date;
    private $refund_reason;
    private $special_order;
    private $pack_date;

    public function getCurrencyId()
    {
        return $this->currency_id;
    }

    public function setCurrencyId($value)
    {
        $this->currency_id = $value;
    }

    public function getModifyOn()
    {
        return $this->modify_on;
    }

    public function setModifyOn($value)
    {
        $this->modify_on = $value;
    }

    public function getOrderDate()
    {
        return $this->order_date;
    }

    public function setOrderDate($value)
    {
        $this->order_date = $value;
    }

    public function getDispatchDate()
    {
        return $this->dispatch_date;
    }

    public function setDispatchDate($value)
    {
        $this->dispatch_date = $value;
    }

    public function getCreateOn()
    {
        return $this->create_on;
    }

    public function setCreateOn($value)
    {
        $this->create_on = $value;
    }

    public function getCreateBy()
    {
        return $this->create_by;
    }

    public function setCreateBy($value)
    {
        $this->create_by = $value;
    }

    public function getTotalRefundAmount()
    {
        return $this->total_refund_amount;
    }

    public function setTotalRefundAmount($value)
    {
        $this->total_refund_amount = $value;
    }

    public function getPlatformId()
    {
        return $this->platform_id;
    }

    public function setPlatformId($value)
    {
        $this->platform_id = $value;
    }

    public function getTxnId()
    {
        return $this->txn_id;
    }

    public function setTxnId($value)
    {
        $this->txn_id = $value;
    }

    public function getPlatformOrderId()
    {
        return $this->platform_order_id;
    }

    public function setPlatformOrderId($value)
    {
        $this->platform_order_id = $value;
    }

    public function getSoNo()
    {
        return $this->so_no;
    }

    public function setSoNo($value)
    {
        $this->so_no = $value;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setId($value)
    {
        $this->id = $value;
    }

    public function getPaymentGateway()
    {
        return $this->payment_gateway;
    }

    public function setPaymentGateway($value)
    {
        $this->payment_gateway = $value;
    }

    public function getRefundScore()
    {
        return $this->refund_score;
    }

    public function setRefundScore($value)
    {
        $this->refund_score = $value;
    }

    public function getRefundScoreDate()
    {
        return $this->refund_score_date;
    }

    public function setRefundScoreDate($value)
    {
        $this->refund_score_date = $value;
    }

    public function getRefundReason()
    {
        return $this->refund_reason;
    }

    public function setRefundReason($value)
    {
        $this->refund_reason = $value;
    }

    public function getSpecialOrder()
    {
        return $this->special_order;
    }

    public function setSpecialOrder($value)
    {
        $this->special_order = $value;
    }

    public function getPackDate()
    {
        return $this->pack_date;
    }

    public function setPackDate($value)
    {
        $this->pack_date = $value;
    }
}

<?php
class ExpectDeliveryDateReportDto
{
    private $so_no;
    private $platform_id;
    private $payment_gateway;
    private $platform_order_id;
    private $ebay_id;
    private $transaction_no;
    private $order_amount;
    private $create_date = "01/01/1970 12:00:00";
    private $edd = "01/01/1970";
    private $client_name;
    private $client_email;
    private $contact_no;
    private $shipped_on = "01/01/1970 12:00:00";
    private $order_status;
    private $hold_status;
    private $refund_status;
    private $priority_score;
    private $modify_by;

    public function setSoNo($so_no)
    {
        $this->so_no = $so_no;
    }

    public function getSoNo()
    {
        return $this->so_no;
    }

    public function setPlatformId($platform_id)
    {
        $this->platform_id = $platform_id;
    }

    public function getPlatformId()
    {
        return $this->platform_id;
    }

    public function setPaymentGateway($payment_gateway)
    {
        $this->payment_gateway = $payment_gateway;
    }

    public function getPaymentGateway()
    {
        return $this->payment_gateway;
    }

    public function setPlatformOrderId($platform_order_id)
    {
        $this->platform_order_id = $platform_order_id;
    }

    public function getPlatformOrderId()
    {
        return $this->platform_order_id;
    }

    public function setEbayId($ebay_id)
    {
        $this->ebay_id = $ebay_id;
    }

    public function getEbayId()
    {
        return $this->ebay_id;
    }

    public function setTransactionNo($transaction_no)
    {
        $this->transaction_no = $transaction_no;
    }

    public function getTransactionNo()
    {
        return $this->transaction_no;
    }

    public function setOrderAmount($order_amount)
    {
        $this->order_amount = $order_amount;
    }

    public function getOrderAmount()
    {
        return $this->order_amount;
    }

    public function setCreateDate($create_date)
    {
        $this->create_date = $create_date;
    }

    public function getCreateDate()
    {
        return $this->create_date;
    }

    public function setEdd($edd)
    {
        $this->edd = $edd;
    }

    public function getEdd()
    {
        return $this->edd;
    }

    public function setClientName($client_name)
    {
        $this->client_name = $client_name;
    }

    public function getClientName()
    {
        return $this->client_name;
    }

    public function setClientEmail($client_email)
    {
        $this->client_email = $client_email;
    }

    public function getClientEmail()
    {
        return $this->client_email;
    }

    public function setContactNo($contact_no)
    {
        $this->contact_no = $contact_no;
    }

    public function getContactNo()
    {
        return $this->contact_no;
    }

    public function setShippedOn($shipped_on)
    {
        $this->shipped_on = $shipped_on;
    }

    public function getShippedOn()
    {
        return $this->shipped_on;
    }

    public function setOrderStatus($order_status)
    {
        $this->order_status = $order_status;
    }

    public function getOrderStatus()
    {
        return $this->order_status;
    }

    public function setHoldStatus($hold_status)
    {
        $this->hold_status = $hold_status;
    }

    public function getHoldStatus()
    {
        return $this->hold_status;
    }

    public function setRefundStatus($refund_status)
    {
        $this->refund_status = $refund_status;
    }

    public function getRefundStatus()
    {
        return $this->refund_status;
    }

    public function setPriorityScore($priority_score)
    {
        $this->priority_score = $priority_score;
    }

    public function getPriorityScore()
    {
        return $this->priority_score;
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

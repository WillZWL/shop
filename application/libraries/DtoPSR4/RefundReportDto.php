<?php
class RefundReportDto
{
    private $refund_id;
    private $biz_type;
    private $platform_id;
    private $pmgw_name;
    private $bill_country_id;
    private $txn_id;
    private $client_id;
    private $so_no;
    private $prod_name;
    private $cat_name;
    private $item_sku;
    private $dispatch_date;
    private $order_create_date;
    private $amount;
    private $delivery_type_id;
    private $request_date;
    private $approve_date;
    private $refund_date;
    private $refund_type;
    private $currency_id;
    private $refund_amount;
    private $request_by;
    private $reason_cat;
    private $description;
    private $notes;
    private $refund_status;
    private $cs_approval_date;
    private $cs_approved_by;

    public function getRefundId()
    {
        return $this->refund_id;
    }

    public function setRefundId($value)
    {
        $this->refund_id = $value;
    }

    public function getBizType()
    {
        return $this->biz_type;
    }

    public function setBizType($value)
    {
        $this->biz_type = $value;
    }

    public function getPlatformId()
    {
        return $this->platform_id;
    }

    public function setPlatformId($value)
    {
        $this->platform_id = $value;
    }

    public function getPmgwName()
    {
        return $this->pmgw_name;
    }

    public function setPmgwName($value)
    {
        $this->pmgw_name = $value;
    }

    public function getBillCountryId()
    {
        return $this->bill_country_id;
    }

    public function setBillCountryId($value)
    {
        $this->bill_country_id = $value;
    }

    public function getTxnId()
    {
        return $this->txn_id;
    }

    public function setTxnId($value)
    {
        $this->txn_id = $value;
    }

    public function getClientId()
    {
        return $this->client_id;
    }

    public function setClientId($value)
    {
        $this->client_id = $value;
    }

    public function getSoNo()
    {
        return $this->so_no;
    }

    public function setSoNo($value)
    {
        $this->so_no = $value;
    }

    public function getProdName()
    {
        return $this->prod_name;
    }

    public function setProdName($value)
    {
        $this->prod_name = $value;
    }

    public function getCatName()
    {
        return $this->cat_name;
    }

    public function setCatName($value)
    {
        $this->cat_name = $value;
    }

    public function getItemSku()
    {
        return $this->item_sku;
    }

    public function setItemSku($value)
    {
        $this->item_sku = $value;
    }

    public function getDispatchDate()
    {
        return $this->dispatch_date;
    }

    public function setDispatchDate($value)
    {
        $this->dispatch_date = $value;
    }

    public function getOrderCreateDate()
    {
        return $this->order_create_date;
    }

    public function setOrderCreateDate($value)
    {
        $this->order_create_date = $value;
    }

    public function getAmount()
    {
        return $this->amount;
    }

    public function setAmount($value)
    {
        $this->amount = $value;
    }

    public function getDeliveryTypeId()
    {
        return $this->delivery_type_id;
    }

    public function setDeliveryTypeId($value)
    {
        $this->delivery_type_id = $value;
    }

    public function getRequestDate()
    {
        return $this->request_date;
    }

    public function setRequestDate($value)
    {
        $this->request_date = $value;
    }

    public function getApproveDate()
    {
        return $this->approve_date;
    }

    public function setApproveDate($value)
    {
        $this->approve_date = $value;
    }

    public function getRefundDate()
    {
        return $this->refund_date;
    }

    public function setRefundDate($value)
    {
        $this->refund_date = $value;
    }

    public function getRefundType()
    {
        return $this->refund_type;
    }

    public function setRefundType($value)
    {
        $this->refund_type = $value;
    }

    public function getCurrencyId()
    {
        return $this->currency_id;
    }

    public function setCurrencyId($value)
    {
        $this->currency_id = $value;
    }

    public function getRefundAmount()
    {
        return $this->refund_amount;
    }

    public function setRefundAmount($value)
    {
        $this->refund_amount = $value;
    }

    public function getRequestBy()
    {
        return $this->request_by;
    }

    public function setRequestBy($value)
    {
        $this->request_by = $value;
    }

    public function getReasonCat()
    {
        return $this->reason_cat;
    }

    public function setReasonCat($value)
    {
        $this->reason_cat = $value;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function setDescription($value)
    {
        $this->description = $value;
    }

    public function getNotes()
    {
        return $this->notes;
    }

    public function setNotes($value)
    {
        $this->notes = $value;
    }

    public function getRefundStatus()
    {
        return $this->refund_status;
    }

    public function setRefundStatus($value)
    {
        $this->refund_status = $value;
    }

    public function getCsApprovalDate()
    {
        return $this->cs_approval_date;
    }

    public function setCsApprovalDate($value)
    {
        $this->cs_approval_date = $value;
    }

    public function getCsApprovedBy()
    {
        return $this->cs_approved_by;
    }

    public function setCsApprovedBy($value)
    {
        $this->cs_approved_by = $value;
    }
}

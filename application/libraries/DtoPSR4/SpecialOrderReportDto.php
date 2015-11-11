<?php
class SpecialOrderReportDto
{
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
    private $currency_id;
    private $request_by;
    private $approval_date;
    private $approved_by;
    private $reason;
    private $courier_id;
    private $tracking_no;

    public function setBizType($biz_type)
    {
        $this->biz_type = $biz_type;
    }

    public function getBizType()
    {
        return $this->biz_type;
    }

    public function setPlatformId($platform_id)
    {
        $this->platform_id = $platform_id;
    }

    public function getPlatformId()
    {
        return $this->platform_id;
    }

    public function setPmgwName($pmgw_name)
    {
        $this->pmgw_name = $pmgw_name;
    }

    public function getPmgwName()
    {
        return $this->pmgw_name;
    }

    public function setBillCountryId($bill_country_id)
    {
        $this->bill_country_id = $bill_country_id;
    }

    public function getBillCountryId()
    {
        return $this->bill_country_id;
    }

    public function setTxnId($txn_id)
    {
        $this->txn_id = $txn_id;
    }

    public function getTxnId()
    {
        return $this->txn_id;
    }

    public function setClientId($client_id)
    {
        $this->client_id = $client_id;
    }

    public function getClientId()
    {
        return $this->client_id;
    }

    public function setSoNo($so_no)
    {
        $this->so_no = $so_no;
    }

    public function getSoNo()
    {
        return $this->so_no;
    }

    public function setProdName($prod_name)
    {
        $this->prod_name = $prod_name;
    }

    public function getProdName()
    {
        return $this->prod_name;
    }

    public function setCatName($cat_name)
    {
        $this->cat_name = $cat_name;
    }

    public function getCatName()
    {
        return $this->cat_name;
    }

    public function setItemSku($item_sku)
    {
        $this->item_sku = $item_sku;
    }

    public function getItemSku()
    {
        return $this->item_sku;
    }

    public function setDispatchDate($dispatch_date)
    {
        $this->dispatch_date = $dispatch_date;
    }

    public function getDispatchDate()
    {
        return $this->dispatch_date;
    }

    public function setOrderCreateDate($order_create_date)
    {
        $this->order_create_date = $order_create_date;
    }

    public function getOrderCreateDate()
    {
        return $this->order_create_date;
    }

    public function setAmount($amount)
    {
        $this->amount = $amount;
    }

    public function getAmount()
    {
        return $this->amount;
    }

    public function setDeliveryTypeId($delivery_type_id)
    {
        $this->delivery_type_id = $delivery_type_id;
    }

    public function getDeliveryTypeId()
    {
        return $this->delivery_type_id;
    }

    public function setCurrencyId($currency_id)
    {
        $this->currency_id = $currency_id;
    }

    public function getCurrencyId()
    {
        return $this->currency_id;
    }

    public function setRequestBy($request_by)
    {
        $this->request_by = $request_by;
    }

    public function getRequestBy()
    {
        return $this->request_by;
    }

    public function setApprovalDate($approval_date)
    {
        $this->approval_date = $approval_date;
    }

    public function getApprovalDate()
    {
        return $this->approval_date;
    }

    public function setApprovedBy($approved_by)
    {
        $this->approved_by = $approved_by;
    }

    public function getApprovedBy()
    {
        return $this->approved_by;
    }

    public function setReason($reason)
    {
        $this->reason = $reason;
    }

    public function getReason()
    {
        return $this->reason;
    }

    public function setCourierId($courier_id)
    {
        $this->courier_id = $courier_id;
    }

    public function getCourierId()
    {
        return $this->courier_id;
    }

    public function setTrackingNo($tracking_no)
    {
        $this->tracking_no = $tracking_no;
    }

    public function getTrackingNo()
    {
        return $this->tracking_no;
    }

}

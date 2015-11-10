<?php
class DelayReportItemListDto
{
    private $platform_id;
    private $order_no;
    private $bill_country_id;
    private $order_date;
    private $product_name;
    private $sku;
    private $hold_status;
    private $mult;
    private $packed_date;
    private $dispatched_date;
    private $courier_id;
    private $tracking_no;
    private $fulfillment_centre;
    private $cs_comment;
    private $fulfillment_day;
    private $refund_type;
    private $refund_status;
    private $refund_qty;
    private $refund_amount;

    public function setPlatformId($platform_id)
    {
        $this->platform_id = $platform_id;
    }

    public function getPlatformId()
    {
        return $this->platform_id;
    }

    public function setOrderNo($order_no)
    {
        $this->order_no = $order_no;
    }

    public function getOrderNo()
    {
        return $this->order_no;
    }

    public function setBillCountryId($bill_country_id)
    {
        $this->bill_country_id = $bill_country_id;
    }

    public function getBillCountryId()
    {
        return $this->bill_country_id;
    }

    public function setOrderDate($order_date)
    {
        $this->order_date = $order_date;
    }

    public function getOrderDate()
    {
        return $this->order_date;
    }

    public function setProductName($product_name)
    {
        $this->product_name = $product_name;
    }

    public function getProductName()
    {
        return $this->product_name;
    }

    public function setSku($sku)
    {
        $this->sku = $sku;
    }

    public function getSku()
    {
        return $this->sku;
    }

    public function setHoldStatus($hold_status)
    {
        $this->hold_status = $hold_status;
    }

    public function getHoldStatus()
    {
        return $this->hold_status;
    }

    public function setMult($mult)
    {
        $this->mult = $mult;
    }

    public function getMult()
    {
        return $this->mult;
    }

    public function setPackedDate($packed_date)
    {
        $this->packed_date = $packed_date;
    }

    public function getPackedDate()
    {
        return $this->packed_date;
    }

    public function setDispatchedDate($dispatched_date)
    {
        $this->dispatched_date = $dispatched_date;
    }

    public function getDispatchedDate()
    {
        return $this->dispatched_date;
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

    public function setFulfillmentCentre($fulfillment_centre)
    {
        $this->fulfillment_centre = $fulfillment_centre;
    }

    public function getFulfillmentCentre()
    {
        return $this->fulfillment_centre;
    }

    public function setCsComment($cs_comment)
    {
        $this->cs_comment = $cs_comment;
    }

    public function getCsComment()
    {
        return $this->cs_comment;
    }

    public function setFulfillmentDay($fulfillment_day)
    {
        $this->fulfillment_day = $fulfillment_day;
    }

    public function getFulfillmentDay()
    {
        return $this->fulfillment_day;
    }

    public function setRefundType($refund_type)
    {
        $this->refund_type = $refund_type;
    }

    public function getRefundType()
    {
        return $this->refund_type;
    }

    public function setRefundStatus($refund_status)
    {
        $this->refund_status = $refund_status;
    }

    public function getRefundStatus()
    {
        return $this->refund_status;
    }

    public function setRefundQty($refund_qty)
    {
        $this->refund_qty = $refund_qty;
    }

    public function getRefundQty()
    {
        return $this->refund_qty;
    }

    public function setRefundAmount($refund_amount)
    {
        $this->refund_amount = $refund_amount;
    }

    public function getRefundAmount()
    {
        return $this->refund_amount;
    }

}

<?php
class SoListWithNameDto
{
    private $so_no;
    private $sh_no;
    private $platform_order_id;
    private $platform_id;
    private $txn_id;
    private $client_id;
    private $biz_type;
    private $amount;
    private $delivery_charge;
    private $delivery_type_id;
    private $weight;
    private $currency_id;
    private $bill_name;
    private $bill_company;
    private $bill_address;
    private $bill_postcode;
    private $bill_city;
    private $bill_state;
    private $bill_country_id;
    private $delivery_name;
    private $delivery_company;
    private $delivery_address;
    private $delivery_postcode;
    private $delivery_city;
    private $delivery_state;
    private $delivery_country_id;
    private $status = '1';
    private $refund_status = '0';
    private $hold_status = '0';
    private $promotion_code;
    private $expect_delivery_date;
    private $orderCreate_date;
    private $dispatch_date;
    private $git;
    private $create_on = '0000-00-00 00:00:00';
    private $create_at;
    private $create_by;
    private $modify_on;
    private $modify_at;
    private $modify_by;
    private $multiple;
    private $items;
    private $o_items;
    private $client_name;
    private $t3m_result;
    private $email;
    private $payment_gateway_id;
    private $warehouse_id;
    private $tracking_no;
    private $note;
    private $order_reason;
    private $offline_fee;
    private $delivery_type;
    private $courier_id;
    private $website_status;
    private $sku;
    private $product_name;
    private $qty;
    private $inventory;
    private $outstanding_qty;
    private $order_total_sku;
    private $reason;
    private $require_payment;
    private $master_sku;
    private $rate;
    private $amount_usd;
    private $so_item_amount;
    private $cat_name;
    private $sub_cat_name;
    private $rec_courier;
    private $packing_date;
    private $split_so_group;

    public function getSoNo()
    {
        return $this->so_no;
    }

    public function setSoNo($value)
    {
        $this->so_no = $value;
        return $this;
    }

    public function getShNo()
    {
        return $this->sh_no;
    }

    public function setShNo($value)
    {
        $this->sh_no = $value;
        return $this;
    }

    public function getPlatformOrderId()
    {
        return $this->platform_order_id;
    }

    public function setPlatformOrderId($value)
    {
        $this->platform_order_id = $value;
        return $this;
    }

    public function getPlatformId()
    {
        return $this->platform_id;
    }

    public function setPlatformId($value)
    {
        $this->platform_id = $value;
        return $this;
    }

    public function getTxnId()
    {
        return $this->txn_id;
    }

    public function setTxnId($value)
    {
        $this->txn_id = $value;
        return $this;
    }

    public function getClientId()
    {
        return $this->client_id;
    }

    public function setClientId($value)
    {
        $this->client_id = $value;
        return $this;
    }

    public function getBizType()
    {
        return $this->biz_type;
    }

    public function setBizType($value)
    {
        $this->biz_type = $value;
        return $this;
    }

    public function getAmount()
    {
        return $this->amount;
    }

    public function setAmount($value)
    {
        $this->amount = $value;
        return $this;
    }

    public function getDeliveryCharge()
    {
        return $this->delivery_charge;
    }

    public function setDeliveryCharge($value)
    {
        $this->delivery_charge = $value;
        return $this;
    }

    public function getDeliveryTypeId()
    {
        return $this->delivery_type_id;
    }

    public function setDeliveryTypeId($value)
    {
        $this->delivery_type_id = $value;
    }

    public function getWeight()
    {
        return $this->weight;
    }

    public function setWeight($value)
    {
        $this->weight = $value;
        return $this;
    }

    public function getCurrencyId()
    {
        return $this->currency_id;
    }

    public function setCurrencyId($value)
    {
        $this->currency_id = $value;
        return $this;
    }

    public function getBillName()
    {
        return $this->bill_name;
    }

    public function setBillName($value)
    {
        $this->bill_name = $value;
        return $this;
    }

    public function getBillCompany()
    {
        return $this->bill_company;
    }

    public function setBillCompany($value)
    {
        $this->bill_company = $value;
        return $this;
    }

    public function getBillAddress()
    {
        return $this->bill_address;
    }

    public function setBillAddress($value)
    {
        $this->bill_address = $value;
        return $this;
    }

    public function getBillPostcode()
    {
        return $this->bill_postcode;
    }

    public function setBillPostcode($value)
    {
        $this->bill_postcode = $value;
        return $this;
    }

    public function getBillCity()
    {
        return $this->bill_city;
    }

    public function setBillCity($value)
    {
        $this->bill_city = $value;
        return $this;
    }

    public function getBillState()
    {
        return $this->bill_state;
    }

    public function setBillState($value)
    {
        $this->bill_state = $value;
        return $this;
    }

    public function getBillCountryId()
    {
        return $this->bill_country_id;
    }

    public function setBillCountryId($value)
    {
        $this->bill_country_id = $value;
        return $this;
    }

    public function getDeliveryName()
    {
        return $this->delivery_name;
    }

    public function setDeliveryName($value)
    {
        $this->delivery_name = $value;
        return $this;
    }

    public function getDeliveryCompany()
    {
        return $this->delivery_company;
    }

    public function setDeliveryCompany($value)
    {
        $this->delivery_company = $value;
        return $this;
    }

    public function getDeliveryAddress()
    {
        return $this->delivery_address;
    }

    public function setDeliveryAddress($value)
    {
        $this->delivery_address = $value;
        return $this;
    }

    public function getDeliveryPostcode()
    {
        return $this->delivery_postcode;
    }

    public function setDeliveryPostcode($value)
    {
        $this->delivery_postcode = $value;
        return $this;
    }

    public function getGit()
    {
        return $this->git;
    }

    public function setGit($value)
    {
        $this->git = $value;
        return $this;
    }

    public function getDeliveryCity()
    {
        return $this->delivery_city;
    }

    public function setDeliveryCity($value)
    {
        $this->delivery_city = $value;
        return $this;
    }

    public function getDeliveryState()
    {
        return $this->delivery_state;
    }

    public function setDeliveryState($value)
    {
        $this->delivery_state = $value;
        return $this;
    }

    public function getDeliveryCountryId()
    {
        return $this->delivery_country_id;
    }

    public function setDeliveryCountryId($value)
    {
        $this->delivery_country_id = $value;
        return $this;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function setStatus($value)
    {
        $this->status = $value;
        return $this;
    }

    public function getRefundStatus()
    {
        return $this->refund_status;
    }

    public function setRefundStatus($value)
    {
        $this->refund_status = $value;
        return $this;
    }

    public function getHoldStatus()
    {
        return $this->hold_status;
    }

    public function setHoldStatus($value)
    {
        $this->hold_status = $value;
        return $this;
    }

    public function getPromotionCode()
    {
        return $this->promotion_code;
    }

    public function setPromotionCode($value)
    {
        $this->promotion_code = $value;
        return $this;
    }

    public function getExpectDeliveryDate()
    {
        return $this->expect_delivery_date;
    }

    public function setExpectDeliveryDate($value)
    {
        $this->expect_delivery_date = $value;
        return $this;
    }

    public function getOrderCreateDate()
    {
        return $this->orderCreate_date;
    }

    public function setOrderCreateDate($value)
    {
        $this->orderCreate_date = $value;
        return $this;
    }

    public function getDispatchDate()
    {
        return $this->dispatch_date;
    }

    public function setDispatchDate($value)
    {
        $this->dispatch_date = $value;
        return $this;
    }

    public function getCreateOn()
    {
        return $this->create_on;
    }

    public function setCreateOn($value)
    {
        $this->create_on = $value;
        return $this;
    }

    public function getCreateAt()
    {
        return $this->create_at;
    }

    public function setCreateAt($value)
    {
        $this->create_at = $value;
        return $this;
    }

    public function getCreateBy()
    {
        return $this->create_by;
    }

    public function setCreateBy($value)
    {
        $this->create_by = $value;
        return $this;
    }

    public function getModifyOn()
    {
        return $this->modify_on;
    }

    public function setModifyOn($value)
    {
        $this->modify_on = $value;
        return $this;
    }

    public function getModifyAt()
    {
        return $this->modify_at;
    }

    public function setModifyAt($value)
    {
        $this->modify_at = $value;
        return $this;
    }

    public function getModifyBy()
    {
        return $this->modify_by;
    }

    public function setModifyBy($value)
    {
        $this->modify_by = $value;
        return $this;
    }

    public function getMultiple()
    {
        return $this->multiple;
    }

    public function setMultiple($value)
    {
        $this->multiple = $value;
    }

    public function getItems()
    {
        return $this->items;
    }

    public function setItems($value)
    {
        $this->items = $value;
    }

    public function getOItems()
    {
        return $this->o_items;
    }

    public function setOItems($value)
    {
        $this->o_items = $value;
    }

    public function getClientName()
    {
        return $this->client_name;
    }

    public function setClientName($value)
    {
        $this->client_name = $value;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function setEmail($value)
    {
        $this->email = $value;
    }

    public function getT3mResult()
    {
        return $this->t3m_result;
    }

    public function setT3mResult($value)
    {
        $this->t3m_result = $value;
    }

    public function getPaymentGatewayId()
    {
        return $this->payment_gateway_id;
    }

    public function setPaymentGatewayId($value)
    {
        $this->payment_gateway_id = $value;
    }

    public function getWarehouseId()
    {
        return $this->warehouse_id;
    }

    public function setWarehouseId($value)
    {
        $this->warehouse_id = $value;
    }

    public function getTrackingNo()
    {
        return $this->tracking_no;
    }

    public function setTrackingNo($value)
    {
        $this->tracking_no = $value;
    }

    public function getNote()
    {
        return $this->note;
    }

    public function setNote($value)
    {
        $this->note = $value;
    }

    public function getOrderReason()
    {
        return $this->order_reason;
    }

    public function setOrderReason($value)
    {
        $this->order_reason = $value;
    }

    public function getOfflineFee()
    {
        return $this->offline_fee;
    }

    public function setOfflineFee($value)
    {
        $this->offline_fee = $value;
    }

    public function getDeliveryType()
    {
        return $this->delivery_type;
    }

    public function setDeliveryType($value)
    {
        $this->delivery_type = $value;
    }

    public function getCourierId()
    {
        return $this->courier_id;
    }

    public function setCourierId($value)
    {
        $this->courier_id = $value;
    }

    public function getWebsiteStatus()
    {
        return $this->website_status;
    }

    public function setWebsiteStatus($value)
    {
        $this->website_status = $value;
    }

    public function getSku()
    {
        return $this->sku;
    }

    public function setSku($value)
    {
        $this->sku = $value;
    }

    public function getProductName()
    {
        return $this->product_name;
    }

    public function setProductName($value)
    {
        $this->product_name = $value;
    }

    public function getQty()
    {
        return $this->qty;
    }

    public function setQty($value)
    {
        $this->qty = $value;
    }

    public function getInventory()
    {
        return $this->inventory;
    }

    public function setInventory($value)
    {
        $this->inventory = $value;
    }

    public function getOutstandingQty()
    {
        return $this->outstanding_qty;
    }

    public function setOutstandingQty($value)
    {
        $this->outstanding_qty = $value;
    }

    public function getOrderTotalSku()
    {
        return $this->order_total_sku;
    }

    public function setOrderTotalSku($value)
    {
        $this->order_total_sku = $value;
    }

    public function getReason()
    {
        return $this->reason;
    }

    public function setReason($value)
    {
        $this->reason = $value;
    }

    public function getRequirePayment()
    {
        return $this->require_payment;
    }

    public function setRequirePayment($value)
    {
        $this->require_payment = $value;
    }

    public function getMasterSku()
    {
        return $this->master_sku;
    }

    public function setMasterSku($value)
    {
        $this->master_sku = $value;
    }

    public function getRate()
    {
        return $this->rate;
    }

    public function setRate($value)
    {
        $this->rate = $value;
    }

    public function getAmountUsd()
    {
        return $this->amount_usd;
    }

    public function setAmountUsd($value)
    {
        $this->amount_usd = $value;
    }

    public function getSoItemAmount()
    {
        return $this->so_item_amount;
    }

    public function setSoItemAmount($value)
    {
        $this->so_item_amount = $value;
    }

    public function getCatName()
    {
        return $this->cat_name;
    }

    public function setCatName($value)
    {
        $this->cat_name = $value;
    }

    public function getSubCatName()
    {
        return $this->sub_cat_name;
    }

    public function setSubCatName($value)
    {
        $this->sub_cat_name = $value;
    }

    public function getRecCourier()
    {
        return $this->rec_courier;
    }

    public function setRecCourier($value)
    {
        $this->rec_courier = $value;
    }

    public function getPackingDate()
    {
        return $this->packing_date;
    }

    public function setPackingDate($value)
    {
        $this->packing_date = $value;
    }

    public function getSplitSoGroup()
    {
        return $this->split_so_group;
    }

    public function setSplitSoGroup($value)
    {
        $this->split_so_group = $value;
    }

}

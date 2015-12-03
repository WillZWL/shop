<?php
class IntegratedOrderFulfillmentVo extends \BaseVo
{
    private $id;
    private $so_no;
    private $line_no;
    private $sku = '';
    private $platform_id = '';
    private $platform_order_id = '';
    private $order_create_date = '0000-00-00 00:00:00';
    private $expect_delivery_date = '0000-00-00';
    private $product_name = '';
    private $website_status = '';
    private $delivery_name = '';
    private $delivery_country_id = '';
    private $delivery_type_id = '';
    private $payment_gateway_id = '';
    private $rec_courier = '';
    private $note = '';
    private $amount = '0.00';
    private $refund_status = '0';
    private $hold_status = '0';
    private $qty = '0';
    private $outstanding_qty = '0';
    private $status = '1';
    private $split_so_group = '';
    private $delivery_postcode = '';
    private $order_total_sku = '0';
    private $create_on = '0000-00-00 00:00:00';
    private $create_at = '2130706433';
    private $create_by = 'system';
    private $modify_on = '';
    private $modify_at = '2130706433';
    private $modify_by = 'system';

    private $primary_key = ['id'];
    private $increment_field = 'id';

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

    public function setLineNo($line_no)
    {
        if ($line_no !== null) {
            $this->line_no = $line_no;
        }
    }

    public function getLineNo()
    {
        return $this->line_no;
    }

    public function setSku($sku)
    {
        if ($sku !== null) {
            $this->sku = $sku;
        }
    }

    public function getSku()
    {
        return $this->sku;
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

    public function setPlatformOrderId($platform_order_id)
    {
        if ($platform_order_id !== null) {
            $this->platform_order_id = $platform_order_id;
        }
    }

    public function getPlatformOrderId()
    {
        return $this->platform_order_id;
    }

    public function setOrderCreateDate($order_create_date)
    {
        if ($order_create_date !== null) {
            $this->order_create_date = $order_create_date;
        }
    }

    public function getOrderCreateDate()
    {
        return $this->order_create_date;
    }

    public function setExpectDeliveryDate($expect_delivery_date)
    {
        if ($expect_delivery_date !== null) {
            $this->expect_delivery_date = $expect_delivery_date;
        }
    }

    public function getExpectDeliveryDate()
    {
        return $this->expect_delivery_date;
    }

    public function setProductName($product_name)
    {
        if ($product_name !== null) {
            $this->product_name = $product_name;
        }
    }

    public function getProductName()
    {
        return $this->product_name;
    }

    public function setWebsiteStatus($website_status)
    {
        if ($website_status !== null) {
            $this->website_status = $website_status;
        }
    }

    public function getWebsiteStatus()
    {
        return $this->website_status;
    }

    public function setDeliveryName($delivery_name)
    {
        if ($delivery_name !== null) {
            $this->delivery_name = $delivery_name;
        }
    }

    public function getDeliveryName()
    {
        return $this->delivery_name;
    }

    public function setDeliveryCountryId($delivery_country_id)
    {
        if ($delivery_country_id !== null) {
            $this->delivery_country_id = $delivery_country_id;
        }
    }

    public function getDeliveryCountryId()
    {
        return $this->delivery_country_id;
    }

    public function setDeliveryTypeId($delivery_type_id)
    {
        if ($delivery_type_id !== null) {
            $this->delivery_type_id = $delivery_type_id;
        }
    }

    public function getDeliveryTypeId()
    {
        return $this->delivery_type_id;
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

    public function setRecCourier($rec_courier)
    {
        if ($rec_courier !== null) {
            $this->rec_courier = $rec_courier;
        }
    }

    public function getRecCourier()
    {
        return $this->rec_courier;
    }

    public function setNote($note)
    {
        if ($note !== null) {
            $this->note = $note;
        }
    }

    public function getNote()
    {
        return $this->note;
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

    public function setRefundStatus($refund_status)
    {
        if ($refund_status !== null) {
            $this->refund_status = $refund_status;
        }
    }

    public function getRefundStatus()
    {
        return $this->refund_status;
    }

    public function setHoldStatus($hold_status)
    {
        if ($hold_status !== null) {
            $this->hold_status = $hold_status;
        }
    }

    public function getHoldStatus()
    {
        return $this->hold_status;
    }

    public function setQty($qty)
    {
        if ($qty !== null) {
            $this->qty = $qty;
        }
    }

    public function getQty()
    {
        return $this->qty;
    }

    public function setOutstandingQty($outstanding_qty)
    {
        if ($outstanding_qty !== null) {
            $this->outstanding_qty = $outstanding_qty;
        }
    }

    public function getOutstandingQty()
    {
        return $this->outstanding_qty;
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

    public function setSplitSoGroup($split_so_group)
    {
        if ($split_so_group !== null) {
            $this->split_so_group = $split_so_group;
        }
    }

    public function getSplitSoGroup()
    {
        return $this->split_so_group;
    }

    public function setDeliveryPostcode($delivery_postcode)
    {
        if ($delivery_postcode !== null) {
            $this->delivery_postcode = $delivery_postcode;
        }
    }

    public function getDeliveryPostcode()
    {
        return $this->delivery_postcode;
    }

    public function setOrderTotalSku($order_total_sku)
    {
        if ($order_total_sku !== null) {
            $this->order_total_sku = $order_total_sku;
        }
    }

    public function getOrderTotalSku()
    {
        return $this->order_total_sku;
    }

    public function setCreateOn($create_on)
    {
        if ($create_on !== null) {
            $this->create_on = $create_on;
        }
    }

    public function getCreateOn()
    {
        return $this->create_on;
    }

    public function setCreateAt($create_at)
    {
        if ($create_at !== null) {
            $this->create_at = $create_at;
        }
    }

    public function getCreateAt()
    {
        return $this->create_at;
    }

    public function setCreateBy($create_by)
    {
        if ($create_by !== null) {
            $this->create_by = $create_by;
        }
    }

    public function getCreateBy()
    {
        return $this->create_by;
    }

    public function setModifyOn($modify_on)
    {
        if ($modify_on !== null) {
            $this->modify_on = $modify_on;
        }
    }

    public function getModifyOn()
    {
        return $this->modify_on;
    }

    public function setModifyAt($modify_at)
    {
        if ($modify_at !== null) {
            $this->modify_at = $modify_at;
        }
    }

    public function getModifyAt()
    {
        return $this->modify_at;
    }

    public function setModifyBy($modify_by)
    {
        if ($modify_by !== null) {
            $this->modify_by = $modify_by;
        }
    }

    public function getModifyBy()
    {
        return $this->modify_by;
    }

    public function getPrimaryKey()
    {
        return $this->primary_key;
    }

    public function getIncrementField()
    {
        return $this->increment_field;
    }
}

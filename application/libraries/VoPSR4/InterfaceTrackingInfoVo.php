<?php
class InterfaceTrackingInfoVo extends \BaseVo
{
    private $trans_id;
    private $batch_id;
    private $sh_no;
    private $so_no;
    private $order_number = '';
    private $status = '';
    private $tracking_no = '';
    private $ship_method = '';
    private $courier_id = '';
    private $dispatch_date = '';
    private $weight = '0.00';
    private $consignee = '';
    private $postcode = '';
    private $country = '';
    private $amount = '0.00';
    private $currency = '';
    private $charge_out = '';
    private $qty = '0';
    private $sku = '';
    private $qty_shipped = '0';
    private $shipping_cost = '0.00';
    private $batch_status = '';
    private $failed_reason = '';

    protected $primary_key = ['trans_id'];
    protected $increment_field = 'trans_id';

    public function setTransId($trans_id)
    {
        if ($trans_id !== null) {
            $this->trans_id = $trans_id;
        }
    }

    public function getTransId()
    {
        return $this->trans_id;
    }

    public function setBatchId($batch_id)
    {
        if ($batch_id !== null) {
            $this->batch_id = $batch_id;
        }
    }

    public function getBatchId()
    {
        return $this->batch_id;
    }

    public function setShNo($sh_no)
    {
        if ($sh_no !== null) {
            $this->sh_no = $sh_no;
        }
    }

    public function getShNo()
    {
        return $this->sh_no;
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

    public function setOrderNumber($order_number)
    {
        if ($order_number !== null) {
            $this->order_number = $order_number;
        }
    }

    public function getOrderNumber()
    {
        return $this->order_number;
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

    public function setTrackingNo($tracking_no)
    {
        if ($tracking_no !== null) {
            $this->tracking_no = $tracking_no;
        }
    }

    public function getTrackingNo()
    {
        return $this->tracking_no;
    }

    public function setShipMethod($ship_method)
    {
        if ($ship_method !== null) {
            $this->ship_method = $ship_method;
        }
    }

    public function getShipMethod()
    {
        return $this->ship_method;
    }

    public function setCourierId($courier_id)
    {
        if ($courier_id !== null) {
            $this->courier_id = $courier_id;
        }
    }

    public function getCourierId()
    {
        return $this->courier_id;
    }

    public function setDispatchDate($dispatch_date)
    {
        if ($dispatch_date !== null) {
            $this->dispatch_date = $dispatch_date;
        }
    }

    public function getDispatchDate()
    {
        return $this->dispatch_date;
    }

    public function setWeight($weight)
    {
        if ($weight !== null) {
            $this->weight = $weight;
        }
    }

    public function getWeight()
    {
        return $this->weight;
    }

    public function setConsignee($consignee)
    {
        if ($consignee !== null) {
            $this->consignee = $consignee;
        }
    }

    public function getConsignee()
    {
        return $this->consignee;
    }

    public function setPostcode($postcode)
    {
        if ($postcode !== null) {
            $this->postcode = $postcode;
        }
    }

    public function getPostcode()
    {
        return $this->postcode;
    }

    public function setCountry($country)
    {
        if ($country !== null) {
            $this->country = $country;
        }
    }

    public function getCountry()
    {
        return $this->country;
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

    public function setCurrency($currency)
    {
        if ($currency !== null) {
            $this->currency = $currency;
        }
    }

    public function getCurrency()
    {
        return $this->currency;
    }

    public function setChargeOut($charge_out)
    {
        if ($charge_out !== null) {
            $this->charge_out = $charge_out;
        }
    }

    public function getChargeOut()
    {
        return $this->charge_out;
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

    public function setQtyShipped($qty_shipped)
    {
        if ($qty_shipped !== null) {
            $this->qty_shipped = $qty_shipped;
        }
    }

    public function getQtyShipped()
    {
        return $this->qty_shipped;
    }

    public function setShippingCost($shipping_cost)
    {
        if ($shipping_cost !== null) {
            $this->shipping_cost = $shipping_cost;
        }
    }

    public function getShippingCost()
    {
        return $this->shipping_cost;
    }

    public function setBatchStatus($batch_status)
    {
        if ($batch_status !== null) {
            $this->batch_status = $batch_status;
        }
    }

    public function getBatchStatus()
    {
        return $this->batch_status;
    }

    public function setFailedReason($failed_reason)
    {
        if ($failed_reason !== null) {
            $this->failed_reason = $failed_reason;
        }
    }

    public function getFailedReason()
    {
        return $this->failed_reason;
    }

}

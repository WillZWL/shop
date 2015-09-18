<?php
class PendingOrderDto
{
    private $ext_sku;
    private $flex_batch_id;
    private $txn_time;
    private $currency_id;
    private $gateway_id;
    private $qty;
    private $amount;
    private $so_no;
    private $platform_order_id;
    private $total;

    public function setExtSku($ext_sku)
    {
        $this->ext_sku = $ext_sku;
    }

    public function getExtSku()
    {
        return $this->ext_sku;
    }

    public function setFlexBatchId($flex_batch_id)
    {
        $this->flex_batch_id = $flex_batch_id;
    }

    public function getFlexBatchId()
    {
        return $this->flex_batch_id;
    }

    public function setTxnTime($txn_time)
    {
        $this->txn_time = $txn_time;
    }

    public function getTxnTime()
    {
        return $this->txn_time;
    }

    public function setCurrencyId($currency_id)
    {
        $this->currency_id = $currency_id;
    }

    public function getCurrencyId()
    {
        return $this->currency_id;
    }

    public function setGatewayId($gateway_id)
    {
        $this->gateway_id = $gateway_id;
    }

    public function getGatewayId()
    {
        return $this->gateway_id;
    }

    public function setQty($qty)
    {
        $this->qty = $qty;
    }

    public function getQty()
    {
        return $this->qty;
    }

    public function setAmount($amount)
    {
        $this->amount = $amount;
    }

    public function getAmount()
    {
        return $this->amount;
    }

    public function setSoNo($so_no)
    {
        $this->so_no = $so_no;
    }

    public function getSoNo()
    {
        return $this->so_no;
    }

    public function setPlatformOrderId($platform_order_id)
    {
        $this->platform_order_id = $platform_order_id;
    }

    public function getPlatformOrderId()
    {
        return $this->platform_order_id;
    }

    public function setTotal($total)
    {
        $this->total = $total;
    }

    public function getTotal()
    {
        return $this->total;
    }

}

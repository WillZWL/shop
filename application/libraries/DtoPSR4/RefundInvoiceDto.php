<?php
class RefundInvoiceDto
{
    private $index_no;
    private $product_line;
    private $row_no;
    private $master_sku;
    private $tran_type;
    private $flex_batch_id;
    private $txn_time;
    private $currency_id;
    private $report_pmgw;
    private $product_code;
    private $qty;
    private $unit_price;
    private $so_no;
    private $ship_loc_code;
    private $txn_id;
    private $ria_txn_time;
    private $failed_reason;
    private $sr_num;
    private $gateway_id;
    private $amount;
    private $contain_size;
    private $sm_code;

    public function getIndexNo()
    {
        return $this->index_no;
    }

    public function setIndexNo($value)
    {
        $this->index_no = $value;
    }

    public function getProductLine()
    {
        return $this->product_line;
    }

    public function setProductLine($value)
    {
        $this->product_line = $value;
    }

    public function getRowNo()
    {
        return $this->row_no;
    }

    public function setRowNo($value)
    {
        $this->row_no = $value;
    }

    public function getMasterSku()
    {
        return $this->master_sku;
    }

    public function setMasterSku($value)
    {
        $this->master_sku = $value;
    }

    public function getTranType()
    {
        return $this->tran_type;
    }

    public function setTranType($value)
    {
        $this->tran_type = $value;
    }

    public function getFlexBatchId()
    {
        return $this->flex_batch_id;
    }

    public function setFlexBatchId($value)
    {
        $this->flex_batch_id = $value;
    }

    public function getTxnTime()
    {
        return $this->txn_time;
    }

    public function setTxnTime($value)
    {
        $this->txn_time = $value;
    }

    public function getCurrencyId()
    {
        return $this->currency_id;
    }

    public function setCurrencyId($value)
    {
        $this->currency_id = $value;
    }

    public function getReportPmgw()
    {
        return $this->report_pmgw;
    }

    public function setReportPmgw($value)
    {
        $this->report_pmgw = $value;
    }

    public function getProductCode()
    {
        return $this->product_code;
    }

    public function setProductCode($value)
    {
        $this->product_code = $value;
    }

    public function getQty()
    {
        return $this->qty;
    }

    public function setQty($value)
    {
        $this->qty = $value;
    }

    public function getUnitPrice()
    {
        return $this->unit_price;
    }

    public function setUnitPrice($value)
    {
        $this->unit_price = $value;
    }

    public function getSoNo()
    {
        return $this->so_no;
    }

    public function setSoNo($value)
    {
        $this->so_no = $value;
    }

    public function getShipLocCode()
    {
        return $this->ship_loc_code;
    }

    public function setShipLocCode($value)
    {
        $this->ship_loc_code = $value;
    }

    public function getTxnId()
    {
        return $this->txn_id;
    }

    public function setTxnId($value)
    {
        $this->txn_id = $value;
    }

    public function getRiaTxnTime()
    {
        return $this->ria_txn_time;
    }

    public function setRiaTxnTime($value)
    {
        $this->ria_txn_time = $value;
    }

    public function getFailedReason()
    {
        return $this->failed_reason;
    }

    public function setFailedReason($value)
    {
        $this->failed_reason = $value;
    }

    public function getSrNum()
    {
        return $this->sr_num;
    }

    public function setSrNum($value)
    {
        $this->sr_num = $value;
    }

    public function getGatewayId()
    {
        return $this->gateway_id;
    }

    public function setGatewayId($value)
    {
        $this->gateway_id = $value;
    }

    public function getAmount()
    {
        return $this->amount;
    }

    public function setAmount($value)
    {
        $this->amount = $value;
    }

    public function getContainSize()
    {
        return $this->contain_size;
    }

    public function setContainSize($value)
    {
        $this->contain_size = $value;
    }

    public function getSmCode()
    {
        return $this->sm_code;
    }

    public function setSmCode($value)
    {
        $this->sm_code = $value;
    }
}



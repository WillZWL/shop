<?php
class SalesInvoiceDto
{
    private $index_no;
    private $product_line;
    private $row_no;
    private $master_sku;
    private $tran_type;
    private $dispatch_date;
    private $currency_id;
    private $report_pmgw;
    private $flex_batch_id;
    private $product_code;
    private $qty;
    private $unit_price;
    private $txn_time;
    private $so_no;
    private $biz_type;
    private $ship_loc_code;
    private $txn_id;
    private $customer_email;
    private $delivery_charge;
    private $gateway_id;
    private $ria;
    private $remark;
    private $order_reason;
    private $amount;
    private $contain_size;
    private $sm_code;
    private $order_create_date;
    private $platform_id;
    private $split_so_group;
    private $parent_so_no;
    private $line_index;
    private $reason;

    public function setIndexNo($index_no)
    {
        $this->index_no = $index_no;
    }

    public function getIndexNo()
    {
        return $this->index_no;
    }

    public function setProductLine($product_line)
    {
        $this->product_line = $product_line;
    }

    public function getProductLine()
    {
        return $this->product_line;
    }

    public function setRowNo($row_no)
    {
        $this->row_no = $row_no;
    }

    public function getRowNo()
    {
        return $this->row_no;
    }

    public function setMasterSku($master_sku)
    {
        $this->master_sku = $master_sku;
    }

    public function getMasterSku()
    {
        return $this->master_sku;
    }

    public function setTranType($tran_type)
    {
        $this->tran_type = $tran_type;
    }

    public function getTranType()
    {
        return $this->tran_type;
    }

    public function setDispatchDate($dispatch_date)
    {
        $this->dispatch_date = $dispatch_date;
    }

    public function getDispatchDate()
    {
        return $this->dispatch_date;
    }

    public function setCurrencyId($currency_id)
    {
        $this->currency_id = $currency_id;
    }

    public function getCurrencyId()
    {
        return $this->currency_id;
    }

    public function setReportPmgw($report_pmgw)
    {
        $this->report_pmgw = $report_pmgw;
    }

    public function getReportPmgw()
    {
        return $this->report_pmgw;
    }

    public function setFlexBatchId($flex_batch_id)
    {
        $this->flex_batch_id = $flex_batch_id;
    }

    public function getFlexBatchId()
    {
        return $this->flex_batch_id;
    }

    public function setProductCode($product_code)
    {
        $this->product_code = $product_code;
    }

    public function getProductCode()
    {
        return $this->product_code;
    }

    public function setQty($qty)
    {
        $this->qty = $qty;
    }

    public function getQty()
    {
        return $this->qty;
    }

    public function setUnitPrice($unit_price)
    {
        $this->unit_price = $unit_price;
    }

    public function getUnitPrice()
    {
        return $this->unit_price;
    }

    public function setTxnTime($txn_time)
    {
        $this->txn_time = $txn_time;
    }

    public function getTxnTime()
    {
        return $this->txn_time;
    }

    public function setSoNo($so_no)
    {
        $this->so_no = $so_no;
    }

    public function getSoNo()
    {
        return $this->so_no;
    }

    public function setBizType($biz_type)
    {
        $this->biz_type = $biz_type;
    }

    public function getBizType()
    {
        return $this->biz_type;
    }

    public function setShipLocCode($ship_loc_code)
    {
        $this->ship_loc_code = $ship_loc_code;
    }

    public function getShipLocCode()
    {
        return $this->ship_loc_code;
    }

    public function setTxnId($txn_id)
    {
        $this->txn_id = $txn_id;
    }

    public function getTxnId()
    {
        return $this->txn_id;
    }

    public function setCustomerEmail($customer_email)
    {
        $this->customer_email = $customer_email;
    }

    public function getCustomerEmail()
    {
        return $this->customer_email;
    }

    public function setDeliveryCharge($delivery_charge)
    {
        $this->delivery_charge = $delivery_charge;
    }

    public function getDeliveryCharge()
    {
        return $this->delivery_charge;
    }

    public function setGatewayId($gateway_id)
    {
        $this->gateway_id = $gateway_id;
    }

    public function getGatewayId()
    {
        return $this->gateway_id;
    }

    public function setRia($ria)
    {
        $this->ria = $ria;
    }

    public function getRia()
    {
        return $this->ria;
    }

    public function setRemark($remark)
    {
        $this->remark = $remark;
    }

    public function getRemark()
    {
        return $this->remark;
    }

    public function setOrderReason($order_reason)
    {
        $this->order_reason = $order_reason;
    }

    public function getOrderReason()
    {
        return $this->order_reason;
    }

    public function setAmount($amount)
    {
        $this->amount = $amount;
    }

    public function getAmount()
    {
        return $this->amount;
    }

    public function setContainSize($contain_size)
    {
        $this->contain_size = $contain_size;
    }

    public function getContainSize()
    {
        return $this->contain_size;
    }

    public function setSmCode($sm_code)
    {
        $this->sm_code = $sm_code;
    }

    public function getSmCode()
    {
        return $this->sm_code;
    }

    public function setOrderCreateDate($order_create_date)
    {
        $this->order_create_date = $order_create_date;
    }

    public function getOrderCreateDate()
    {
        return $this->order_create_date;
    }

    public function setPlatformId($platform_id)
    {
        $this->platform_id = $platform_id;
    }

    public function getPlatformId()
    {
        return $this->platform_id;
    }

    public function setSplitSoGroup($split_so_group)
    {
        $this->split_so_group = $split_so_group;
    }

    public function getSplitSoGroup()
    {
        return $this->split_so_group;
    }

    public function setParentSoNo($parent_so_no)
    {
        $this->parent_so_no = $parent_so_no;
    }

    public function getParentSoNo()
    {
        return $this->parent_so_no;
    }

    public function setLineIndex($line_index)
    {
        $this->line_index = $line_index;
    }

    public function getLineIndex()
    {
        return $this->line_index;
    }

    public function setReason($reason)
    {
        $this->reason = $reason;
    }

    public function getReason()
    {
        return $this->reason;
    }

}

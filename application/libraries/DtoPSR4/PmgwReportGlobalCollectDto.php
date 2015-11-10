<?php
class PmgwReportGlobalCollectDto
{
    private $merchant_id;
    private $contract_id;
    private $so_no;
    private $effort_id;
    private $type;
    private $payment_reference;
    private $customer_id;
    private $status_id;
    private $status_description;
    private $payment_product_id;
    private $payment_product_description;
    private $currency_id;
    private $amount;
    private $request_currency_code;
    private $request_amount;
    private $paid_currency;
    private $paid_amount;
    private $received_date;
    private $date;
    private $rejection_code;
    private $remarks;
    private $txn_id;
    private $internal_txn_id;
    private $ref_txn_id;
    private $txn_time;
    private $commission;

    public function setMerchantId($merchant_id)
    {
        $this->merchant_id = $merchant_id;
    }

    public function getMerchantId()
    {
        return $this->merchant_id;
    }

    public function setContractId($contract_id)
    {
        $this->contract_id = $contract_id;
    }

    public function getContractId()
    {
        return $this->contract_id;
    }

    public function setSoNo($so_no)
    {
        $this->so_no = $so_no;
    }

    public function getSoNo()
    {
        return $this->so_no;
    }

    public function setEffortId($effort_id)
    {
        $this->effort_id = $effort_id;
    }

    public function getEffortId()
    {
        return $this->effort_id;
    }

    public function setType($type)
    {
        $this->type = $type;
    }

    public function getType()
    {
        return $this->type;
    }

    public function setPaymentReference($payment_reference)
    {
        $this->payment_reference = $payment_reference;
    }

    public function getPaymentReference()
    {
        return $this->payment_reference;
    }

    public function setCustomerId($customer_id)
    {
        $this->customer_id = $customer_id;
    }

    public function getCustomerId()
    {
        return $this->customer_id;
    }

    public function setStatusId($status_id)
    {
        $this->status_id = $status_id;
    }

    public function getStatusId()
    {
        return $this->status_id;
    }

    public function setStatusDescription($status_description)
    {
        $this->status_description = $status_description;
    }

    public function getStatusDescription()
    {
        return $this->status_description;
    }

    public function setPaymentProductId($payment_product_id)
    {
        $this->payment_product_id = $payment_product_id;
    }

    public function getPaymentProductId()
    {
        return $this->payment_product_id;
    }

    public function setPaymentProductDescription($payment_product_description)
    {
        $this->payment_product_description = $payment_product_description;
    }

    public function getPaymentProductDescription()
    {
        return $this->payment_product_description;
    }

    public function setCurrencyId($currency_id)
    {
        $this->currency_id = $currency_id;
    }

    public function getCurrencyId()
    {
        return $this->currency_id;
    }

    public function setAmount($amount)
    {
        $this->amount = $amount;
    }

    public function getAmount()
    {
        return $this->amount;
    }

    public function setRequestCurrencyCode($request_currency_code)
    {
        $this->request_currency_code = $request_currency_code;
    }

    public function getRequestCurrencyCode()
    {
        return $this->request_currency_code;
    }

    public function setRequestAmount($request_amount)
    {
        $this->request_amount = $request_amount;
    }

    public function getRequestAmount()
    {
        return $this->request_amount;
    }

    public function setPaidCurrency($paid_currency)
    {
        $this->paid_currency = $paid_currency;
    }

    public function getPaidCurrency()
    {
        return $this->paid_currency;
    }

    public function setPaidAmount($paid_amount)
    {
        $this->paid_amount = $paid_amount;
    }

    public function getPaidAmount()
    {
        return $this->paid_amount;
    }

    public function setReceivedDate($received_date)
    {
        $this->received_date = $received_date;
    }

    public function getReceivedDate()
    {
        return $this->received_date;
    }

    public function setDate($date)
    {
        $this->date = $date;
    }

    public function getDate()
    {
        return $this->date;
    }

    public function setRejectionCode($rejection_code)
    {
        $this->rejection_code = $rejection_code;
    }

    public function getRejectionCode()
    {
        return $this->rejection_code;
    }

    public function setRemarks($remarks)
    {
        $this->remarks = $remarks;
    }

    public function getRemarks()
    {
        return $this->remarks;
    }

    public function setTxnId($txn_id)
    {
        $this->txn_id = $txn_id;
    }

    public function getTxnId()
    {
        return $this->txn_id;
    }

    public function setInternalTxnId($internal_txn_id)
    {
        $this->internal_txn_id = $internal_txn_id;
    }

    public function getInternalTxnId()
    {
        return $this->internal_txn_id;
    }

    public function setRefTxnId($ref_txn_id)
    {
        $this->ref_txn_id = $ref_txn_id;
    }

    public function getRefTxnId()
    {
        return $this->ref_txn_id;
    }

    public function setTxnTime($txn_time)
    {
        $this->txn_time = $txn_time;
    }

    public function getTxnTime()
    {
        return $this->txn_time;
    }

    public function setCommission($commission)
    {
        $this->commission = $commission;
    }

    public function getCommission()
    {
        return $this->commission;
    }

}

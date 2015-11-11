<?php
class RiaGateMappingDto
{
    private $currency_id;
    private $amount;
    private $status;
    private $tran_type;
    private $report_pmgw;
    private $flex_batch_id;
    private $txn_time;
    private $txn_id;

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

    public function setStatus($status)
    {
        $this->status = $status;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function setTranType($tran_type)
    {
        $this->tran_type = $tran_type;
    }

    public function getTranType()
    {
        return $this->tran_type;
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

    public function setTxnTime($txn_time)
    {
        $this->txn_time = $txn_time;
    }

    public function getTxnTime()
    {
        return $this->txn_time;
    }

    public function setTxnId($txn_id)
    {
        $this->txn_id = $txn_id;
    }

    public function getTxnId()
    {
        return $this->txn_id;
    }

}

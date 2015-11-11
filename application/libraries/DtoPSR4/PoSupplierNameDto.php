<?php
class PoSupplierNameDto
{
    private $po_number;
    private $supplier_name;
    private $supplier_invoice_number;
    private $delivery_mode;
    private $status;
    private $currency;
    private $amount;
    private $eta;
    private $currency_name;
    private $purchase_detail;
    private $po_message;
    private $create_on;
    private $create_at;
    private $create_by;
    private $modify_on;
    private $modify_at;
    private $modify_by;

    public function setPoNumber($po_number)
    {
        $this->po_number = $po_number;
    }

    public function getPoNumber()
    {
        return $this->po_number;
    }

    public function setSupplierName($supplier_name)
    {
        $this->supplier_name = $supplier_name;
    }

    public function getSupplierName()
    {
        return $this->supplier_name;
    }

    public function setSupplierInvoiceNumber($supplier_invoice_number)
    {
        $this->supplier_invoice_number = $supplier_invoice_number;
    }

    public function getSupplierInvoiceNumber()
    {
        return $this->supplier_invoice_number;
    }

    public function setDeliveryMode($delivery_mode)
    {
        $this->delivery_mode = $delivery_mode;
    }

    public function getDeliveryMode()
    {
        return $this->delivery_mode;
    }

    public function setStatus($status)
    {
        $this->status = $status;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function setCurrency($currency)
    {
        $this->currency = $currency;
    }

    public function getCurrency()
    {
        return $this->currency;
    }

    public function setAmount($amount)
    {
        $this->amount = $amount;
    }

    public function getAmount()
    {
        return $this->amount;
    }

    public function setEta($eta)
    {
        $this->eta = $eta;
    }

    public function getEta()
    {
        return $this->eta;
    }

    public function setCurrencyName($currency_name)
    {
        $this->currency_name = $currency_name;
    }

    public function getCurrencyName()
    {
        return $this->currency_name;
    }

    public function setPurchaseDetail($purchase_detail)
    {
        $this->purchase_detail = $purchase_detail;
    }

    public function getPurchaseDetail()
    {
        return $this->purchase_detail;
    }

    public function setPoMessage($po_message)
    {
        $this->po_message = $po_message;
    }

    public function getPoMessage()
    {
        return $this->po_message;
    }

    public function setCreateOn($create_on)
    {
        $this->create_on = $create_on;
    }

    public function getCreateOn()
    {
        return $this->create_on;
    }

    public function setCreateAt($create_at)
    {
        $this->create_at = $create_at;
    }

    public function getCreateAt()
    {
        return $this->create_at;
    }

    public function setCreateBy($create_by)
    {
        $this->create_by = $create_by;
    }

    public function getCreateBy()
    {
        return $this->create_by;
    }

    public function setModifyOn($modify_on)
    {
        $this->modify_on = $modify_on;
    }

    public function getModifyOn()
    {
        return $this->modify_on;
    }

    public function setModifyAt($modify_at)
    {
        $this->modify_at = $modify_at;
    }

    public function getModifyAt()
    {
        return $this->modify_at;
    }

    public function setModifyBy($modify_by)
    {
        $this->modify_by = $modify_by;
    }

    public function getModifyBy()
    {
        return $this->modify_by;
    }

}

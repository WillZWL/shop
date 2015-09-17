<?php
class SupplierInvoiceDto
{
    private $index_no;
    private $product_line;
    private $row_no;
    private $master_sku;
    private $tran_type;
    private $dispatch_date;
    private $currency_id;
    private $supplier_code;
    private $siv;
    private $product_code;
    private $qty;
    private $unit_price;
    private $ship_loc_code;

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

    public function setSupplierCode($supplier_code)
    {
        $this->supplier_code = $supplier_code;
    }

    public function getSupplierCode()
    {
        return $this->supplier_code;
    }

    public function setSiv($siv)
    {
        $this->siv = $siv;
    }

    public function getSiv()
    {
        return $this->siv;
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

    public function setShipLocCode($ship_loc_code)
    {
        $this->ship_loc_code = $ship_loc_code;
    }

    public function getShipLocCode()
    {
        return $this->ship_loc_code;
    }

}
